<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// User info from session
$username = $_SESSION['username'];
$email = $_SESSION['email'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container my-5">
  <h2 class="text-center mb-4">👤 Profile Details</h2>
  <div class="card mx-auto" style="max-width: 500px;">
    <div class="card-body">
      <h5 class="card-title">Username: <?php echo $username; ?></h5>
      <p class="card-text">Email: <?php echo $email; ?></p>
    </div>
  </div>
</div>

</body>
</html>
