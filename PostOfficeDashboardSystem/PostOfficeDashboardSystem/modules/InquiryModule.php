<?php
class InquiryModule {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function submitInquiry($inquiryData) {
        $stmt = $this->conn->prepare("INSERT INTO inquiries (name, tracking_number, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $inquiryData['name'], $inquiryData['tracking'], $inquiryData['message']);

        return $stmt->execute();
    }

    public function getInquiryStatus($trackingNumber) {
        $stmt = $this->conn->prepare("SELECT status FROM tracking_details WHERE tracking_number = ?");
        $stmt->bind_param("s", $trackingNumber);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }
}
?>

<?php
include('../db/connection.php');
include_once('../modules/InquiryModule.php');

$inquiry = new InquiryModule($conn);
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = [
        'name' => $_POST['name'],
        'tracking' => $_POST['tracking'],
        'message' => $_POST['message']
    ];

    if ($inquiry->submitInquiry($data)) {
        $status = $inquiry->getInquiryStatus($data['tracking']);
        if ($status) {
            $message = "Inquiry submitted successfully! Current Status: <strong>{$status['status']}</strong>";
        } else {
            $message = "Inquiry submitted. No status found for tracking number.";
        }
    } else {
        $message = "Error submitting inquiry.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Inquiry</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-image: url('https://upload.wikimedia.org/wikipedia/commons/b/be/India-post-logo.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background-color: #c8102e;
            color: white;
            padding: 10px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
        }

        .navbar .logo {
            display: flex;
            align-items: center;
            font-size: 20px;
            font-weight: bold;
            gap: 10px;
        }

        .navbar .logo img {
            height: 40px;
            border-radius: 4px;
        }

        .navbar .nav-links {
            list-style: none;
            display: flex;
            gap: 25px;
        }

        .navbar .nav-links li a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            font-size: 16px;
            transition: color 0.3s ease;
        }

        .navbar .nav-links li a:hover {
            color: #ffd700;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.92);
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            width: 100%;
            margin: auto;
            margin-top: 50px;
        }

        h2 {
            text-align: center;
            color: #c8102e;
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
            background-color: #c8102e;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #a10b24;
        }

        .success-message {
            color: green;
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <img src="https://upload.wikimedia.org/wikipedia/commons/b/be/India-post-logo.jpg" alt="India Post">
            <span>India Post Portal</span>
        </div>
        <ul class="nav-links">
            <li><a href="dashboard.php">🏠 Home</a></li>
            <li><a href="#">📦 Register</a></li>
            <li><a href="#">🔍 Track</a></li>
            <li><a href="#">📊 Report</a></li>
            <li><a href="#">📞 Contact</a></li>
        </ul>
    </nav>

    <div class="form-container">
        <h2>Submit an Inquiry</h2>
        <form method="post">
            <label>Your Name:</label>
            <input type="text" name="name" required>

            <label>Tracking Number:</label>
            <input type="text" name="tracking" required>

            <label>Your Message:</label>
            <textarea name="message" rows="4" required></textarea>

            <button type="submit">Submit Inquiry</button>
            <?php if ($message): ?>
                <p class="success-message"><?= $message ?></p>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
