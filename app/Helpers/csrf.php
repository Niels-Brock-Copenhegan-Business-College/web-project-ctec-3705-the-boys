<?php
/**
 * app/Helpers/csrf.php
 * Drop in app/Helpers/ and require once in public/index.php
 */

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];;
}

function csrf_verify(string $token): bool
{
    $stored = $_SESSION['csrf_token'] ?? '';
    if (!$stored || !hash_equals($stored, $token)) {
        return false;
    }
    // Keep token stable to allow multiple forms on the same page to submit reliably.
    // If you require single-use tokens, re-enable rotation here.
    return true;
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrf_token(), ENT_QUOTES) . '">';
}