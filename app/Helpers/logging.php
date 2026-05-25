<?php

if (!function_exists('app_log')) {
    function app_log(string $level, string $message, array $context = []): void
    {
        $time = date('Y-m-d H:i:s');

        // Require PDO for DB logging. If PDO is missing or write fails, do nothing.
        $pdo = $GLOBALS['app_pdo'] ?? null;
        if (!($pdo instanceof \PDO)) {
            return;
        }

        try {
            // Create table if missing (best-effort)
            $create = "CREATE TABLE IF NOT EXISTS `audit_logs` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `created_at` DATETIME NOT NULL,
                `level` VARCHAR(20) NOT NULL,
                `message` TEXT NOT NULL,
                `context` JSON DEFAULT NULL,
                `ip` VARCHAR(45) DEFAULT NULL,
                `created_by` INT DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
            $pdo->exec($create);

            $stmt = $pdo->prepare('INSERT INTO audit_logs (created_at, level, message, context, ip, created_by) VALUES (?, ?, ?, ?, ?, ?)');
            $ctxJson = json_encode($context, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            $ip = $context['ip'] ?? ($_SERVER['REMOTE_ADDR'] ?? null);
            $createdBy = $context['admin_id'] ?? $context['staff_id'] ?? null;
            $stmt->execute([$time, strtolower($level), $message, $ctxJson, $ip, $createdBy]);
            return;
        } catch (\Throwable $e) {
            // If DB write fails, do nothing to avoid logging sensitive exceptions here.
            return;
        }
    }
}