<?php
class RegisterModule {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function register($username, $password) {
        // Hash the password for security
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Prepare and execute the SQL statement to insert the new user
        $stmt = $this->conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashedPassword);
        
        if ($stmt->execute()) {
            return true; // Registration successful
        } else {
            return false; // Registration failed
        }
    }
}
?>
<?php
include('../db/connection.php');
include_once('../modules/RegisterModule.php'); // Assuming you have a RegisterModule for handling registration logic

$register = new RegisterModule($conn);
$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirm_password"];

    // Basic validation
    if ($password !== $confirmPassword) {
        $error = "Passwords do not match.";
    } else {
        // Attempt to register the user
        if ($register->register($username, $password)) {
            $success = "Registration successful! You can now log in.";
            // Optionally redirect to login page after a short delay
            header("refresh:2;url=login.php");
            exit;
        } else {
            $error = "Registration failed. Username may already be taken.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - India Post</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('https://upload.wikimedia.org/wikipedia/commons/6/63/India_Post_Office.jpg');
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .logo {
            margin-bottom: 15px;
        }
        .logo img {
            max-width: 180px;
        }
        .register-container {
            background: rgba(255, 255, 255, 0.93);
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
            width: 320px;
        }
        h2 {
            text-align: center;
            color: #b71c0c;
            margin-bottom: 20px;
        }
        input[type="text"], input[type="password"], select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #d9230f;
            border: none;
            border-radius: 4px;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #b71c0c;
        }
        .error {
            color: red;
            text-align: center;
        }
        .success {
            color: green;
            text-align: center;
        }
        .login-link {
            text-align: center;
            margin-top: 10px;
        }
        .login-link a {
            color: #007bff;
            text-decoration: none;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="logo">
        <img src="https://upload.wikimedia.org/wikipedia/commons/b/be/India-post-logo.jpg" alt="India Post Logo">
    </div>
    <div class="register-container">
        <h2>Register</h2>
        <form method="post">
            <input type="text" name="Email" placeholder="Email ID" required>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <select name="role" required>
                <option value="">Select Role</option>
                <option value="admin">Admin</option>
                <option value="customer">Customer</option>
            </select>
            <button type="submit">Register</button>
            <?php if (isset($error) && $error): ?>
                <p class="error"><?= $error ?></p>
            <?php endif; ?>
            <?php if (isset($success) && $success): ?>
                <p class="success"><?= $success ?></p>
            <?php endif; ?>
        </form>
        <div class="login-link">
            <p>Already have an account? <a href="LoginModule.php">Login here</a></p>
        </div>
    </div>
</body>
</html>
