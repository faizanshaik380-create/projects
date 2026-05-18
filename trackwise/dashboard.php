<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

include_once __DIR__ . "/config/db.php";

$user_id = $_SESSION['user_id'];

// FETCH USER DATA
$stmt = $conn->prepare("SELECT name, photo, salary FROM users WHERE id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

$name = $user['name'] ?? "User";
$photo = $user['photo'] ?? "";
$salary = $user['salary'] ?? 0;

// FETCH CURRENT MONTH EXPENSES
$currentMonth = date("Y-m");

$stmt = $conn->prepare("
    SELECT COALESCE(SUM(amount),0) as total
    FROM expenses
    WHERE user_id=?
    AND DATE_FORMAT(expense_date,'%Y-%m')=?
");
$stmt->bind_param("is", $user_id, $currentMonth);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

$monthlyExpense = $row['total'];
$warning = false;
$savingsLeft = 0;
$overSpent = 0;

if ($salary > 0) {
    $savingsLeft = $salary - $monthlyExpense;

    if ($monthlyExpense >= $salary) {
        $warning = true;
        $overSpent = abs($savingsLeft);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard | TrackWise</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
body{
background: linear-gradient(135deg,#1e3a5f,#4a90e2);
font-family:'Segoe UI';
color:white;
min-height:100vh;
}

.navbar{
background:#0f1f33;
}

.profile-img{
width:90px;
height:90px;
border-radius:50%;
object-fit:cover;
border:3px solid white;
}

.card-box{
background:white;
color:black;
border-radius:15px;
padding:20px;
text-align:center;
box-shadow:0 5px 15px rgba(0,0,0,0.2);
transition:0.3s;
height:100%;
}

.card-box:hover{
transform:translateY(-5px);
}

.icon{
font-size:30px;
margin-bottom:10px;
}

.hero-img{
width:220px;
margin-top:20px;
}
</style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-dark p-3">
<div class="container d-flex justify-content-between">
    <h4 class="text-white m-0">
        <i class="fa-solid fa-wallet"></i> TrackWise
    </h4>

    <a href="logout.php" class="btn btn-danger">
        <i class="fa-solid fa-right-from-bracket"></i> Logout
    </a>
</div>
</nav>

<!-- MAIN -->
<div class="container text-center mt-5">

<!-- PROFILE -->
<img src="<?php echo $photo ? 'uploads/'.$photo : 'https://cdn-icons-png.flaticon.com/512/149/149071.png'; ?>" 
class="profile-img mb-3">

<h2>Welcome Back, <?php echo htmlspecialchars($name); ?> 👋</h2>

<p>Track your expenses smarter & faster</p>

<!-- SALARY STATUS -->
<div class="alert alert-info mt-3">
    💵 Salary: <strong>₹<?php echo number_format($salary,2); ?></strong> |
    📅 This Month: <strong>₹<?php echo number_format($monthlyExpense,2); ?></strong> |
    💰 Savings Left: <strong>₹<?php echo number_format($savingsLeft,2); ?></strong>
</div>

<!-- WARNING -->
<?php if($warning){ ?>
<div class="alert alert-danger mt-3">
    🚨 <strong>Warning!</strong>  
    You exceeded your monthly salary by  
    <strong>₹<?php echo number_format($overSpent,2); ?></strong>
</div>
<?php } ?>

<!-- HERO IMAGE -->
<img src="https://cdn-icons-png.flaticon.com/512/3144/3144456.png" class="hero-img">

<!-- DASHBOARD CARDS -->
<div class="row mt-5 g-4">

<div class="col-md-3">
<div class="card-box">
<div class="icon text-success">➕</div>
<h5>Add Expense</h5>
<a href="add_expense.php" class="btn btn-success w-100">Open</a>
</div>
</div>

<div class="col-md-3">
<div class="card-box">
<div class="icon text-primary">📋</div>
<h5>View Expenses</h5>
<a href="view_expenses.php" class="btn btn-primary w-100">Open</a>
</div>
</div>

<div class="col-md-3">
<div class="card-box">
<div class="icon text-warning">📊</div>
<h5>Analytics</h5>
<a href="analytics.php" class="btn btn-warning w-100">Open</a>
</div>
</div>

<div class="col-md-3">
<div class="card-box">
<div class="icon text-dark">👤</div>
<h5>Profile</h5>
<a href="profile.php" class="btn btn-dark w-100">Open</a>
</div>
</div>

</div>

</div>

<!-- 🔔 DEVICE NOTIFICATION -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    let warning = <?php echo $warning ? 'true' : 'false'; ?>;
    let overSpent = <?php echo $overSpent; ?>;

    if (warning) {
        if (Notification.permission === "granted") {
            new Notification("🚨 TrackWise Alert", {
                body: "You exceeded your monthly salary by ₹" + overSpent,
                icon: "https://cdn-icons-png.flaticon.com/512/2331/2331970.png"
            });
        } 
        else if (Notification.permission !== "denied") {
            Notification.requestPermission().then(function(permission) {
                if (permission === "granted") {
                    new Notification("🚨 TrackWise Alert", {
                        body: "You exceeded your monthly salary by ₹" + overSpent,
                        icon: "https://cdn-icons-png.flaticon.com/512/2331/2331970.png"
                    });
                }
            });
        }
    }
});
</script>

</body>
</html>