<?php
namespace App\Models;

class StaffModel
{
    public function __construct(private \PDO $pdo) {}

    public function getAll(): array
    {
        return $this->pdo->query('SELECT * FROM staff ORDER BY full_name')->fetchAll();
    }

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
        foreach (['full_name', 'email', 'role', 'is_active'] as $f) {
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
                    (SELECT COUNT(*) FROM staff_programmes sp2 WHERE sp2.programme_id = p.id)       AS team_count,
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

        // Staff team (no role column — just list them)
        $stmt = $this->pdo->prepare(
            'SELECT s.id, s.full_name, s.email, s.role AS staff_role
             FROM staff s
             JOIN staff_programmes sp ON sp.staff_id = s.id
             WHERE sp.programme_id = ?
             ORDER BY s.full_name ASC'
        );
        $stmt->execute([$programmeId]);
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
            'SELECT s.id, s.full_name, s.email, s.role AS staff_role
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
             (SELECT module_id FROM staff_modules WHERE staff_id = ?)
             ORDER BY title'
        );
        $stmt->execute([$staffId]);
        return $stmt->fetchAll();
    }

    public function getUnassignedProgrammes(int $staffId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM programmes WHERE id NOT IN
             (SELECT programme_id FROM staff_programmes WHERE staff_id = ?)
             ORDER BY title'
        );
        $stmt->execute([$staffId]);
        return $stmt->fetchAll();
    }

    public function assignModule(int $staffId, int $moduleId): void
    {
        $stmt = $this->pdo->prepare(
            'INSERT IGNORE INTO staff_modules (staff_id, module_id) VALUES (?, ?)'
        );
        $stmt->execute([$staffId, $moduleId]);
    }

    public function assignProgramme(int $staffId, int $programmeId): void
    {
        $stmt = $this->pdo->prepare(
            'INSERT IGNORE INTO staff_programmes (staff_id, programme_id) VALUES (?, ?)'
        );
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
