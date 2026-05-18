<?php
session_start();
include_once __DIR__ . "/config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

$msg = "";
$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = $_POST['title'];
    $amount = $_POST['amount'];
    $category = $_POST['category'];
    $expense_date = $_POST['expense_date']; // ✅ NEW

    // ✅ UPDATED QUERY
    $stmt = $conn->prepare("INSERT INTO expenses (user_id, title, amount, category, expense_date) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isdss", $user_id, $title, $amount, $category, $expense_date);
    $stmt->execute();
    $stmt->close();

    $msg = "Expense Added Successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Expense | TrackWise</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
background: linear-gradient(135deg,#556B2F,#8FBC8F);
font-family: 'Segoe UI';
}

.card{
border-radius:15px;
}

.btn-custom{
background:#2f4f1f;
color:white;
}

.btn-custom:hover{
background:#1e2f13;
}

</style>

</head>
<body>

<div class="container vh-100 d-flex justify-content-center align-items-center">

<div class="card shadow-lg p-4" style="width:450px">

<h3 class="text-center fw-bold">Add Expense</h3>
<p class="text-center text-muted">Track your spending easily</p>

<?php if($msg){ ?>
<div class="alert alert-success"><?php echo $msg; ?></div>
<?php } ?>

<form method="POST">

<div class="mb-3">
<label class="form-label">Title</label>
<input type="text" name="title" class="form-control" required>
</div>

<div class="mb-3">
<label class="form-label">Amount</label>
<input type="number" step="0.01" name="amount" class="form-control" required>
</div>

<div class="mb-3">
<label class="form-label">Category</label>
<select name="category" class="form-select">

<option>Food</option>
<option>Transport</option>
<option>Shopping</option>
<option>Bills</option>
<option>Entertainment</option>
<option>Other</option>

</select>
</div>

<!-- ✅ NEW DATE FIELD -->
<div class="mb-3">
<label class="form-label">Date</label>
<input type="date" name="expense_date" class="form-control" required>
</div>

<button class="btn btn-custom w-100">Add Expense</button>

</form>

<div class="text-center mt-3">

<a href="view_expenses.php" class="btn btn-outline-dark w-100 mb-2">
View Expenses
</a>

<a href="dashboard.php" class="btn btn-secondary w-100">
Back Dashboard
</a>

</div>

</div>
</div>

</body>
</html>