<?php
session_start();
include 'db.php';

$error_message = ''; // Initialize variable for error message

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $query = "SELECT * FROM admins WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $admin = mysqli_fetch_assoc($result);

        // Verify password
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['email'] = $admin['email'];
            header("Location: dashboard.php"); // Redirect to admin dashboard
            exit;
        } else {
            $error_message = "Invalid password!";
        }
    } else {
        $error_message = "Admin not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - AttendEase</title>
    <?php include 'cdn.php'?>
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/signup.css">
</head>
<body>
<?php include 'header.php'?>
<div class="signup_all">
    <form method="POST" action="">
        <div class="forms_title">
            <h2>Admin Login</h2>
        </div>

        <!-- Display the error message -->
        <?php if ($error_message): ?>
            <div class="error_message" style="color: red; text-align: center; margin-bottom: 15px;">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <div class="forms">
            <label for="email">Email:</label>
            <input type="email" name="email" required>
        </div>

        <div class="forms">
            <label for="password">Password:</label>
            <input type="password" name="password" required>
        </div>

        <div class="forms">
            <button type="submit">Login</button>
        </div>
    </form>
</div>
</body>
</html>
