<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.html");
  exit();
}

$conn = new mysqli('localhost', 'root', '', 'srsfy');
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$plan_id = $_GET['plan_id'] ?? $_POST['plan_id'] ?? null;
$user_id = $_SESSION['user_id'];

if (!$plan_id) {
  echo "No plan selected.";
  exit();
}

// Fetch plan
$plan_result = $conn->query("SELECT * FROM plans WHERE id = $plan_id");
$plan = $plan_result->fetch_assoc();

if (!$plan) {
  echo "Invalid plan.";
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $amount = $_POST['amount_entered'];
  if ($amount == $plan['price']) {
    $update = $conn->query("UPDATE users SET plan_id = $plan_id WHERE id = $user_id");
    if ($update) {
      $_SESSION['plan_id'] = $plan_id;
      header("Location: home.php");
      exit();
    } else {
      echo "Failed to upgrade plan.";
    }
  } else {
    $error = "Incorrect amount. Please enter ₹" . $plan['price'];
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Confirm Plan Payment</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container" style="margin-top: 100px;">
    <h2>Upgrade to: <?php echo htmlspecialchars($plan['name']); ?></h2>
    <p>Plan Price: ₹<?php echo $plan['price']; ?></p>

    <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>

    <form method="POST">
      <input type="hidden" name="plan_id" value="<?php echo $plan['id']; ?>">
      <label>Enter the exact amount to confirm:</label>
      <input type="number" name="amount_entered" required>
      <button type="submit">Confirm Upgrade</button>
    </form>
  </div>
</body>
</html>
