<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.html");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $plan_id = $_POST['plan_id'];
  $user_id = $_SESSION['user_id'];

  $conn = new mysqli('localhost', 'root', '', 'srsfy');
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Update user's plan
  $update = $conn->query("UPDATE users SET plan_id = $plan_id WHERE id = $user_id");

  if ($update) {
    $_SESSION['plan_id'] = $plan_id; // update session
    header("Location: home.php");
    exit();
  } else {
    echo "Failed to update plan.";
  }
}
?>
