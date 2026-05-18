<?php
session_start();
include_once __DIR__ . "/config/db.php";

// Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

// Check if ID exists
if (isset($_GET['id'])) {

    $id = intval($_GET['id']); // safe integer
    $user_id = $_SESSION['user_id'];

    // Delete only user's expense
    $stmt = $conn->prepare("DELETE FROM expenses WHERE id=? AND user_id=?");
    $stmt->bind_param("ii", $id, $user_id);

    if($stmt->execute()){
        // success
    } else {
        echo "Error deleting record";
    }

    $stmt->close();
}

// Redirect back
header("Location: view_expenses.php");
exit();
?>