<?php
require_once "../config/db.php";

if(isset($_POST['category_id'])) {

    $category_id = $_POST['category_id'];

    $types = mysqli_query($conn,
        "SELECT * FROM asset_types WHERE category_id = $category_id");

    while($row = mysqli_fetch_assoc($types)) {
        echo "<option value='{$row['id']}'>{$row['name']}</option>";
    }
}
?>