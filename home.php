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

$user_id = $_SESSION['user_id'];
$name = $_SESSION['fullname'];
$plan_id = $_SESSION['plan_id'];

// Fetch user's current plan
$plan_sql = "SELECT * FROM plans WHERE id = $plan_id";
$plan_result = $conn->query($plan_sql);
$plan = $plan_result->fetch_assoc();

// Fetch songs
$songs_sql = "SELECT * FROM songs";
$songs_result = $conn->query($songs_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Welcome - SRSFY</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <div id="navbar">
    <h1>SRSFY</h1>
    <div>
      <a href="plans.html">Upgrade Plan</a>
      <a href="feedback.html">Feedback</a>
      <a href="logout.php">Logout</a>
    </div>
  </div>

  <div class="container" style="margin-top: 120px;">
    <h2>Hi, <?php echo htmlspecialchars($name); ?>! 👋</h2>
    <p>Your Current Plan: <strong><?php echo $plan['name']; ?></strong> – ₹<?php echo $plan['price']; ?></p>

    <h3>Songs Available:</h3>
    <div class="songs-grid">
      <?php while ($row = $songs_result->fetch_assoc()): ?>
        <div class="song-card">
          <h4><?php echo htmlspecialchars($row['title']); ?></h4>
          <p><?php echo htmlspecialchars($row['artist']); ?></p>
          <audio controls src="<?php echo htmlspecialchars($row['url']); ?>"type = "songs/mpeg"></audio>
        </div>
      <?php endwhile; ?>
    </div>
  </div>

</body>
</html>
