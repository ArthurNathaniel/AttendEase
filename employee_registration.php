<?php
include 'db.php';

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $position = mysqli_real_escape_string($conn, $_POST['position']);
    $salary_per_hour = mysqli_real_escape_string($conn, $_POST['salary_per_hour']);  // Updated to salary per hour

    // Handle image upload
    $profile_image = $_FILES['profile_image']['name'];
    $target_dir = "employee_profile/";
    $target_file = $target_dir . basename($profile_image);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if the image file is a valid image
    $check = getimagesize($_FILES['profile_image']['tmp_name']);
    if ($check === false) {
        $error_message = "File is not an image.";
    } elseif (file_exists($target_file)) {
        $error_message = "Sorry, file already exists.";
    } elseif ($_FILES['profile_image']['size'] > 5000000) {
        $error_message = "Sorry, your file is too large. Max 5MB allowed.";
    } elseif ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        $error_message = "Only JPG, JPEG, and PNG files are allowed.";
    } else {
        // If no error, try to move uploaded file
        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file)) {
            // Insert employee data into database
            $query = "INSERT INTO employees (name, gender, dob, position, salary_per_hour, profile_image) 
                      VALUES ('$name', '$gender', '$dob', '$position', '$salary_per_hour', '$profile_image')";  // Updated query
            if (mysqli_query($conn, $query)) {
                $success_message = "Employee registered successfully!";
            } else {
                $error_message = "Error: " . mysqli_error($conn);
            }
        } else {
            $error_message = "There was an error uploading the file.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Registration - AttendEase</title>
    <?php include 'cdn.php'; ?>
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/signup.css">
    <style>
        .message {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }
        .success-message {
            color: green;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="signup_all">
    <form method="POST" action="" enctype="multipart/form-data">
        <div class="forms_title">
            <h2>Employee Registration</h2>
        </div>

        <!-- Display error or success messages -->
        <?php if ($error_message): ?>
            <div class="message"><?php echo $error_message; ?></div>
        <?php elseif ($success_message): ?>
            <div class="success-message"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <div class="forms">
            <label for="name">Name:</label>
            <input type="text" name="name" required>
        </div>

        <div class="forms">
            <label for="gender">Gender:</label>
            <select name="gender" required>
                <option value="" selected hidden>Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
        </div>

        <div class="forms">
            <label for="dob">Date of Birth:</label>
            <input type="date" name="dob" required>
        </div>

        <div class="forms">
            <label for="position">Position:</label>
            <input type="text" name="position" required>
        </div>

        <!-- Update Salary field to Per Hour -->
        <div class="forms">
            <label for="salary_per_hour">Salary Per Hour:</label>  <!-- Updated label -->
            <input type="number" name="salary_per_hour" required>  <!-- Updated input name -->
        </div>

        <div class="forms">
            <label for="profile_image">Profile Image:</label>
            <input type="file" name="profile_image" accept="image/*" required>
        </div>

        <div class="forms">
            <button type="submit">Register Employee</button>
        </div>
    </form>
</div>
<?php include 'footer.php'; ?>
</body>
</html>
