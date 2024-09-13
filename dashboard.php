<?php
session_start();
include 'db.php';

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch admin details from the database
$admin_id = $_SESSION['admin_id'];
$query = "SELECT * FROM admins WHERE id = '$admin_id'";
$result = mysqli_query($conn, $query);
$admin = mysqli_fetch_assoc($result);

// Handle logout
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - AttendEase</title>
    <?php include 'cdn.php'; ?>
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/dashboard.css">
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="dashboard">
        <h1>Welcome, <?php echo htmlspecialchars($admin['email']); ?>!</h1>
        <p>This is your admin dashboard.</p>
        
        <!-- Dashboard content goes here -->
        
        <form method="POST" action="">
            <button type="submit" name="logout">Logout</button>
        </form>
    </div>
</body>
</html>
