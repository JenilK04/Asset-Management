<?php
// ================================
// Login Backend (Core PHP)
// ================================

session_start();
require_once "config/db.php";   // Database connection

// 1️⃣ Check request method
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: login.php");
    exit;
}

// 2️⃣ Collect form data
$email    = trim($_POST['email']);
$password = $_POST['password'];

// 3️⃣ Basic validation
if (empty($email) || empty($password)) {
    header("Location: login.php?error=1");
    exit;
}

// 4️⃣ Fetch user by email
$query = mysqli_prepare($conn, "SELECT id, name, password, role FROM users WHERE email = ?");
mysqli_stmt_bind_param($query, "s", $email);
mysqli_stmt_execute($query);
$result = mysqli_stmt_get_result($query);

// 5️⃣ Check if user exists
if ($user = mysqli_fetch_assoc($result)) {

    // 6️⃣ Verify password
    if (password_verify($password, $user['password'])) {

        // 7️⃣ Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name']    = $user['name'];
        $_SESSION['role']    = $user['role'];

        // 8️⃣ Redirect based on role
        if ($user['role'] === 'admin') {
            header("Location: admin/dashboard.php");
        } else {
            header("Location: client/dashboard.php");
        }
        exit;

    } else {
        // Wrong password
        header("Location: login.php?error=1");
        exit;
    }

} else {
    // User not found
    header("Location: login.php?error=1");
    exit;
}
?>
