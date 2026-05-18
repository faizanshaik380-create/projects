<?php
session_start();
include "config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$result = mysqli_query($conn, "SELECT * FROM expenses WHERE user_id='$user_id' ORDER BY expense_date DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Track Expenses</title>
</head>
<body>

<h2>Your Expenses</h2>

<table border="1" cellpadding="10">
<tr>
    <th>Title</th>
    <th>Amount</th>
    <th>Category</th>
    <th>Date</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)) { ?>
<tr>
    <td><?php echo $row['title']; ?></td>
    <td><?php echo $row['amount']; ?></td>
    <td><?php echo $row['category']; ?></td>
    <td><?php echo $row['expense_date']; ?></td>
</tr>
<?php } ?>

</table>

<a href="dashboard.php">Back to Dashboard</a>

</body>
</html>