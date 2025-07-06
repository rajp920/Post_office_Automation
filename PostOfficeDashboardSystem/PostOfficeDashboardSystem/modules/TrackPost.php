<?php
// Database connection
include('../db/connection.php');

// TrackPost class
class TrackPost {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function trackPost($trackingNumber) {
        $stmt = $this->conn->prepare("SELECT * FROM tracking_details WHERE tracking_number = ?");
        $stmt->bind_param("s", $trackingNumber);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getTrackingHistory($trackingNumber) {
        $stmt = $this->conn->prepare("SELECT * FROM tracking_history WHERE tracking_number = ? ORDER BY updated_at ASC");
        $stmt->bind_param("s", $trackingNumber);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}

// Instantiate the tracker
$tracker = new TrackPost($conn);
$statusInfo = null;
$trackingHistory = [];
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $trackingNumber = $_POST['tracking'];
    $statusInfo = $tracker->trackPost($trackingNumber);

    if ($statusInfo) {
        $trackingHistory = $tracker->getTrackingHistory($trackingNumber);
    } else {
        $message = "No status found for Tracking Number <strong>$trackingNumber</strong>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Your Post</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('https://upload.wikimedia.org/wikipedia/commons/b/be/India-post-logo.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .form-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
            max-width: 600px;
            width: 100%;
        }
        h2 {
            text-align: center;
            color: #b22222;
        }
        input[type="text"] {
            width: 100%;
            margin-bottom: 15px;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #b22222;
            border: none;
            border-radius: 6px;
            color: white;
            font-size: 18px;
            cursor: pointer;
        }
        button:hover {
            background-color: #8b0000;
        }
        .result, .error-message {
            margin-top: 20px;
            text-align: center;
        }
        .result p {
            margin: 5px 0;
            font-size: 16px;
        }
        .error-message {
            color: red;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #999;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        @media (max-width: 600px) {
            .form-container {
                padding: 20px;
            }
            input[type="text"], button {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Track Your Post</h2>
        <form method="post">
            <label for="tracking">Enter Tracking Number:</label>
            <input type="text" id="tracking" name="tracking" required>
            <button type="submit">Track</button>
        </form>

        <?php if ($statusInfo): ?>
            <div class="result">
                <p><strong>Status:</strong> <?= htmlspecialchars($statusInfo['status']) ?></p>
                <p><strong>Last Updated:</strong> <?= htmlspecialchars($statusInfo['updated_at']) ?></p>
            </div>

            <?php if (!empty($trackingHistory)): ?>
                <div class="result">
                    <h3>Tracking History</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Location</th>
                                <th>Status</th>
                                <th>Date & Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($trackingHistory as $row): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['location']) ?></td>
                                    <td><?= htmlspecialchars($row['status']) ?></td>
                                    <td><?= htmlspecialchars($row['updated_at']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        <?php elseif ($message): ?>
            <p class="error-message"><?= $message ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
