<?php
class ParcelPost {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function manageParcel($parcelData) {
        $trackingNumber = $this->generateTrackingNumber();
        $cost = $this->calculatePostage($parcelData['weight'], $parcelData['distance']);

        $stmt = $this->conn->prepare("INSERT INTO parcels (sender_name, weight, cost, tracking_number) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sdds", $parcelData['sender'], $parcelData['weight'], $cost, $trackingNumber);

        if ($stmt->execute()) {
            return ['tracking' => $trackingNumber, 'cost' => $cost];
        }

        return false;
    }

    private function calculatePostage($weight, $distance) {
        $ratePerKg = 30;    // ₹30 per kg
        $distanceCharge = $distance * 2; // ₹2 per km
        return ($weight * $ratePerKg) + $distanceCharge;
    }

    private function generateTrackingNumber() {
        return 'PP' . strtoupper(uniqid());
    }
}
?>

<?php
include('../db/connection.php');
include_once('../modules/ParcelPost.php');

$parcel = new ParcelPost($conn);
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $parcelData = [
        'sender' => $_POST['sender'],
        'weight' => floatval($_POST['weight']),
        'distance' => intval($_POST['distance'])
    ];

    $result = $parcel->manageParcel($parcelData);
    if ($result) {
        $message = "Parcel Registered. Tracking: <strong>{$result['tracking']}</strong><br>Cost: ₹<strong>{$result['cost']}</strong>";
    } else {
        $message = "Error registering parcel.";
    }
}
?>
<?php
// You can add PHP backend processing here to insert into database
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Parcel Post</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
        }
        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
            height: 100%;
            background-image: url('https://upload.wikimedia.org/wikipedia/commons/b/be/India-post-logo.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            backdrop-filter: blur(3px);
        }

        nav {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 15px 30px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav .logo {
            font-size: 20px;
            font-weight: bold;
        }

        nav .menu a {
            color: white;
            margin-left: 20px;
            text-decoration: none;
            font-weight: 500;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.92);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            width: 90%;
            margin: 50px auto;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
        }

        input, select {
            width: 100%;
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #aaa;
            border-radius: 5px;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #d62828;
            border: none;
            border-radius: 6px;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background-color: #a51212;
        }

        .success-message {
            text-align: center;
            color: green;
            margin-top: 10px;
        }
    </style>
</head>




<body>
    <nav>
        <div class="logo">📮 Indian Post Portal</div>
        <div class="menu">
            <a href="dashboard.php">🏠Home</a>
            <a href="TrackPost.php">🔍Track Post</a>
            <a href="DailyPostReport.php">📊Daily Report</a>
            <a href="register_post.php">📦Register Post</a>
        </div>
    </nav>

    <div class="form-container">
        <h2>Register Parcel Post</h2>
        <form method="post">
            <label>Sender Name:</label>
            <input type="text" name="sender" required>

            <label>Weight (kg):</label>
            <input type="number" step="0.1" name="weight" required>

            <label>Distance (km):</label>
            <input type="number" name="distance" required>

            <label>Parcel Type:</label>
            <select name="parcel_type" required>
                <option value="">Select Type</option>
                <option value="Speed Post">Speed Post</option>
                <option value="Registered Parcel">Registered Parcel</option>
                <option value="Express Parcel">Express Parcel</option>
                <option value="Business Parcel">Business Parcel</option>
            </select>

            <button type="submit">Process Post</button>
        </form>
    </div>
</body>
</html>
