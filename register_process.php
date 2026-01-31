<?php
session_start();
require_once "./config/db.php";   // DB connection file

// 1️⃣ Check request method
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: register.php");
    exit;
}

// 2️⃣ Collect form data
$name           = trim($_POST['name']);
$email          = trim($_POST['email']);
$phone          = trim($_POST['phone']);
$address        = trim($_POST['address']);
$aadhaar_pan    = trim($_POST['aadhaar_pan']);
$dob            = $_POST['dob'];
$password       = $_POST['password'];
$confirm_pass   = $_POST['confirm_password'];

// 3️⃣ Basic validation
if (
    empty($name) || empty($email) || empty($phone) ||
    empty($address) || empty($aadhaar_pan) || empty($dob) ||
    empty($password) || empty($confirm_pass)
) {
    die("All fields are required.");
}

// 4️⃣ Password match check
if ($password !== $confirm_pass) {
    die("Passwords do not match.");
}

// 5️⃣ Check if email already exists
$checkEmail = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
mysqli_stmt_bind_param($checkEmail, "s", $email);
mysqli_stmt_execute($checkEmail);
mysqli_stmt_store_result($checkEmail);

if (mysqli_stmt_num_rows($checkEmail) > 0) {
    die("Email already registered.");
}
mysqli_stmt_close($checkEmail);

// 6️⃣ Hash password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// 7️⃣ Insert into users table (role assigned automatically)
$userQuery = mysqli_prepare(
    $conn,
    "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'client')"
);
mysqli_stmt_bind_param($userQuery, "sss", $name, $email, $hashed_password);

if (!mysqli_stmt_execute($userQuery)) {
    die("User registration failed.");
}

$user_id = mysqli_insert_id($conn);
mysqli_stmt_close($userQuery);

// 8️⃣ Insert into clients table
$clientQuery = mysqli_prepare(
    $conn,
    "INSERT INTO clients (user_id, phone, address, aadhaar_pan, dob)
     VALUES (?, ?, ?, ?, ?)"
);
mysqli_stmt_bind_param(
    $clientQuery,
    "issss",
    $user_id,
    $phone,
    $address,
    $aadhaar_pan,
    $dob
);

if (!mysqli_stmt_execute($clientQuery)) {
    die("Client profile creation failed.");
}

mysqli_stmt_close($clientQuery);

// 9️⃣ Registration successful → redirect to login
header("Location: login.php?success=1");
exit;
?>
