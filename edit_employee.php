<?php
session_start();
include 'db.php';

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch the employee's details
if (isset($_GET['id'])) {
    $employee_id = intval($_GET['id']);
    $query = "SELECT * FROM employees WHERE id = $employee_id";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $employee = mysqli_fetch_assoc($result);
    } else {
        echo "Employee not found.";
        exit;
    }
}

// Handle the form submission for editing
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $position = mysqli_real_escape_string($conn, $_POST['position']);
    $salary_per_hour = mysqli_real_escape_string($conn, $_POST['salary_per_hour']);

    // Handle profile image upload
    if (!empty($_FILES['profile_image']['name'])) {
        $target_dir = "employee_profile/";
        $profile_image = basename($_FILES["profile_image"]["name"]);
        $target_file = $target_dir . $profile_image;
        move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file);
    } else {
        $profile_image = $employee['profile_image']; // If no new image is uploaded, keep the old image
    }

    // Update the employee details in the database
    $updateQuery = "UPDATE employees SET name = '$name', gender = '$gender', dob = '$dob', position = '$position', salary_per_hour = '$salary_per_hour', profile_image = '$profile_image' WHERE id = $employee_id";

    if (mysqli_query($conn, $updateQuery)) {
        header("Location: view_employees.php?msg=Employee updated successfully");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employee - AttendEase</title>
    <?php include 'cdn.php'; ?>
    <link rel="stylesheet" href="./css/base.css">
    <style>
        .edit_employee {
            padding: 20px;
            max-width: 500px;
            margin: 0 auto;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-top: 50px;
        }

        .edit_employee h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .edit_employee label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .edit_employee input,
        .edit_employee select {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .edit_employee button {
            width: 100%;
            padding: 10px;
            background-color: #0e4740;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .edit_employee button:hover {
            background-color: #09392f;
        }

        img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="edit_employee">
        <h2>Edit Employee</h2>
        <form method="POST" enctype="multipart/form-data">
            <label for="name">Name:</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($employee['name']); ?>" required>

            <label for="gender">Gender:</label>
            <select name="gender" required>
                <option value="Male" <?php if ($employee['gender'] == 'Male') echo 'selected'; ?>>Male</option>
                <option value="Female" <?php if ($employee['gender'] == 'Female') echo 'selected'; ?>>Female</option>
            </select>

            <label for="dob">Date of Birth:</label>
            <input type="date" name="dob" value="<?php echo $employee['dob']; ?>" required>

            <label for="position">Position:</label>
            <input type="text" name="position" value="<?php echo htmlspecialchars($employee['position']); ?>" required>

            <label for="salary_per_hour">Salary Per Day:</label>
            <input type="number" name="salary_per_hour" value="<?php echo $employee['salary_per_hour']; ?>" required>

            <label for="profile_image">Profile Image:</label>
            <input type="file" name="profile_image">
            <img src="employee_profile/<?php echo $employee['profile_image']; ?>" alt="Profile Image">

            <button type="submit">Update Employee</button>
        </form>
    </div>
    <?php include 'footer.php'; ?>
</body>

</html>