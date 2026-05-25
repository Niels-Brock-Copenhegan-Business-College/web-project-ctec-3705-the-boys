<?php
namespace App\Models;

class StaffModel
{
    public function __construct(private \PDO $pdo) {}

    public function getAll(): array
    {
        return $this->pdo->query('SELECT * FROM staff ORDER BY full_name')->fetchAll();
    }

    public function getPdo(): \PDO { return $this->pdo; }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM staff WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function findByUsername(string $username): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM staff WHERE username = ?');
        $stmt->execute([$username]);
        return $stmt->fetch() ?: null;
    }

    public function verifyLogin(string $username, string $password): ?array
    {
        $staff = $this->findByUsername($username);
        if ($staff && password_verify($password, $staff['password_hash'])) {
            return $staff;
        }
        return null;
    }

    public function incrementLoginAttempts(int $id): void
{
    $this->pdo->prepare(
        'UPDATE staff SET login_attempts = login_attempts + 1 WHERE id = ?'
    )->execute([$id]);
}

public function lockAccount(int $id, int $minutes = 15): void
{
    $this->pdo->prepare(
        'UPDATE staff SET login_attempts = login_attempts + 1,
         locked_until = DATE_ADD(NOW(), INTERVAL ? MINUTE) WHERE id = ?'
    )->execute([$minutes, $id]);
}

