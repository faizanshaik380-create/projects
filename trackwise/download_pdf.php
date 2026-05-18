<?php
session_start();
include "config/db.php";

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized");
}

$user_id = $_SESSION['user_id'];

$result = mysqli_query($conn, "SELECT * FROM expenses WHERE user_id='$user_id'");

header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=expenses_report.html");

echo "<html><head><title>Expense Report</title></head><body>";

echo "<h2>TrackWise Expense Report</h2>";
echo "<table border='1' cellpadding='10' cellspacing='0'>";

echo "<tr>
<th>Title</th>
<th>Amount</th>
<th>Category</th>
<th>Date</th>
</tr>";

while($row = mysqli_fetch_assoc($result)){
    echo "<tr>
    <td>{$row['title']}</td>
    <td>{$row['amount']}</td>
    <td>{$row['category']}</td>
    <td>{$row['created_at']}</td>
    </tr>";
}

echo "</table>";
echo "</body></html>";
?>