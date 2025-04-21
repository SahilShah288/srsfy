<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.html");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $plan_id = $_POST['plan_id'];

  // Redirect to upgrade.php with plan_id
  header("Location: upgrade.php?plan_id=" . urlencode($plan_id));
  exit();
}
?>
