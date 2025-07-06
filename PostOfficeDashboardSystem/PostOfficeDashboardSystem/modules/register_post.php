<?php
require_once('../db/connection.php');
require_once('../modules/RegisteredPost.php');

$regPost = new RegisteredPost($conn);
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $postData = [
        'sender' => $_POST['sender'],
        'receiver' => $_POST['receiver'],
        'address' => $_POST['address']
    ];

    $tracking = $regPost->registerPost($postData);
    if ($tracking) {
        $successMessage = "Registered Post Created. Tracking Number: <strong>$tracking</strong>";
    } else {
        $successMessage = "Error while registering post.";
    }
}
?>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register Post</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: Arial, sans-serif;
      background-image: url('https://upload.wikimedia.org/wikipedia/commons/6/63/India_Post_Office.jpg');
      background-size: cover;
      background-position: center;
      height: 100vh;
      margin: 0;
    }

    .navbar {
      background-color: rgba(220, 20, 60, 0.95); /* India Post red with slight transparency */
    }

    .navbar-brand {
      font-weight: bold;
      color: white !important;
    }

    .form-container {
      background: rgba(255, 255, 255, 0.95);
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
      max-width: 500px;
      margin: 100px auto;
    }

    h2 {
      text-align: center;
      color: #b22222; /* Indian red */
      margin-bottom: 20px;
    }

    input, textarea {
      width: 100%;
      margin-bottom: 15px;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    button {
      width: 100%;
      padding: 10px;
      background-color: #b22222;
      border: none;
      border-radius: 5px;
      color: white;
      font-size: 16px;
      cursor: pointer;
    }

    button:hover {
      background-color: #8b1a1a;
    }

    .success-message {
      color: green;
      text-align: center;
      margin-top: 10px;
    }
  </style>
</head>
<body>

  <!-- ✅ Navbar -->
  <nav class="navbar navbar-expand-lg">
    <div class="container">
      <a class="navbar-brand" href="#"><i class="fas fa-envelope"></i> Register Post</a>
    </div>
  </nav>

  <!-- ✅ Form Section -->
  <div class="form-container">
    <h2>Register New Post</h2>
    <form method="post">
      <label>Sender Name:</label>
      <input type="text" name="sender" required>

      <label>Receiver Name:</label>
      <input type="text" name="receiver" required>

      <label>Receiver Address:</label>
      <textarea name="address" rows="4" required></textarea>

      <button type="submit">Register Post</button>

      <?php if (isset($successMessage) && $successMessage): ?>
        <p class="success-message"><?= $successMessage ?></p>
      <?php endif; ?>
    </form>
  </div>

</body>
</html>
