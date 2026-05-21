<?php
namespace App\Models;

class AdminModel
{
    public function __construct(private \PDO $pdo) {}

    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT * FROM admins WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    /**
     * Verify if provided secret code matches admin's stored secret code hash
     */
    public function verifySecretCode(int $adminId, string $secretCode): bool
    {
        $stmt = $this->pdo->prepare('SELECT secret_code_hash FROM admins WHERE id = ? LIMIT 1');
        $stmt->execute([$adminId]);
        $admin = $stmt->fetch();

        if (!$admin || empty($admin['secret_code_hash'])) {
            return false;
        }

        return password_verify($secretCode, $admin['secret_code_hash']);
    }
}
