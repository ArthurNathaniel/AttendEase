<?php
include 'db.php';

$error_message = ''; // Variable to store error message
$success_message = ''; // Variable to store success message

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hashing the password

    // Check if email already exists
    $checkQuery = "SELECT * FROM admins WHERE email = '$email'";
    $result = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($result) > 0) {
        $error_message = "Email already registered!";
    } else {
        $query = "INSERT INTO admins (email, password) VALUES ('$email', '$hashed_password')";
        if (mysqli_query($conn, $query)) {
            $success_message = "Admin registered successfully!";
            header("Location: login.php"); // Redirect to login.php after successful registration
            exit;
        } else {
            $error_message = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SignUp – AttendEase</title>
    <?php include 'cdn.php'?>
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/signup.css">
    <style>
     
    </style>
</head>
<body>
<?php include 'header.php'?>

<div class="signup_all">
    <form method="POST" action="">
        <div class="forms_title">
            <h2>Admin Signup</h2>
        </div>

        <!-- Display the error or success message -->
        <?php if ($error_message): ?>
            <div class="message">
                <?php echo $error_message; ?>
            </div>
        <?php elseif ($success_message): ?>
            <div class="success-message">
                <?php echo $success_message; ?>
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
            <button type="submit">Sign up</button>
        </div>

        <div class="forms">
            <p>Already have an account? <a href="login.php">Login</a></p>
        </div>
    </form>
</div>

</body>
</html>
