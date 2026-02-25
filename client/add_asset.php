<?php
session_start();
require_once "../config/db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

/* ============================
   GET CLIENT ID
============================ */
$clientQuery = mysqli_query($conn, "SELECT id FROM clients WHERE user_id = $user_id");
$client = mysqli_fetch_assoc($clientQuery);
$client_id = $client['id'];

/* ============================
   INSERT ASSET
============================ */
if (isset($_POST['add_asset'])) {

    $asset_type_id = $_POST['asset_type_id'];
    $asset_name = mysqli_real_escape_string($conn, $_POST['asset_name']);
    $purchase_value = $_POST['purchase_value'];
    $current_value = $_POST['current_value'];
    $purchase_date = $_POST['purchase_date'];
    $income_model = $_POST['income_model'];

    if (!empty($asset_type_id) && !empty($asset_name)) {

        $insert = mysqli_query($conn, "INSERT INTO client_assets
            (client_id, asset_type_id, asset_name, purchase_value, current_value, purchase_date)
            VALUES
            ($client_id, $asset_type_id, '$asset_name', $purchase_value, $current_value, '$purchase_date')");

        if ($insert) {

            $asset_id = mysqli_insert_id($conn);

            // Save income model
            mysqli_query($conn, "INSERT INTO asset_metadata 
                (client_asset_id, meta_key, meta_value)
                VALUES ($asset_id, 'income_model', '$income_model')");

            // Interest Model
            if(isset($_POST['interest_rate'])) {
                mysqli_query($conn, "INSERT INTO asset_metadata 
                    (client_asset_id, meta_key, meta_value)
                    VALUES ($asset_id, 'interest_rate', '{$_POST['interest_rate']}')");
            }

            if(isset($_POST['maturity_date'])) {
                mysqli_query($conn, "INSERT INTO asset_metadata 
                    (client_asset_id, meta_key, meta_value)
                    VALUES ($asset_id, 'maturity_date', '{$_POST['maturity_date']}')");
            }

            // Rental Model
            if(isset($_POST['monthly_rent'])) {
                mysqli_query($conn, "INSERT INTO asset_metadata 
                    (client_asset_id, meta_key, meta_value)
                    VALUES ($asset_id, 'monthly_rent', '{$_POST['monthly_rent']}')");
            }

            if(isset($_POST['maintenance_cost'])) {
                mysqli_query($conn, "INSERT INTO asset_metadata 
                    (client_asset_id, meta_key, meta_value)
                    VALUES ($asset_id, 'maintenance_cost', '{$_POST['maintenance_cost']}')");
            }

            // Dividend Model
            if(isset($_POST['quantity'])) {
                mysqli_query($conn, "INSERT INTO asset_metadata 
                    (client_asset_id, meta_key, meta_value)
                    VALUES ($asset_id, 'quantity', '{$_POST['quantity']}')");
            }

            if(isset($_POST['dividend_per_unit'])) {
                mysqli_query($conn, "INSERT INTO asset_metadata 
                    (client_asset_id, meta_key, meta_value)
                    VALUES ($asset_id, 'dividend_per_unit', '{$_POST['dividend_per_unit']}')");
            }

            // Business Model
            if(isset($_POST['profit_share'])) {
                mysqli_query($conn, "INSERT INTO asset_metadata 
                    (client_asset_id, meta_key, meta_value)
                    VALUES ($asset_id, 'profit_share', '{$_POST['profit_share']}')");
            }

            header("Location: dashboard.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Asset</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">

    <div class="card shadow p-4">
        <h4 class="mb-4">➕ Add New Asset</h4>

        <form method="POST">

            <!-- CATEGORY -->
            <div class="mb-3">
                <label>Asset Category</label>
                <select name="category_id" id="categorySelect" class="form-control" required>
                    <option value="">Select Category</option>
                    <?php
                    $categories = mysqli_query($conn, "SELECT * FROM asset_categories");
                    while($cat = mysqli_fetch_assoc($categories)) {
                        echo "<option value='{$cat['id']}'>{$cat['name']}</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- TYPE -->
            <div class="mb-3">
                <label>Asset Type</label>
                <select name="asset_type_id" id="typeSelect" class="form-control" required>
                    <option value="">Select Type</option>
                </select>
            </div>

            <!-- COMMON FIELDS -->
            <div class="mb-3">
                <label>Asset Name</label>
                <input type="text" name="asset_name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Purchase Value</label>
                <input type="number" step="0.01" name="purchase_value" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Current Value</label>
                <input type="number" step="0.01" name="current_value" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Purchase Date</label>
                <input type="date" name="purchase_date" class="form-control" required>
            </div>

            <!-- INCOME MODEL -->
            <div class="mb-3">
                <label>Income Model</label>
                <select name="income_model" id="incomeModel" class="form-control" required>
                    <option value="">Select Income Model</option>
                    <option value="capital">Capital Appreciation</option>
                    <option value="interest">Fixed Interest</option>
                    <option value="rental">Rental Income</option>
                    <option value="dividend">Dividend Income</option>
                    <option value="business">Business Profit</option>
                </select>
            </div>

            <!-- DYNAMIC FIELDS -->
            <div id="extraFields"></div>

            <button type="submit" name="add_asset" class="btn btn-primary">Add Asset</button>

        </form>
    </div>

</div>

<!-- AJAX + DYNAMIC SCRIPT -->
<script>

// Load types dynamically
document.getElementById("categorySelect").addEventListener("change", function() {

    let categoryId = this.value;

    fetch("get_types.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "category_id=" + categoryId
    })
    .then(res => res.text())
    .then(data => {
        document.getElementById("typeSelect").innerHTML =
            "<option value=''>Select Type</option>" + data;
    });
});

// Income Model Dynamic Fields
document.getElementById("incomeModel").addEventListener("change", function() {

    let model = this.value;
    let container = document.getElementById("extraFields");
    container.innerHTML = "";

    if(model === "interest") {
        container.innerHTML = `
            <div class="mb-3">
                <label>Interest Rate (%)</label>
                <input type="number" step="0.01" name="interest_rate" class="form-control">
            </div>
            <div class="mb-3">
                <label>Maturity Date</label>
                <input type="date" name="maturity_date" class="form-control">
            </div>
        `;
    }

    if(model === "rental") {
        container.innerHTML = `
            <div class="mb-3">
                <label>Monthly Rent</label>
                <input type="number" name="monthly_rent" class="form-control">
            </div>
            <div class="mb-3">
                <label>Annual Maintenance</label>
                <input type="number" name="maintenance_cost" class="form-control">
            </div>
        `;
    }

    if(model === "dividend") {
        container.innerHTML = `
            <div class="mb-3">
                <label>Quantity</label>
                <input type="number" name="quantity" class="form-control">
            </div>
            <div class="mb-3">
                <label>Dividend Per Unit</label>
                <input type="number" step="0.01" name="dividend_per_unit" class="form-control">
            </div>
        `;
    }

    if(model === "business") {
        container.innerHTML = `
            <div class="mb-3">
                <label>Profit Share (%)</label>
                <input type="number" step="0.01" name="profit_share" class="form-control">
            </div>
        `;
    }

});
</script>

</body>
</html>