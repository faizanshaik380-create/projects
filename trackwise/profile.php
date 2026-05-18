<?php
session_start();
include_once __DIR__ . "/config/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$msg = "";

// FETCH USER DATA
$stmt = $conn->prepare("SELECT name, dob, photo, salary FROM users WHERE id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

$name = $user['name'] ?? "";
$dob = $user['dob'] ?? "";
$photo = $user['photo'] ?? "";
$salary = $user['salary'] ?? 0;

// UPDATE PROFILE
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['name'];
    $dob = $_POST['dob'];
    $salary = $_POST['salary'];

    $newPhoto = $photo;

    if (!empty($_FILES['photo']['name'])) {
        if (!is_dir("uploads")) {
            mkdir("uploads");
        }

        $newPhoto = time() . "_" . $_FILES['photo']['name'];
        move_uploaded_file($_FILES['photo']['tmp_name'], "uploads/" . $newPhoto);
    }

    $stmt = $conn->prepare("UPDATE users SET name=?, dob=?, photo=?, salary=? WHERE id=?");
    $stmt->bind_param("sssdi", $name, $dob, $newPhoto, $salary, $user_id);
    $stmt->execute();
    $stmt->close();

    $photo = $newPhoto;
    $msg = "✅ Profile updated successfully!";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Profile | TrackWise</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
background:#f4f6f9;
font-family:'Segoe UI';
}
.card{
max-width:500px;
margin:auto;
border-radius:15px;
}
.profile-img{
width:120px;
height:120px;
border-radius:50%;
object-fit:cover;
}
</style>
</head>
<body>

<div class="container py-5">
<div class="card shadow-lg p-4">

<h3 class="text-center mb-4">👤 Update Profile</h3>

<?php if($msg){ ?>
<div class="alert alert-success"><?php echo $msg; ?></div>
<?php } ?>

<div class="text-center">
<img src="<?php echo $photo ? 'uploads/'.$photo : 'https://cdn-icons-png.flaticon.com/512/149/149071.png'; ?>" class="profile-img mb-3">
</div>

<form method="POST" enctype="multipart/form-data">

<label>Name</label>
<input type="text" name="name" class="form-control mb-3" value="<?php echo htmlspecialchars($name); ?>" required>

<label>Date of Birth</label>
<input type="date" name="dob" class="form-control mb-3" value="<?php echo $dob; ?>">

<label>Monthly Salary</label>
<input type="number" step="0.01" name="salary" class="form-control mb-3" value="<?php echo $salary; ?>" required>

<label>Upload Photo</label>
<input type="file" name="photo" class="form-control mb-3">

<button class="btn btn-primary w-100">Save Profile</button>
</form>

<a href="dashboard.php" class="btn btn-dark w-100 mt-3">← Back Dashboard</a>

</div>
</div>

</body>
</html>