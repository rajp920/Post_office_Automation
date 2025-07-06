<?php
class SpeedPost {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function processSpeedPost($postData) {
        $trackingNumber = $this->generateTrackingNumber();

        $stmt = $this->conn->prepare("INSERT INTO speed_posts (sender_name, receiver_name, address, tracking_number, status) VALUES (?, ?, ?, ?, ?)");
        $status = "Processing";
        $stmt->bind_param("sssss", $postData['sender'], $postData['receiver'], $postData['address'], $trackingNumber, $status);

        if ($stmt->execute()) {
            return $trackingNumber;
        }

        return false;
    }

    private function generateTrackingNumber() {
        return 'SP' . strtoupper(uniqid());
    }
}
?>

<?php
include('../db/connection.php');
include_once('../modules/SpeedPost.php');

$speedPost = new SpeedPost($conn);
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $postData = [
        'sender' => $_POST['sender'],
        'receiver' => $_POST['receiver'],
        'address' => $_POST['address']
    ];

    $tracking = $speedPost->processSpeedPost($postData);
    if ($tracking) {
        $message = "Speed Post Created. Tracking Number: <strong>$tracking</strong>";
    } else {
        $message = "Error while processing speed post.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Process Speed Post</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('https://upload.wikimedia.org/wikipedia/commons/b/be/India-post-logo.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .form-container {
            background: rgba(255, 255, 255, 0.8); /* White background with transparency */
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        h2 {
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
        }
        input, textarea {
            width: 100%;
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 18px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .success-message {
            color: green;
            font-weight: bold;
            margin-top: 15px;
        }
        /* Navbar Styling */
        .navbar {
            background-color: #d32f2f; /* Indian Post Office Red */
            padding: 15px;
            color: #fff;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 10;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
        }
        .navbar-nav .nav-link {
            color: #fff !important;
            font-size: 1.1rem;
        }
    </style>
</head>
<body>

<!-- Navbar Start -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">🇮🇳 Indian Post Office</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="register_post.php"><i class="fas fa-envelope me-1"></i> Register Post</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="TrackPost.php"><i class="fas fa-map-marker-alt me-1"></i> Track Post</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="InquiryModule.php"><i class="fas fa-question-circle me-1"></i> Inquiry</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- Navbar End -->

<div class="form-container">
    <h2>Process Speed Post</h2>
    <form method="post">
        <label for="sender">Sender Name:</label>
        <input type="text" id="sender" name="sender" required>

        <label for="receiver">Receiver Name:</label>
        <input type="text" id="receiver" name="receiver" required>

        <label for="address">Receiver Address:</label>
        <textarea id="address" name="address" rows="4" required></textarea>

        <button type="submit">Process Post</button>
        <?php if ($message): ?>
            <p class="success-message"><?= $message ?></p>
        <?php endif; ?>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
