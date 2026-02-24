<?php
session_start();
require_once "../config/db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

/* ================================
   GET CLIENT ID
================================ */
$clientQuery = mysqli_query($conn, "SELECT id FROM clients WHERE user_id = $user_id");
$client = mysqli_fetch_assoc($clientQuery);
$client_id = $client['id'];

// Total Investment
$investmentQuery = mysqli_query($conn, 
    "SELECT SUM(purchase_value) AS total_investment 
     FROM client_assets 
     WHERE client_id = $client_id"
);
$investmentData = mysqli_fetch_assoc($investmentQuery);
$total_investment = $investmentData['total_investment'] ?? 0;

// Total Net Worth
$netQuery = mysqli_query($conn, 
    "SELECT SUM(current_value) AS total_net 
     FROM client_assets 
     WHERE client_id = $client_id"
);
$netData = mysqli_fetch_assoc($netQuery);
$total_net = $netData['total_net'] ?? 0;

// Profit / Loss
$profit_loss = $total_net - $total_investment;

// Total Assets
$countQuery = mysqli_query($conn, 
    "SELECT COUNT(*) AS total_assets 
     FROM client_assets 
     WHERE client_id = $client_id"
);
$countData = mysqli_fetch_assoc($countQuery);
$total_assets = $countData['total_assets'];

?>

<!DOCTYPE html>
<html>
<head>
    <title>Client Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f6f9;
        }
        .card {
            border-radius: 12px;
        }
        .navbar {
            border-radius: 0 0 12px 12px;
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary px-4">
    <span class="navbar-brand">Asset Management System</span>
    <div class="ms-auto d-flex align-items-center">
        <span class="text-white me-3">
            Welcome, <?php echo $_SESSION['name']; ?>
        </span>
        <a href="../logout.php" class="btn btn-light btn-sm">Logout</a>
    </div>
</nav>

<div class="container mt-4">

    <h4 class="mb-4">Portfolio Overview</h4>

    <!-- SUMMARY CARDS -->
    <div class="row">

        <div class="col-md-3 mb-3">
            <div class="card shadow-sm p-3">
                <h6>Total Investment</h6>
                <h4 class="text-primary">
                    ₹ <?php echo number_format($total_investment, 2); ?>
                </h4>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card shadow-sm p-3">
                <h6>Total Net Worth</h6>
                <h4 class="text-success">
                    ₹ <?php echo number_format($total_net, 2); ?>
                </h4>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card shadow-sm p-3">
                <h6>Profit / Loss</h6>
                <h4 class="<?php echo ($profit_loss >= 0) ? 'text-success' : 'text-danger'; ?>">
                    ₹ <?php echo number_format($profit_loss, 2); ?>
                </h4>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card shadow-sm p-3">
                <h6>Total Assets</h6>
                <h4 class="text-dark">
                    <?php echo $total_assets; ?>
                </h4>
            </div>
        </div>

    </div>

    <!-- RECENT ASSETS TABLE -->
    <div class="card mt-4 shadow-sm">
        <div class="card-header bg-light">
            Recent Assets
        </div>
        <div class="card-body">

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Asset Name</th>
                        <th>Purchase Value</th>
                        <th>Current Value</th>
                        <th>Purchase Date</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $assetsQuery = mysqli_query($conn, 
                    "SELECT asset_name, purchase_value, current_value, purchase_date 
                     FROM client_assets 
                     WHERE client_id = $client_id 
                     ORDER BY id DESC LIMIT 5"
                );

                if (mysqli_num_rows($assetsQuery) > 0) {
                    while ($row = mysqli_fetch_assoc($assetsQuery)) {
                        echo "<tr>
                                <td>{$row['asset_name']}</td>
                                <td>₹ " . number_format($row['purchase_value'], 2) . "</td>
                                <td>₹ " . number_format($row['current_value'], 2) . "</td>
                                <td>{$row['purchase_date']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' class='text-center'>No assets added yet.</td></tr>";
                }
                ?>
                </tbody>
            </table>

        </div>
    </div>

</div>

</body>
</html>
