<?php
session_start();
include 'db.php';

// Check user_id and redirect if not logged in
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header("Location: weblogin.php");
    exit;
}

$username = $email = $phone = $address = "";

if ($user_id) {
    $stmt = $conn->prepare("SELECT username, email, phone, address FROM users WHERE id=?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($username, $email, $phone, $address);
    $stmt->fetch();
    $stmt->close();
}

$success = $error = $pass_success = $pass_error = "";

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['profile_update']) && $user_id) {
    $new_email = trim($_POST['email']);
    $new_phone = trim($_POST['phone']);
    $new_address = trim($_POST['address']);

    $stmt = $conn->prepare("UPDATE users SET email=?, phone=?, address=? WHERE id=?");
    $stmt->bind_param("sssi", $new_email, $new_phone, $new_address, $user_id);
    if ($stmt->execute()) {
        $success = "Profile updated successfully!";
        $email = $new_email;
        $phone = $new_phone;
        $address = $new_address;
    } else {
        $error = "Failed to update profile: " . $stmt->error;
    }
    $stmt->close();
}

// Handle password change (optional)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password_update']) && $user_id) {
    $old_pass = $_POST['old_password'];
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];

    $stmt = $conn->prepare("SELECT password FROM users WHERE id=?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($db_pass);
    $stmt->fetch();
    $stmt->close();

    if (!password_verify($old_pass, $db_pass)) {
        $pass_error = "Current password is incorrect.";
    } elseif ($new_pass !== $confirm_pass) {
        $pass_error = "New passwords do not match.";
    } elseif (strlen($new_pass) < 6) {
        $pass_error = "New password must be at least 6 characters.";
    } else {
        $new_hash = password_hash($new_pass, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
        $stmt->bind_param("si", $new_hash, $user_id);
        if ($stmt->execute()) {
            $pass_success = "Password changed successfully!";
        } else {
            $pass_error = "Failed to update password. Please try again.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Beans Appetite</title>
    <style>
    .hero-message {
        position: absolute;
        top: 35%;
        left: 5%;
        transform: translateY(-50%);
        text-align: left;
        max-width: 600px;
    }

    .text-box {
        background: rgba(0, 0, 0, 0.6); /* transparent dark box */
        padding: 15px 20px;
        margin-bottom: 15px;
        border-radius: 10px;
    }

    .wake-up {
        font-size: 3em;
        color: #FFD700;
        font-weight: bold;
        margin: 0;
        line-height: 1.2;
    }

    .sip-note {
        font-size: 1.4em;
        color: #F5F5DC;
        margin: 0;
    }

    .savor-note {
        font-size: 1.2em;
        color: #F5F5DC;
        font-style: italic;
        margin: 0;
    }


    .hero-message .savor-note {
        font-size: 1.6em;
        color: #F5F5DC;
        font-style: italic;
        box-shadow: 0 4px 6px rgba(0,0,0,0.2);
    }


    .hero-message .savor-note {
        font-size: 20px;
        color:rgb(245, 243, 243);
        font-style: italic;
    }
    body {
      min-height: 100vh;
      margin: 0;
      background: url('bg.jpg.png') no-repeat center center fixed;
      background-size: cover;
      font-family: 'Segoe UI', Arial, sans-serif;
    }
    .navbar-fixed {
      width: 100%;
      position: fixed;
      top: 0; left: 0;
      z-index: 1002;
      background: rgba(44, 26, 23, 0.98);
      box-shadow: 0 0 6px 0 rgba(30,30,50,0.16);
      height: 68px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 10px;
    }
    .navbar-logo-group {
      display: flex;
      align-items: center;
      margin-left: 30px;
    }
    .nav-logo-img {
      font-size: 2em;
      margin-right: 10px;
    }
    .navbar-logo {
      font-size: 2em;
      font-weight: bold;
      letter-spacing: 1px;
      color: #7B3F00;
      user-select: none;
      display: inline-flex;
      align-items: center;
      margin-left: auto;
    }
    .navbar-links {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-left: 0;
    }
    .navbar-link {
      color: #fff;
      background: none;
      border: none;
      font-size: 1.13em;
      border-radius: 8px;
      padding: 7px 20px;
      margin: 0 3px;
      font-weight: 500;
      text-decoration: none;
      transition: background .15s, color .15s;
      cursor: pointer;
      outline: 0;
    }
    .navbar-link.active,
    .navbar-link:hover {
      color: #fff;
      background: #7B3F00;
    }
    .profile-dropdown {
      position: relative;
      display: flex;
      align-items: center;
    }
    .profile-btn {
      background: #F5F5DC;
      color: #7B3F00;
      border: none;
      padding: 7px 24px 7px 16px;
      border-radius: 99px;
      font-size: 1.08em;
      font-weight: bold;
      cursor: pointer;
      margin-left: 15px;
      display: flex;
      align-items: center;
      gap: 7px;
      box-shadow: 0 1px 4px #7B3F0068;
      transition: background 0.18s, color 0.18s;
    }
    .profile-btn:hover,
    .profile-btn.open {
      background: #7B3F00;
      color: #fff;
    }
    .profile-btn .emoji {
      font-size: 1.28em;
      vertical-align: middle;
      margin-right: 2px;
    }
    .profile-dropdown-content {
      display: none;
      position: absolute;
      right: 0;
      top: 120%;
      background: #fff;
      min-width: 100px;
      box-shadow: 0 8px 24px 0 #19444226;
      border-radius: 12px;
      padding: 4px 0;
      z-index: 20;
      animation: fadeIn 0.19s;
      text-align: left;
    }
    .profile-dropdown:hover .profile-dropdown-content,
    .profile-dropdown:focus-within .profile-dropdown-content,
    .profile-dropdown .profile-dropdown-content.show {
      display: block;
    }
    .profile-dropdown-content a {
      color: #7B3F00;
      padding: 12px 25px;
      text-decoration: none;
      display: block;
      font-weight: 600;
      font-size: 1em;
      border-radius: 9px;
      transition: background 0.2s, color 0.2s;
      cursor: pointer;
    }
    .profile-dropdown-content a:hover {
      background: #7B3F00;
      color: #fff;
    }
    .profile-modal-bg {
      display: none;
      position: fixed;
      z-index: 1050;
      left: 0; top: 0; width: 100vw; height: 100vh;
      background: rgba(44, 62, 80, 0.49);
      justify-content: center;
      align-items: center;
    }
    .profile-modal-bg.active { display: flex; }
    .profile-modal-box {
      background: rgba(75, 46, 43, 0.95);
      backdrop-filter: blur(7px);
      padding: 0;
      border-radius: 22px;
      box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.18);
      width: 100%;
      max-width: 460px;
      color: #23272b;
      position: relative;
      animation: fadeIn 0.3s;
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    @keyframes fadeIn {
      from { opacity:.07; transform:translateY(-36px); }
      to { opacity:1; transform:translateY(0); }
    }
    .close-modal {
      position: absolute;
      top: 18px;
      right: 22px;
      font-size: 2em;
      color: #7B3F00;
      cursor: pointer;
      font-weight: bold;
      z-index: 2;
      background: none;
      border: 0;
      line-height: 1;
    }
    .profile-modal-content {
      width: 100%;
      padding: 38px 32px 32px 32px;
      box-sizing: border-box;
      background: none;
      border-radius: 0 0 18px 18px;
      min-height: 320px;
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    .profile-box-form, .password-form {
      width: 100%;
      max-width: 340px;
      color: #fff;
    }
    .profile-box-form h2, .password-form h2 {
      margin: 0 0 18px 0;
      color: #7B3F00;
      font-weight: 900;
      font-size: 2em;
      letter-spacing: 1px;
    }
    .profile-box-form label, .password-form label {
      display: block;
      margin: 16px 0 6px 0;
      color: #7B3F00;
      font-weight: bold;
      font-size: 1.06em;
      text-align: left;
    }
    .profile-box-form input,
    .profile-box-form textarea,
    .password-form input {
      width: 100%;
      padding: 12px 16px;
      border-radius: 10px;
      border: 2px solid #7B3F00;
      background: #D7CCC8;
      color: #2C1A17;
      margin-bottom: 10px;
      font-size: 1.1em;
      outline: none;
      transition: border 0.2s;
      resize: none;
    }
    .profile-box-form input:focus,
    .password-form input:focus,
    .profile-box-form textarea:focus {
      border: 2px solid #4B2E2B;
      background: #F5F5DC;
    }
    .profile-box-form button,
    .password-form button,
    .logout-content button {
      width: 100%;
      padding: 16px 0;
      margin-top: 18px;
      border-radius: 12px;
      font-size: 1.14em;
      font-weight: 700;
      border: none;
      cursor: pointer;
      background: linear-gradient(90deg, #7B3F00 65%, #4B2E2B 100%);
      box-shadow: 0 3px 16px #2227;
      color: #fff;
      transition: background 0.18s, color 0.18s;
    }
    .profile-box-form button:hover,
    .password-form button:hover,
    .logout-content button:hover {
      background: linear-gradient(90deg, #4B2E2B 65%, #7B3F00 100%);
      color: #fff;
    }
    .logout-content {
      padding:42px 8px;
      color: #fff;
      text-align: center;
    }
    .logout-content h2 {
      font-size: 2em;
      font-weight: bold;
      letter-spacing: 1px;
      margin:10px 0;
    }
    .logout-content p {
      margin-bottom:22px;
    }
    .msg-success {
      background: #7B3F00;
      color: #fff;
      padding: 11px 0;
      border-radius: 8px;
      margin-bottom: 15px;
      font-weight: bold;
      letter-spacing: .3px;
      font-size: 1.1em;
      text-align: center;
    }
    .msg-error {
      background: #b22222;
      color: #fff;
      padding: 11px 0;
      border-radius: 8px;
      margin-bottom: 15px;
      font-weight: bold;
      letter-spacing: .3px;
      font-size: 1.1em;
      text-align: center;
    }
    @media (max-width: 700px) {
      .navbar-fixed { flex-direction: column; height: auto; padding: 6px; }
      .navbar-logo-group { margin-bottom: 6px; }
      .navbar-logo { font-size: 1.3em; }
      .navbar-links { gap: 0; margin-left: 0; }
      .profile-btn { font-size: 1em; padding:7px 10px; }
      .profile-dropdown-content { min-width: 112px; }
    }
    @media(max-width: 530px) {
      .navbar-link {
        font-size: 1em;
        padding: 6px 6px;
        margin:2px 1px;
      }
      .profile-btn {
        font-size: .97em;
      }
    }
    @media (max-width:500px) {
      .profile-modal-box,.profile-modal-content {
        padding: 8px 3vw 10px 3vw;
        max-width: 99vw;
      }
      .profile-dropdown-content {
        right: 0;
      }
    }
    section {margin-top: 85px;}
  </style>

</head>
<body>
  <!-- --- TOP FIXED NAVIGATION BAR --- -->
  <header class="navbar-fixed">
    <div class="navbar-logo-group">
        <span class="nav-logo-img">☕</span>
        <span class="navbar-logo">Beans Appetite</span>
    </div>
    <nav class="navbar-links">
        <a href="user.php" class="navbar-link active">Home</a>
        <a href="product_page.php" class="navbar-link">Products</a>
        <a href="aboutus.php" class="navbar-link">About Us</a>
        <div class="profile-dropdown" id="profileDropdown">
            <button type="button" id="profileBtn" class="profile-btn">
                <span class="emoji">👤</span> Profile
            </button>
            <div class="profile-dropdown-content" id="profileDropdownContent">
                <a tabindex="1" onclick="openProfileModal('profile')">Edit Profile</a>
                <a tabindex="1" onclick="openProfileModal('settings')">Change Password</a>
                <a tabindex="1" onclick="openProfileModal('logout')">Logout</a>
            </div>
        </div>
    </nav>
</header>
<div class="hero-message">
    <div class="text-box">
        <h1 class="wake-up">WAKE UP & SMELL<br>THE COFFEE!</h1>
    </div>
    <div class="text-box">
        <p class="sip-note">Sit back, sip, and enjoy the perfect blend of flavors in every cup.</p>
    </div>
    <div class="text-box">
        <p class="savor-note">Savor the Perfect Brew, One Click at a Time.</p>
    </div>
</div>


  <!-- ----- PROFILE MODAL, COPIES PH OLD HTML ----- -->
  <div id="profileModal" class="profile-modal-bg">
    <div class="profile-modal-box">
      <button type="button" class="close-modal" onclick="closeProfileModal()">&times;</button>

      <!-- TAB -->
      <div class="profile-modal-content" id="profileTabContent" style="display:none;">
        <form method="post" class="profile-box-form">
          <input type="hidden" name="profile_update" value="1">
          <h2>Edit Profile</h2>
          <?php if (!empty($success)): ?>
            <div class="msg-success"><?= $success ?></div>
          <?php endif; ?>
          <?php if (!empty($error)): ?>
            <div class="msg-error"><?= $error ?></div>
          <?php endif; ?>
          <label>Username</label>
          <input type="text" value="<?= htmlspecialchars($username) ?>" disabled>
          <label for="email">Email</label>
          <input type="email" id="email" name="email" maxlength="80" value="<?= htmlspecialchars($email) ?>" placeholder="Enter your email">
          <label for="phone">Phone Number</label>
          <input type="text" id="phone" name="phone" maxlength="20" value="<?= htmlspecialchars($phone) ?>" placeholder="Enter your phone number">
          <label for="address">Address</label>
          <textarea id="address" name="address" rows="3" maxlength="255" placeholder="Enter your address"><?= htmlspecialchars($address) ?></textarea>
          <button type="submit">Save Changes</button>
        </form>
      </div>

      <!-- Password tab -->
      <div class="profile-modal-content" id="settingsTabContent" style="display:none;">
        <form method="post" class="password-form">
          <input type="hidden" name="password_update" value="1">
          <h2>Change Password</h2>
          <?php if (!empty($pass_success)): ?>
            <div class="msg-success"><?= $pass_success ?></div>
          <?php endif; ?>
          <?php if (!empty($pass_error)): ?>
            <div class="msg-error"><?= $pass_error ?></div>
          <?php endif; ?>
          <label for="old_password">Current Password</label>
          <input type="password" id="old_password" name="old_password" required placeholder="Enter current password">
          <label for="new_password">New Password</label>
          <input type="password" id="new_password" name="new_password" required placeholder="Enter new password">
          <label for="confirm_password">Confirm New Password</label>
          <input type="password" id="confirm_password" name="confirm_password" required placeholder="Confirm new password">
          <button type="submit">Update Password</button>
        </form>
      </div>

      <!-- Logout tab -->
      <div class="profile-modal-content" id="logoutTabContent" style="display:none;">
        <div class="logout-content">
          <h2>Logout</h2>
          <p>Are you sure you want to logout?</p>
          <form method="post" action="logout.php">
            <button type="submit">Logout</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Main Content spacing -->
  <section id="home" class="hero"></section>
  <section id="products" class="coffee-lovers"></section>
  <section id="services" class="services"></section>

  <script>
    // Dropdown for Profile
    const profileDropdown = document.getElementById('profileDropdown');
    const profileBtn = document.getElementById('profileBtn');
    const profileDropdownContent = document.getElementById('profileDropdownContent');
    profileBtn.addEventListener('click', function(event) {
      event.stopPropagation();
      profileDropdownContent.classList.toggle('show');
      profileBtn.classList.toggle('open');
    });
    document.body.addEventListener('click', function() {
      profileDropdownContent.classList.remove('show');
      profileBtn.classList.remove('open');
    });

    // Modal actions
    const profileModal = document.getElementById('profileModal');
    const profileTabContent = document.getElementById('profileTabContent');
    const settingsTabContent = document.getElementById('settingsTabContent');
    const logoutTabContent = document.getElementById('logoutTabContent');

    function openProfileModal(tab) {
      document.body.classList.add('modal-open');
      profileModal.classList.add('active');
      profileTabContent.style.display = 'none';
      settingsTabContent.style.display = 'none';
      logoutTabContent.style.display = 'none';
      if (tab === 'profile') {
        profileTabContent.style.display = 'flex';
      } else if (tab === 'settings') {
        settingsTabContent.style.display = 'flex';
      } else if (tab === 'logout') {
        logoutTabContent.style.display = 'flex';
      }
      profileDropdownContent.classList.remove('show');
      profileBtn.classList.remove('open');
    }
    function closeProfileModal() {
      document.body.classList.remove('modal-open');
      profileModal.classList.remove('active');
      profileTabContent.style.display = 'none';
      settingsTabContent.style.display = 'none';
      logoutTabContent.style.display = 'none';
    }
    window.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') { closeProfileModal(); }
    });
    profileModal.onclick = function(event) {
      if(event.target === profileModal) { closeProfileModal(); }
    }
  </script>
</body>
</html>