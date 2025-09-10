<?php
// admin/config.example.php
declare(strict_types=1);
session_start();

// Connexion à MySQL (exemple fictif)
$mysqli = new mysqli("HOST_MYSQL", "UTILISATEUR", "MOT_DE_PASSE", "NOM_DE_LA_BASE");
if ($mysqli->connect_error) {
    die("Erreur MySQL : " . $mysqli->connect_error);
}
$mysqli->set_charset("utf8mb4");

// Mot de passe admin fictif
const ADMIN_PASSWORD_HASH = 'HASH_FICTIF';

// Fonctions CSRF et Auth (inchangées)
function csrf_token(): string {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}
function csrf_ok(?string $token): bool {
    return isset($_SESSION['csrf_token']) && is_string($token) && hash_equals($_SESSION['csrf_token'], $token);
}
function require_admin(): void {
    if (empty($_SESSION['is_admin'])) {
        header("Location: login.php");
        exit;
    }
}
