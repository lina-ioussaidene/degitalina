<?php
require 'config.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pwd = $_POST['password'] ?? '';
    $token = $_POST['csrf'] ?? '';

    if (!csrf_ok($token)) {
        $error = "Action refusée.";
    } elseif (password_verify($pwd, ADMIN_PASSWORD_HASH)) {
        session_regenerate_id(true);
        $_SESSION['is_admin'] = true;
        header("Location: admin.php");
        exit;
    } else {
        $error = "Mot de passe incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Digitalina — Connexion Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #0f172a;
      color: #f1f5f9;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }
    .login-box {
      background: #1e293b;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 20px rgba(0,0,0,0.3);
      width: 100%;
      max-width: 400px;
    }
    h1 {
      text-align: center;
      color: #38bdf8;
      margin-bottom: 20px;
    }
    label {
      display: block;
      margin-bottom: 6px;
      font-weight: bold;
    }
    input[type="password"] {
      width: 100%;
      padding: 10px;
      border: none;
      border-radius: 8px;
      background: #334155;
      color: #f1f5f9;
      margin-bottom: 16px;
    }
    .btn {
      width: 100%;
      padding: 10px;
      background: #0ea5e9;
      color: white;
      border: none;
      border-radius: 8px;
      font-weight: bold;
      cursor: pointer;
    }
    .btn:hover {
      background: #0284c7;
    }
    .error {
      background: #dc2626;
      color: white;
      padding: 10px;
      border-radius: 8px;
      margin-bottom: 16px;
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="login-box">
    <h1>🔐 Accès Admin</h1>
    <?php if ($error): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post">
      <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
      <label for="password">Mot de passe :</label>
      <input type="password" name="password" id="password" required autocomplete="current-password">
      <button class="btn">Se connecter</button>
    </form>
  </div>
</body>
</html>