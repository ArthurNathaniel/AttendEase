<?php
include 'db.php';

$modalMessage = ''; // Variable to store the modal message
$redirect = false;  // Flag to trigger redirect

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hashing the password

    // Check if email already exists
    $checkQuery = "SELECT * FROM admins WHERE email = '$email'";
    $result = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($result) > 0) {
        $modalMessage = "Email already registered!";
    } else {
        $query = "INSERT INTO admins (email, password) VALUES ('$email', '$hashed_password')";
        if (mysqli_query($conn, $query)) {
            $modalMessage = "Admin registered successfully!";
            $redirect = true; // Set redirect flag to true after successful registration
        } else {
            $modalMessage = "Error: " . mysqli_error($conn);
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
</head>

<body>
<?php include 'header.php'?>
  <div class="signup_all">
  <form method="POST" action="">
        <div class="forms_title">
        <h2>Admin Signup</h2>
        </div>
     <div class="forms">
     <label for="email">Email:</label>
     <input type="email" name="email" required>
     </div>
     <div class="forms">
     <label for="password">Password:</label>
     <input type="password" name="password" required>
     </div>
   
        <div class="forms">
      <button type="submit" >Sign up</button>
        </div>

        <div class="forms">
            <p>Already have an account <a href="login.php">Login</a></p>
        </div>
    </form>
  </div>

    <!-- Modal -->
    <div id="messageModal" class="modal">
        <div class="modal-content">
            <p id="modalMessage"><?php echo $modalMessage; ?></p>
            <button onclick="closeModal()">OK</button>
        </div>
    </div>

    <script>
        // Function to open the modal
        function openModal() {
            document.getElementById('messageModal').style.display = 'block';
        }

        // Function to close the modal
        function closeModal() {
            document.getElementById('messageModal').style.display = 'none';
            <?php if ($redirect): ?>
                // Redirect to login.php after successful registration
                window.location.href = 'login.php';
            <?php endif; ?>
        }

        // Display the modal if there's a message
        <?php if ($modalMessage): ?>
            openModal();
        <?php endif; ?>
    </script>

</body>

</html>