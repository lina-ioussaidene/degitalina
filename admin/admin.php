<?php
require 'config.php';
require_admin();

// Traitement des actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_ok($_POST['csrf'] ?? '')) {
    $id = (int)($_POST['id'] ?? 0);
    $action = $_POST['action'] ?? '';
    if ($id > 0) {
        if ($action === 'delete') {
            $stmt = $mysqli->prepare("DELETE FROM contacts WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
        } elseif ($action === 'done') {
            $stmt = $mysqli->prepare("UPDATE contacts SET statut = 'Terminé' WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
        } elseif ($action === 'validate') {
            $stmt = $mysqli->prepare("UPDATE contacts SET validation = 'Validée' WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
        } elseif ($action === 'invalidate') {
            $stmt = $mysqli->prepare("UPDATE contacts SET validation = 'Non validée' WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
        }
    }
}

// Récupération des données
$result = $mysqli->query("SELECT * FROM contacts ORDER BY date_envoi DESC");

// Compteurs
$total = $validées = $non_validées = $terminées = 0;
$rows = [];
while ($row = $result->fetch_assoc()) {
    $rows[] = $row;
    $total++;
    if ($row['validation'] === 'Validée') $validées++;
    if ($row['validation'] === 'Non validée') $non_validées++;
    if ($row['statut'] === 'Terminé') $terminées++;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Digitalina — Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #0f172a;
      color: #f1f5f9;
    }
    .container {
      max-width: 1200px;
      margin: auto;
      padding: 20px;
    }
    h1 {
      font-size: 2rem;
      margin-bottom: 10px;
      color: #38bdf8;
    }
    .stats {
      display: flex;
      gap: 20px;
      margin-bottom: 20px;
    }
    .stat-box {
      background: #1e293b;
      padding: 10px 20px;
      border-radius: 8px;
      font-weight: bold;
      color: #f1f5f9;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      background: #1e293b;
      border-radius: 8px;
      overflow: hidden;
    }
    th, td {
      padding: 12px;
      border-bottom: 1px solid #334155;
      text-align: left;
    }
    th {
      background: #0ea5e9;
      color: white;
      position: sticky;
      top: 0;
    }
    tr.highlight {
      background-color: #047857 !important;
    }
    .actions form {
      display: inline;
    }
    .btn {
      background: #0ea5e9;
      color: white;
      border: none;
      padding: 6px 10px;
      margin: 2px;
      border-radius: 6px;
      cursor: pointer;
      font-size: 0.9rem;
    }
    .btn:hover {
      background: #0284c7;
    }
    .btn.red {
      background: #ef4444;
    }
    .btn.red:hover {
      background: #dc2626;
    }
    .logout {
      text-align: right;
      margin-bottom: 20px;
    }
    .logout form {
      display: inline;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="logout">
      <form method="post" action="logout.php">
        <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
        <button class="btn">Se déconnecter</button>
      </form>
    </div>

    <h1>📋 Tableau des commandes</h1>

    <div class="stats">
      <div class="stat-box">✅ Validées : <?= $validées ?></div>
      <div class="stat-box">❌ Non validées : <?= $non_validées ?></div>
      <div class="stat-box">✔ Terminées : <?= $terminées ?></div>
      <div class="stat-box">📦 Total : <?= $total ?></div>
    </div>

    <table>
      <thead>
        <tr>
          <th>Nom</th>
          <th>Email</th>
          <th>Sujet</th>
          <th>Message</th>
          <th>Date</th>
          <th>Statut</th>
          <th>Validation</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $row): ?>
        <tr class="<?= $row['statut'] === 'Terminé' ? 'highlight' : '' ?>">
          <td><?= htmlspecialchars($row['nom_complet']) ?></td>
          <td><?= htmlspecialchars($row['email']) ?></td>
          <td><?= htmlspecialchars($row['sujet']) ?></td>
          <td><?= htmlspecialchars($row['message']) ?></td>
          <td><?= $row['date_envoi'] ?></td>
          <td><?= $row['statut'] ?></td>
          <td><?= $row['validation'] ?></td>
          <td class="actions">
            <form method="post">
              <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
              <input type="hidden" name="id" value="<?= $row['id'] ?>">
              <input type="hidden" name="action" value="done">
              <button class="btn">✔ Terminer</button>
            </form>
            <form method="post">
              <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
              <input type="hidden" name="id" value="<?= $row['id'] ?>">
              <input type="hidden" name="action" value="validate">
              <button class="btn">✔ Valider</button>
            </form>
            <form method="post">
              <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
              <input type="hidden" name="id" value="<?= $row['id'] ?>">
              <input type="hidden" name="action" value="invalidate">
              <button class="btn">❌ Non validée</button>
            </form>
            <form method="post" onsubmit="return confirm('Supprimer ce message ?')">
              <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
              <input type="hidden" name="id" value="<?= $row['id'] ?>">
              <input type="hidden" name="action" value="delete">
              <button class="btn red">🗑 Supprimer</button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</body>
</html>