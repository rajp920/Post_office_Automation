<?php
class MoneyOrder {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function processMoneyOrder($orderData) {
        $receiptNumber = $this->generateReceiptNumber();
        $stmt = $this->conn->prepare("INSERT INTO money_orders (sender_name, recipient_name, amount) VALUES (?, ?, ?)");
        $stmt->bind_param("ssd", $orderData['sender'], $orderData['recipient'], $orderData['amount']);
        

        if ($stmt->execute()) {
            return $receiptNumber;
        }

        return false;
    }

    private function generateReceiptNumber() {
        return 'MO' . strtoupper(uniqid());
    }
}
?>

<?php
include('../db/connection.php');
include_once('../modules/MoneyOrder.php');

$moneyOrder = new MoneyOrder($conn);
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $orderData = [
        'sender' => $_POST['sender'],
        'recipient' => $_POST['recipient'],
        'amount' => floatval($_POST['amount'])
    ];

    $receipt = $moneyOrder->processMoneyOrder($orderData);
    if ($receipt) {
        $message = "Money Order Processed. Receipt Number: <strong>$receipt</strong>";
    } else {
        $message = "Error processing money order.";
    }
}
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Process Money Order</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: Arial, sans-serif;
      background-image: url('https://upload.wikimedia.org/wikipedia/commons/b/be/India-post-logo.jpg');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      background-attachment: fixed;
      height: 100vh;
      margin: 0;
    }

    .navbar {
      background-color: rgba(220, 20, 60, 0.95);
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
      color: #b22222;
      margin-bottom: 20px;
    }

    input {
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
      <a class="navbar-brand" href="#"><i class="fas fa-money-bill-wave"></i> Money Order</a>
    </div>
  </nav>

  <!-- ✅ Form Section -->
  <div class="form-container">
    <h2>Process Money Order</h2>
    <form method="post">
      <label>Sender Name:</label>
      <input type="text" name="sender" required>

      <label>Recipient Name:</label>
      <input type="text" name="recipient" required>

      <label>Amount (₹):</label>
      <input type="number" name="amount" step="0.01" required>

      <button type="submit">Send Money</button>

      <?php if (isset($message) && $message): ?>
        <p class="success-message"><?= $message ?></p>
      <?php endif; ?>
    </form>
  </div>

</body>
</html>