public function resetLoginAttempts(int $id): void
{
    $this->pdo->prepare(
        'UPDATE staff SET login_attempts = 0, locked_until = NULL WHERE id = ?'
    )->execute([$id]);
}

    public function createPasswordResetToken(int $staffId, ?int $createdBy = null): array
    {
        $this->pdo->prepare('DELETE FROM staff_password_resets WHERE staff_id = ? AND used_at IS NULL')
                  ->execute([$staffId]);

        $token = bin2hex(random_bytes(32));
        $tokenHash = hash('sha256', $token);
        $expiresAt = (new \DateTimeImmutable('+1 hour'))->format('Y-m-d H:i:s');

        $stmt = $this->pdo->prepare(
            'INSERT INTO staff_password_resets (staff_id, token_hash, expires_at, created_by)
             VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([$staffId, $tokenHash, $expiresAt, $createdBy]);

        return [
            'token' => $token,
            'expires_at' => $expiresAt,
        ];
    }

    public function findPasswordResetToken(string $token): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT spr.id AS reset_id,
                    spr.staff_id,
                    spr.expires_at,
                    spr.used_at,
                    spr.created_at,
                    s.username,
                    s.email,
                    s.full_name,
                    s.is_active
             FROM staff_password_resets spr
             JOIN staff s ON s.id = spr.staff_id
             WHERE spr.token_hash = ?
             LIMIT 1'
        );
        $stmt->execute([hash('sha256', $token)]);
        $reset = $stmt->fetch();

        if (!$reset || !empty($reset['used_at']) || strtotime($reset['expires_at']) < time()) {
            return null;
        }

        return $reset;
    }

    public function resetPasswordWithToken(string $token, string $newPassword): bool
    {
        $tokenHash = hash('sha256', $token);

        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare(
                'SELECT id, staff_id
                 FROM staff_password_resets
                 WHERE token_hash = ?
                   AND used_at IS NULL
                   AND expires_at > NOW()
                 LIMIT 1
                 FOR UPDATE'
            );
            $stmt->execute([$tokenHash]);
            $reset = $stmt->fetch();

            if (!$reset) {
                $this->pdo->rollBack();
                return false;
            }

            $updateStaff = $this->pdo->prepare('UPDATE staff SET password_hash = ? WHERE id = ?');
            $updateStaff->execute([password_hash($newPassword, PASSWORD_BCRYPT), (int) $reset['staff_id']]);

            $markUsed = $this->pdo->prepare('UPDATE staff_password_resets SET used_at = NOW() WHERE id = ?');
            $markUsed->execute([(int) $reset['id']]);

            $this->pdo->commit();
            return true;
        } catch (\Throwable) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            return false;
        }
    }

    public function deletePasswordResetToken(string $token): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM staff_password_resets WHERE token_hash = ?');
        $stmt->execute([hash('sha256', $token)]);
    }

    public function create(array $data, int $createdBy): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO staff (username, password_hash, email, full_name, role, is_active, created_by)
             VALUES (?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $data['username'],
            password_hash($data['password'], PASSWORD_BCRYPT),
            $data['email'],
            $data['full_name'],
            $data['role'] ?? 'instructor',
            $data['is_active'] ?? 1,
            $createdBy,
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $fields = [];
        $values = [];
        foreach (['full_name', 'email', 'role', 'is_active', 'photo', 'bio'] as $f) {
            if (isset($data[$f])) {
                $fields[] = "$f = ?";
                $values[] = $data[$f];
            }
        }
        if (isset($data['password'])) {
            $fields[] = 'password_hash = ?';
            $values[] = password_hash($data['password'], PASSWORD_BCRYPT);
        }
        if (empty($fields)) return;
        $values[] = $id;
        $this->pdo->prepare('UPDATE staff SET ' . implode(', ', $fields) . ' WHERE id = ?')
                  ->execute($values);
    }

    public function delete(int $id): void
    {
        $this->pdo->prepare('DELETE FROM staff WHERE id = ?')->execute([$id]);
    }

    // ── Module assignments ────────────────────────────────────────

    public function getAssignedModules(int $staffId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT m.* FROM modules m
             JOIN staff_modules sm ON sm.module_id = m.id
             WHERE sm.staff_id = ?
             ORDER BY m.year_of_study ASC, m.title ASC'
        );
        $stmt->execute([$staffId]);
        return $stmt->fetchAll();
    }

    /**
     * Modules with their detail plus which programmes they belong to.
     * Adapted for DB without role column on staff_modules.
     */
    public function getModuleDetail(int $moduleId): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM modules WHERE id = ?');
        $stmt->execute([$moduleId]);
        $module = $stmt->fetch();
        if (!$module) return null;

        // All staff on this module
        $stmt = $this->pdo->prepare(
            'SELECT s.id, s.full_name, s.email, s.role AS staff_role
             FROM staff s
             JOIN staff_modules sm ON sm.staff_id = s.id
             WHERE sm.module_id = ?
             ORDER BY s.full_name ASC'
        );
        $stmt->execute([$moduleId]);
        $module['staff'] = $stmt->fetchAll();

        // Programmes this module appears in
        $stmt = $this->pdo->prepare(
            'SELECT p.id, p.title, p.level, p.is_published
             FROM programmes p
             JOIN programme_modules pm ON pm.programme_id = p.id
             WHERE pm.module_id = ?
             ORDER BY p.title ASC'
        );
        $stmt->execute([$moduleId]);
        $module['programmes'] = $stmt->fetchAll();

        return $module;
    }

    // ── Programme assignments ─────────────────────────────────────

 public function getAssignedProgrammes(int $staffId): array
{
    $stmt = $this->pdo->prepare(
        'SELECT p.*,
                (SELECT COUNT(*) FROM programme_modules pm WHERE pm.programme_id = p.id)        AS module_count,
                (SELECT COUNT(*) FROM interest_registrations ir WHERE ir.programme_id = p.id)   AS interest_count,
                (
                    SELECT COUNT(DISTINCT s2.id)
                    FROM staff s2
                    LEFT JOIN staff_programmes sp2 ON sp2.staff_id = s2.id AND sp2.programme_id = p.id
                    LEFT JOIN staff_modules sm2 ON sm2.staff_id = s2.id
                    LEFT JOIN programme_modules pm2 ON pm2.module_id = sm2.module_id AND pm2.programme_id = p.id
                    WHERE sp2.staff_id IS NOT NULL OR pm2.programme_id IS NOT NULL
                )                                                                                AS team_count,
                (SELECT COUNT(*) FROM staff_modules sm
                 JOIN programme_modules pm ON pm.module_id = sm.module_id
                 WHERE pm.programme_id = p.id AND sm.staff_id = ?)                              AS my_module_count
         FROM programmes p
         JOIN staff_programmes sp ON sp.programme_id = p.id
         WHERE sp.staff_id = ?
         ORDER BY p.level ASC, p.title ASC'
    );
    $stmt->execute([$staffId, $staffId]);
    return $stmt->fetchAll();
}

    /**
     * Full programme detail for staff view.
     */
    public function getProgrammeDetail(int $programmeId): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM programmes WHERE id = ?');
        $stmt->execute([$programmeId]);
        $programme = $stmt->fetch();
        if (!$programme) return null;

       // Staff team — deduplicated by id, source just tells us how they're linked
