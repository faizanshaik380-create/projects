<?php
session_start();
include_once __DIR__ . "/config/db.php";

// Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$expenses = [];
$total = 0;

// Fetch all expenses
$stmt = $conn->prepare("SELECT id, title, amount, category, expense_date FROM expenses WHERE user_id=? ORDER BY expense_date DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $expenses[] = $row;
    $total += $row['amount'];
}
$stmt->close();

// ✅ MONTHLY TOTAL CALCULATION
$monthlyTotals = [];

foreach ($expenses as $row) {
    $month = date("F Y", strtotime($row['expense_date']));
    if (!isset($monthlyTotals[$month])) {
        $monthlyTotals[$month] = 0;
    }
    $monthlyTotals[$month] += $row['amount'];
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Your Expenses | TrackWise</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
background:#556B2F;
font-family:Arial;
}

.card{
border-radius:15px;
}

.table thead{
background:#3d4f1c;
color:white;
}

.total-box{
font-size:20px;
font-weight:bold;
text-align:right;
}

.month-box{
background:#f8f9fa;
padding:15px;
border-radius:10px;
margin-bottom:10px;
display:flex;
justify-content:space-between;
font-weight:bold;
}
</style>

</head>
<body>

<div class="container py-5">

<div class="card shadow-lg p-4">

<!-- HEADER -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0">📊 Your Expenses</h3>

    <a href="download_pdf.php" class="btn btn-danger">
        Download Report
    </a>
</div>

<?php if(count($expenses)>0){ ?>

<!-- ✅ MONTHLY EXPENSES -->
<h5 class="mb-3">📅 Monthly Expenses</h5>

<?php foreach($monthlyTotals as $month => $amt){ ?>
<div class="month-box">
    <span><?php echo $month; ?></span>
    <span>₹ <?php echo number_format($amt,2); ?></span>
</div>
<?php } ?>

<hr>

<!-- TABLE -->
<table class="table table-bordered table-hover">

<thead>
<tr>
<th>#</th>
<th>Title</th>
<th>Amount</th>
<th>Category</th>
<th>Date</th>
<th>Action</th>
</tr>
</thead>

<tbody>

<?php $i=1; foreach($expenses as $row){ ?>

<tr>
<td><?php echo $i++; ?></td>
<td><?php echo htmlspecialchars($row['title']); ?></td>
<td>₹ <?php echo number_format($row['amount'],2); ?></td>
<td><?php echo htmlspecialchars($row['category']); ?></td>
<td><?php echo $row['expense_date']; ?></td>
<td>
    <a href="delete_expense.php?id=<?php echo $row['id']; ?>" 
       class="btn btn-danger btn-sm"
       onclick="return confirm('Delete this expense?')">
       Delete
    </a>
</td>
</tr>

<?php } ?>

</tbody>
</table>

<!-- TOTAL -->
<div class="total-box">
Total Spent: ₹ <?php echo number_format($total,2); ?>
</div>

<?php } else { ?>

<p>No expenses added yet.</p>

<?php } ?>

<a href="dashboard.php" class="btn btn-dark mt-3">← Back to Dashboard</a>

</div>

</div>

</body>
</html>