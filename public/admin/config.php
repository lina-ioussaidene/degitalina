<?php
// admin/config.php
declare(strict_types=1);
session_start();

// Connexion à MySQL
$mysqli = new mysqli("localhost", "root", "", "digitalina");
if ($mysqli->connect_error) {
    die("Erreur MySQL : " . $mysqli->connect_error);
}
$mysqli->set_charset("utf8mb4");

// Mot de passe unique (à remplacer par mon hash)
const ADMIN_PASSWORD_HASH = '$2y$10$A2JTuJGRORkmThnFk0Uo.uPfpBp0qrgtIlVBPTJMQRAJ2pU7VLyR6';

// CSRF
function csrf_token(): string {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}
function csrf_ok(?string $token): bool {
    return isset($_SESSION['csrf_token']) && is_string($token) && hash_equals($_SESSION['csrf_token'], $token);
}

// Auth
function require_admin(): void {
    if (empty($_SESSION['is_admin'])) {
        header("Location: login.php");
        exit;
    }
}