$stmt = $this->pdo->prepare(
    'SELECT s.id, s.full_name, s.email, s.photo, s.role AS staff_role,
            CASE
                WHEN sp.staff_id IS NOT NULL AND sm_check.staff_id IS NOT NULL THEN "both"
                WHEN sp.staff_id IS NOT NULL THEN "programme"
                ELSE "module"
            END AS source
     FROM staff s
     LEFT JOIN staff_programmes sp
           ON sp.staff_id = s.id AND sp.programme_id = ?
     LEFT JOIN (
         SELECT DISTINCT sm.staff_id
         FROM staff_modules sm
         JOIN programme_modules pm ON pm.module_id = sm.module_id
         WHERE pm.programme_id = ?
     ) sm_check ON sm_check.staff_id = s.id
     WHERE sp.staff_id IS NOT NULL OR sm_check.staff_id IS NOT NULL
     ORDER BY s.full_name ASC'
);
$stmt->execute([$programmeId, $programmeId]);
$programme['staff'] = $stmt->fetchAll();

        // Modules grouped by year
        $stmt = $this->pdo->prepare(
            'SELECT m.*
             FROM modules m
             JOIN programme_modules pm ON pm.module_id = m.id
             WHERE pm.programme_id = ?
             ORDER BY m.year_of_study ASC, m.title ASC'
        );
        $stmt->execute([$programmeId]);
        $byYear = [];
        foreach ($stmt->fetchAll() as $m) {
            $byYear[(int)$m['year_of_study']][] = $m;
        }
        $programme['modulesByYear'] = $byYear;

        // Interest count
        $stmt = $this->pdo->prepare(
            'SELECT COUNT(*) AS cnt FROM interest_registrations WHERE programme_id = ?'
        );
        $stmt->execute([$programmeId]);
        $programme['interest_count'] = (int)$stmt->fetch()['cnt'];

        return $programme;
    }

    /**
     * Get all staff linked to a programme.
     * Used by the student-facing programme detail page.
     */
    public function getByProgramme(int $programmeId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT s.id, s.full_name, s.email, s.role AS staff_role, s.photo, s.bio
             FROM staff s
             JOIN staff_programmes sp ON sp.staff_id = s.id
             WHERE sp.programme_id = ?
             ORDER BY s.full_name ASC'
        );
        $stmt->execute([$programmeId]);
        return $stmt->fetchAll();
    }

    // ── Admin assignment helpers ──────────────────────────────────

    public function getAllModules(): array
    {
        return $this->pdo->query('SELECT * FROM modules ORDER BY title')->fetchAll();
    }

    public function getAllProgrammes(): array
    {
        return $this->pdo->query('SELECT * FROM programmes ORDER BY title')->fetchAll();
    }

    public function getUnassignedModules(int $staffId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM modules WHERE id NOT IN
             (SELECT module_id FROM staff_modules)
             ORDER BY title'
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getUnassignedProgrammes(int $staffId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM programmes WHERE id NOT IN
             (SELECT programme_id FROM staff_programmes)
             ORDER BY title'
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function assignModule(int $staffId, int $moduleId): void
    {
        // Prevent assigning a module that's already assigned to any staff
        $check = $this->pdo->prepare('SELECT 1 FROM staff_modules WHERE module_id = ? LIMIT 1');
        $check->execute([$moduleId]);
        if ($check->fetch()) {
            return;
        }

        $stmt = $this->pdo->prepare('INSERT INTO staff_modules (staff_id, module_id) VALUES (?, ?)');
        $stmt->execute([$staffId, $moduleId]);
    }

    public function assignProgramme(int $staffId, int $programmeId): void
    {
        // Prevent assigning a programme that's already assigned to any staff
        $check = $this->pdo->prepare('SELECT 1 FROM staff_programmes WHERE programme_id = ? LIMIT 1');
        $check->execute([$programmeId]);
        if ($check->fetch()) {
            return;
        }

        $stmt = $this->pdo->prepare('INSERT INTO staff_programmes (staff_id, programme_id) VALUES (?, ?)');
        $stmt->execute([$staffId, $programmeId]);
    }

    public function unassignModule(int $staffId, int $moduleId): void
    {
        $this->pdo->prepare('DELETE FROM staff_modules WHERE staff_id = ? AND module_id = ?')
                  ->execute([$staffId, $moduleId]);
    }

    public function unassignProgramme(int $staffId, int $programmeId): void
    {
        $this->pdo->prepare('DELETE FROM staff_programmes WHERE staff_id = ? AND programme_id = ?')
                  ->execute([$staffId, $programmeId]);
    }

    public function clearModules(int $staffId): void
    {
        $this->pdo->prepare('DELETE FROM staff_modules WHERE staff_id = ?')->execute([$staffId]);
    }

    public function clearProgrammes(int $staffId): void
    {
        $this->pdo->prepare('DELETE FROM staff_programmes WHERE staff_id = ?')->execute([$staffId]);
    }

    public function countAll(): int
    {
        return (int) $this->pdo->query('SELECT COUNT(*) as cnt FROM staff')->fetch()['cnt'];
    }
}