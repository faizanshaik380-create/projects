<?php
session_start();
include_once __DIR__ . "/config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$categoryTotals = [];
$monthlyTotals = [];

// FETCH DATA
$stmt = $conn->prepare("SELECT amount, category, expense_date FROM expenses WHERE user_id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {

    // CATEGORY TOTAL (Pie)
    $cat = $row['category'];
    $categoryTotals[$cat] = ($categoryTotals[$cat] ?? 0) + $row['amount'];

    // MONTH TOTAL (Bar Graph)
    $month = date("M Y", strtotime($row['expense_date']));
    $monthlyTotals[$month] = ($monthlyTotals[$month] ?? 0) + $row['amount'];
}

$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
<title>Analytics | TrackWise</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
background:#f4f6f9;
font-family:Arial;
}

.card{
border-radius:15px;
}

.chart-box{
max-width:500px;
margin:auto;
}
</style>

</head>
<body>

<div class="container py-5">

<div class="card p-4 shadow">

<h3 class="text-center mb-4">📊 Expense Analytics</h3>

<a href="dashboard.php" class="btn btn-dark mb-3">← Back</a>

<!-- PIE CHART -->
<h5 class="text-center">Category Distribution (%)</h5>

<div class="chart-box">
<canvas id="pieChart"></canvas>
</div>

<hr>

<!-- BAR GRAPH -->
<h5 class="text-center mt-4">Monthly Expense Graph</h5>

<div class="chart-box">
<canvas id="barChart"></canvas>
</div>

</div>

</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Percentage Plugin -->
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

<script>

// DATA FROM PHP
let pieLabels = <?php echo json_encode(array_keys($categoryTotals)); ?>;
let pieData = <?php echo json_encode(array_values($categoryTotals)); ?>;

let barLabels = <?php echo json_encode(array_keys($monthlyTotals)); ?>;
let barData = <?php echo json_encode(array_values($monthlyTotals)); ?>;

// PIE CHART
let total = pieData.reduce((a,b)=>a+b,0);

new Chart(document.getElementById('pieChart'), {
    type: 'pie',
    data: {
        labels: pieLabels,
        datasets: [{
            data: pieData,
            backgroundColor: [
                '#ff6384','#36a2eb','#ffcd56',
                '#4bc0c0','#9966ff','#ff9f40'
            ]
        }]
    },
    options: {
        plugins: {
            legend: { position: 'bottom' },
            datalabels: {
                color: '#fff',
                font: { weight: 'bold' },
                formatter: (value) => {
                    return (value/total*100).toFixed(1) + "%";
                }
            }
        }
    },
    plugins: [ChartDataLabels]
});

// BAR CHART
new Chart(document.getElementById('barChart'), {
    type: 'bar',
    data: {
        labels: barLabels,
        datasets: [{
            label: 'Monthly Expense (₹)',
            data: barData
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

</script>

</body>
</html>