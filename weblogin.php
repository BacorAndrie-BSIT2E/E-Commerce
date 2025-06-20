<?php
session_start();

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "ecommerce_db";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = $success = ""; // Initialize error and success messages

// Handle login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if (!empty($username) && !empty($password)) {
        $sql = "SELECT * FROM users WHERE username=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Verify hashed password
            if (password_verify($password, $row['password'])) {
                // Set session variables
                $_SESSION["user_id"] = $row['id'];
                $_SESSION["username"] = $row['username'];
                $_SESSION["role"] = $row['role'];

                // Redirect based on role
                if ($row['role'] == 'admin') {
                    header("Location: index.php"); // Redirect admin to index.php
                } else {
                    header("Location: User.php"); // Redirect user to User.php
                }
                exit();
            } else {
                $error = "Invalid username or password!";
            }
        } else {
            $error = "Invalid username or password!";
        }
    } else {
        $error = "Please fill in both fields!";
    }
}

// Handle registration
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $confirm_password = isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : '';

    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters!";
    } else {
        // Hash the password before storing it
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (username, password, role) VALUES (?, ?, 'customer')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $hashed_password);

        if ($stmt->execute()) {
            $success = "Account created successfully! You can now log in.";
        } else {
            $error = "Failed to create account. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Coffee Login Page</title>
  <style>
      body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: url("coffee-background.jpg") center/cover no-repeat;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      animation: fadeIn 0.5s ease-in-out;
    }

    .container {
      display: flex;
      width: 900px;
      height: 500px;
      background-color: rgba(0, 0, 0, 0.7);
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 0 30px rgba(0,0,0,0.8);
      transition: all 0.5s ease-in-out;
    }

    .panel {
      flex: 1;
      padding: 10px;
      color: white;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      transition: all 0.5s ease-in-out;
      min-width: 0;
    }

    .left-panel {
      background-color: rgba(20, 20, 20, 0.85);
      text-align: center;
      transition: background 0.5s;
    }

    .right-panel {
      background-color: rgba(60, 30, 10, 0.8);
      text-align: center;
      transition: background 0.5s;
    }

    .right-panel h2, .left-panel h2 {
      font-size: 25px;
      margin-bottom: 20px;
      color: #fffacd;
    }

    .right-panel p, .left-panel .move-message {
      font-size: 16px;
      margin-bottom: 30px;
      text-align: center;
    }

    input {
      margin-bottom: 15px;
      padding: 12px;
      font-size: 16px;
      border: none;
      border-radius: 5px;
      width: 100%;
      box-sizing: border-box;
    }

    .login-box, .register-box {
      opacity: 0;
      max-height: 0;
      overflow: hidden;
      transition: opacity 0.5s, max-height 0.5s cubic-bezier(.4,0,.2,1);
      display: flex;
      flex-direction: column;
      align-items: center;
      width: 100%;
    }
    .login-box.show, .register-box.show {
      opacity: 1;
      max-height: 500px;
      margin-top: 10px;
    }

    .button, .toggle-button, .back-button {
      padding: 10px 20px;
      background-color: #fffacd;
      color: black;
      font-weight: bold;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      margin-top: 10px;
      transition: background 0.3s;
      display: block;
      margin-left: auto;
      margin-right: auto;
    }

    .button:hover, .toggle-button:hover, .back-button:hover {
      background-color: #ffee99;
    }

    .guest-button {
      padding: 10px 20px;
      background-color: #32CD32; /* Green color for Guest button */
      color: white;
      font-weight: bold;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      margin-top: 10px;
      transition: background 0.3s;
      display: block;
      margin-left: auto;
      margin-right: auto;
    }

    .guest-button:hover {
      background-color: #28a428; /* Darker green on hover */
    }

    .shadow-box {
      background-color: rgba(0,0,0,0.5);
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.6);
      width: 100%;
      max-width: 350px;
      margin-left: auto;
      margin-right: auto;
    }

    .slide-in {
      animation: fade 0.5s ease-in-out;
    }

    @keyframes fade {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .hide {
      display: none !important;
    }
    .move-message {
      animation: fade 0.5s;
    }
    .center-message {
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      height: 100%;
      width: 100%;
    }
  </style>
</head>
<body>
  <div class="container" id="container">
    <div class="panel left-panel">
      <div class="shadow-box fade-in" id="leftBox">
        <h2 id="leftTitle">Sign In</h2>
        <form id="loginBox" class="login-box show" method="POST" action="">
          <?php if (!empty($error)): ?>
            <p style="color: red; font-size: 14px;"><?php echo $error; ?></p>
          <?php endif; ?>
          <input type="text" name="username" placeholder="Username" required />
          <input type="password" name="password" placeholder="Password" required />
          <a href="#" style="color:white; font-size:14px; margin-bottom:10px;">Forgot password?</a>
          <button class="button" type="submit" name="login">Login</button>
          <!-- Log In as Guest Button -->
          <button class="guest-button" type="button" onclick="location.href='web.php'">Log In as Guest</button>
        </form>
        <div id="signupMessage" class="move-message" style="display:none;">
          Want to try something special?<br>Enter your personal details and start your journey with our coffee.
        </div>
      </div>
    </div>

    <div class="panel right-panel fade-in">
      <h2 id="rightTitle">Hello Coffee Person!</h2>
      <p id="rightMessage">Want to try something special?<br>Enter your personal details and start your journey with our coffee.</p>
      <button class="toggle-button" id="createAccountBtn">Create an Account</button>

      <form method="POST" action="" id="registerBox" class="register-box shadow-box" style="margin-top:20px;">
        <h2>Register</h2>
        <?php if (!empty($error)): ?>
          <p style="color: red; font-size: 14px;"><?php echo $error; ?></p>
        <?php elseif (!empty($success)): ?>
          <p style="color: lightgreen; font-size: 14px;"><?php echo $success; ?></p>
        <?php endif; ?>
        <input type="text" name="username" placeholder="Username" required />
        <input type="password" name="password" placeholder="Password" required />
        <input type="password" name="confirm_password" placeholder="Confirm Password" required />
        <button type="button" class="button" id="backToLoginBtn2">Back to Log In</button>
        <button type="submit" class="button" name="register" style="margin-top:5px;">Register</button>
      </form>
    </div>
  </div>

  <script>
    const loginBox = document.getElementById('loginBox');
    const registerBox = document.getElementById('registerBox');
    const rightTitle = document.getElementById('rightTitle');
    const rightMessage = document.getElementById('rightMessage');
    const createAccountBtn = document.getElementById('createAccountBtn');
    const signupMsg = document.getElementById('signupMessage');
    const leftTitle = document.getElementById('leftTitle');

    registerBox.classList.remove('show');
    signupMsg.style.display = 'none';
    loginBox.classList.add('show');

    createAccountBtn.onclick = function () {
      loginBox.classList.remove('show');
      setTimeout(() => {
        rightTitle.classList.add('hide');
        rightMessage.classList.add('hide');
        createAccountBtn.classList.add('hide');
      }, 500);
      registerBox.classList.add('slide-in');
      registerBox.classList.add('show');
      signupMsg.style.display = 'flex';
      signupMsg.classList.add('center-message');
      leftTitle.classList.add('hide');
    };

    document.getElementById('backToLoginBtn2').onclick = function () {
      rightTitle.classList.remove('hide');
      rightMessage.classList.remove('hide');
      createAccountBtn.classList.remove('hide');
      rightTitle.classList.remove('fade-out');
      rightMessage.classList.remove('fade-out');
      rightTitle.classList.add('fade-in');
      rightMessage.classList.add('fade-in');
      registerBox.classList.remove('show');
      loginBox.classList.add('show');
      signupMsg.style.display = 'none';
      signupMsg.classList.remove('center-message');
      leftTitle.classList.remove('hide');
    };
  </script>

</body>
</html>