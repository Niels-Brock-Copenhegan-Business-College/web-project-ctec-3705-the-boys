<?php
namespace App\Models;

class SuperAdminModel
{
    public function __construct(private \PDO $pdo) {}

    public function findByUsername(string $username): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM super_admins WHERE username = ?');
        $stmt->execute([$username]);
        return $stmt->fetch() ?: null;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM super_admins WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function verifyLogin(string $username, string $password): ?array
    {
        $sa = $this->findByUsername($username);
        if ($sa && !empty($sa['password_hash']) && password_verify($password, $sa['password_hash'])) {
            return $sa;
        }
        return null;
    }

    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO super_admins (name, username, email, password_hash, is_active, created_at)
             VALUES (?, ?, ?, ?, ?, NOW())'
        );
        $stmt->execute([
            $data['name'] ?? null,
            $data['username'] ?? null,
            $data['email'] ?? null,
            isset($data['password']) ? password_hash($data['password'], PASSWORD_BCRYPT) : null,
            $data['is_active'] ?? 1,
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    public function setPassword(int $id, string $password): void
    {
        $this->pdo->prepare('UPDATE super_admins SET password_hash = ?, updated_at = NOW() WHERE id = ?')
                  ->execute([password_hash($password, PASSWORD_BCRYPT), $id]);
    }
}
