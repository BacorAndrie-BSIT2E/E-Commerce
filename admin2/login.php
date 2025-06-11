<?php
session_start(); 

$conn = new mysqli("localhost", "root", "", "ecommerce_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = ""; 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

       
        $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            
            $_SESSION["username"] = $row['username'];
            $_SESSION["role"] = $row['role'];

            if ($row['role'] == 'admin') {
                header("Location: admin2.php");
                exit();
            } else {
                header("Location: userdashboard.php");
                exit();
            }
        } else {
            $error = "Invalid username or password!";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dummy Login Form</title>
</head>
<body>
    <h2>Login Form</h2>
    <form method="POST" action="login.php">
        <label>Username:</label>
        <input type="text" name="username" required><br><br>
        <label>Password:</label>
        <input type="password" name="password" required><br><br>
        <button type="submit" name="login">Login</button>
        <div class="register-link">
      <p>Don't have an account? <a href="register.php">Register</a></p>
    </div>
    </form>
</body>
</html>