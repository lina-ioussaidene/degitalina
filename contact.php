<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "⛔ Accès non autorisé.";
    exit;
}

$nom     = $_POST['name'] ?? '';
$email   = $_POST['email'] ?? '';
$sujet   = $_POST['subject'] ?? '';
$message = $_POST['message'] ?? '';

if ($nom === '' || $email === '' || $sujet === '' || $message === '') {
    echo "❌ Veuillez remplir tous les champs.";
    exit;
}

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "digitalina";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    echo "❌ Erreur de connexion à la base de données.";
    exit;
}

$sql = "INSERT INTO contacts (nom_complet, email, sujet, message) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo "❌ Erreur interne. Veuillez réessayer plus tard.";
    exit;
}

$stmt->bind_param("ssss", $nom, $email, $sujet, $message);

if ($stmt->execute()) {
    echo "✅ Votre message a bien été envoyé. Merci !";
} else {
    echo "❌ Une erreur est survenue. Veuillez réessayer.";
}

$stmt->close();
$conn->close();
?>
