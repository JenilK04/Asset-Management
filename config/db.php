<?php
$conn = mysqli_connect("localhost", "root", "", "asset_management");

if (!$conn) {
    die("Database connection failed");
}
echo "DB Connected";
?>
