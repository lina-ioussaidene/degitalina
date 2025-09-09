<?php
require 'config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_ok($_POST['csrf'] ?? null)) {
    $_SESSION = [];
    session_destroy();
}
header("Location: login.php");
exit;