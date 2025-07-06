<?php
// --- DB Connection ---
$servername = "localhost";
$username = "root";
$password = "";
$database = "post_office"; // Change this to your DB name

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// --- Class for Report ---
class DailyPostReport {
    private $conn;
    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function generateReport() {
        $today = date('Y-m-d');
        $stmt = $this->conn->prepare("SELECT COUNT(*) AS total_posts, SUM(postage_fee) AS total_revenue FROM DailyReport WHERE DATE(created_at) = ?");
        $stmt->bind_param("s", $today);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function detailedInsights() {
        $stmt = $this->conn->prepare("SELECT status, COUNT(*) AS count FROM DailyReport GROUP BY status");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}

// --- AJAX Response for Live Fetch ---
if (isset($_GET['ajax']) && $_GET['ajax'] === '1') {
    $report = new DailyPostReport($conn);
    $dailyReport = $report->generateReport();
    $insights = $report->detailedInsights();
    echo json_encode([
        'total_posts' => $dailyReport['total_posts'],
        'total_revenue' => $dailyReport['total_revenue'],
        'insights' => $insights
    ]);
    exit;
}

// --- Normal Page Load ---
$report = new DailyPostReport($conn);
$dailyReport = $report->generateReport();
$insights = $report->detailedInsights();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daily Post Report</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('https://upload.wikimedia.org/wikipedia/commons/b/be/India-post-logo.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            margin: 0;
            padding: 0;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }
        .container {
            background: rgba(255,255,255,0.9);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
            max-width: 800px;
            width: 95%;
            overflow-x: auto;
        }
        h2, h3 {
            text-align: center;
        }
        .summary {
            text-align: center;
            font-size: 1.2em;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Daily Post Report</h2>
    <div class="summary">
        <p><strong>Total Posts Today:</strong> <span id="total-posts"><?= $dailyReport['total_posts'] ?></span></p>
        <p><strong>Total Revenue Today:</strong> ₹<span id="total-revenue"><?= number_format($dailyReport['total_revenue'], 2) ?></span></p>
    </div>
    <div class="insights">
        <h3>Detailed Insights</h3>
        <table>
            <thead>
                <tr><th>Status</th><th>Count</th></tr>
            </thead>
            <tbody id="insights-table-body">
                <?php foreach ($insights as $insight): ?>
                    <tr>
                        <td><?= $insight['status'] ?></td>
                        <td><?= $insight['count'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Live Fetch Script -->
<script>
    function fetchLiveData() {
        fetch('DailyPostReport.php?ajax=1')
            .then(res => res.json())
            .then(data => {
                document.getElementById('total-posts').innerText = data.total_posts;
                document.getElementById('total-revenue').innerText = data.total_revenue.toFixed(2);

                const tableBody = document.getElementById('insights-table-body');
                tableBody.innerHTML = '';
                data.insights.forEach(insight => {
                    const row = document.createElement('tr');
                    row.innerHTML = `<td>${insight.status}</td><td>${insight.count}</td>`;
                    tableBody.appendChild(row);
                });
            })
            .catch(err => console.error('Fetch error:', err));
    }

    setInterval(fetchLiveData, 5000); // Refresh every 5 seconds
    window.onload = fetchLiveData;
</script>
</body>
</html>
