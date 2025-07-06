<?php
class ECard {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function sendECard($cardData) {
        $stmt = $this->conn->prepare("INSERT INTO ecards (sender_name, recipient_email, message, template) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $cardData['sender'], $cardData['email'], $cardData['message'], $cardData['template']);
        return $stmt->execute();
    }

    public function getTemplates() {
        // Just static template names for now
        return ["Birthday", "Festival", "Congratulations", "Thank You"];
    }
}
?>

<?php
include('../db/connection.php');
include_once('../modules/ECard.php');

$ecard = new ECard($conn);
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $cardData = [
        'sender' => $_POST['sender'],
        'email' => $_POST['email'],
        'message' => $_POST['message'],
        'template' => $_POST['template']
    ];

    if ($ecard->sendECard($cardData)) {
        $message = "E-Card sent successfully to <strong>{$cardData['email']}</strong>";
    } else {
        $message = "Failed to send E-Card.";
    }
}

$templates = $ecard->getTemplates();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send E-Card</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('https://upload.wikimedia.org/wikipedia/commons/b/be/India-post-logo.jpg'); /* Background image */
            background-size: cover;
            background-position: center;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
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
            padding: 8px 16px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .navbar-nav .nav-link:hover {
            background-color: #c62828;
        }

        /* Form Container Styling */
        .form-container {
            background: rgba(255, 255, 255, 0.9); /* White background with transparency */
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            max-width: 500px;
            width: 100%;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        input, textarea, select {
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

        /* Responsive Navbar Styling */
        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1.2rem;
            }
            .navbar-nav .nav-link {
                font-size: 1rem;
                padding: 10px;
            }
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
                    <a class="nav-link" href="modules/register_post.php"><i class="fas fa-envelope me-1"></i> Register Post</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="modules/TrackPost.php"><i class="fas fa-map-marker-alt me-1"></i> Track Post</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="modules/InquiryModule.php"><i class="fas fa-question-circle me-1"></i> Inquiry</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- Navbar End -->

<div class="form-container">
    <h2>Send a Digital E-Card</h2>
    <form method="post">
        <label for="sender">Your Name:</label>
        <input type="text" id="sender" name="sender" required>

        <label for="email">Recipient Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="message">Message:</label>
        <textarea id="message" name="message" rows="4" required></textarea>

        <label for="template">Template:</label>
        <select id="template" name="template" required>
            <?php foreach ($templates as $template): ?>
                <option value="<?= $template ?>"><?= $template ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Send E-Card</button>
        <?php if ($message): ?>
            <p class="success-message"><?= $message ?></p>
        <?php endif; ?>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
