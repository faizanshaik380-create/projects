<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}
include_once __DIR__ . "/config/db.php";

$user_id = $_SESSION['user_id'];
$expenses = mysqli_query($conn, "SELECT * FROM expenses WHERE user_id='$user_id' ORDER BY date DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Track Expenses | TrackWise</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #556B2F;
        }
        .sidebar {
            height: 100vh;
            background: #3b4f1b;
            padding-top: 20px;
        }
        .sidebar a {
            color: white;
            display: block;
            padding: 12px;
            text-decoration: none;
        }
        .sidebar a:hover {
            background: #6b8e23;
        }
        .navbar {
            background-color: #3b4f1b;
        }
    </style>
</head>

<body>

<nav class="navbar navbar-dark px-4">
    <span class="navbar-brand mb-0 h1">TrackWise</span>
    <a href="logout.php" class="btn btn-light btn-sm">Logout</a>
</nav>

<div class="container-fluid">
    <div class="row">

        <div class="col-md-2 sidebar text-white">
            <h4 class="text-center">Menu</h4>
            <a href="dashboard.php">Dashboard</a>
            <a href="add_expense.php">Add Expense</a>
            <a href="track_expenses.php">Track Expenses</a>
        </div>

        <div class="col-md-10 p-4">

            <h2 class="text-white mb-4">Your Expenses</h2>

            <div class="card shadow p-3">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Category</th>
                            <th>Amount</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($expenses)) { ?>
                            <tr>
                                <td><?php echo $row['date']; ?></td>
               <?php
session_start();
include "config/db.php";

$user_id = $_SESSION['user_id'];
$result = mysqli_query($conn, "SELECT * FROM expenses WHERE user_id='$user_id' ORDER BY expense_date DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Track Expenses</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background:#6b8e23; }
.table { background:white; border-radius:10px; }
</style>
</head>
<body>

<div class="container mt-5">
<h2 class="text-white mb-4">Your Expenses</h2>

<table class="table table-striped shadow">
<tr>
<th>Title</th>
<th>Amount</th>
<th>Category</th>
<th>Date</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)) { ?>
<tr>
<td><?php echo $row['title']; ?></td>
<td>₹ <?php echo $row['amount']; ?></td>
<td><?php echo $row['category']; ?></td>
<td><?php echo $row['expense_date']; ?></td>
</tr>
<?php } ?>

</table>

<a href="dashboard.php" class="btn btn-light">Back to Dashboard</a>

</div>

</body>
</html>                 <td><?php echo $row['category']; ?></td>
                                <td>₹ <?php echo $row['amount']; ?></td>
                                <td><?php echo $row['description']; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

</body>
</html>