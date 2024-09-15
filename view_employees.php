<?php
include 'db.php';

// Fetch all employees from the database
$query = "SELECT * FROM employees";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Employees - AttendEase</title>
    <?php include 'cdn.php'?>
    <link rel="stylesheet" href="./css/base.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #0e4740;
            color: #fff;
        }

        img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
        }

        .container {
            padding: 20px;
        }

        .title {
            margin-bottom: 20px;
        }
        .view_employee{
            padding: 0 7%;
            margin-top: 50px;
        }
    </style>
</head>
<body>
<?php include 'navbar.php'?>
    <div class="view_employee">
        <div class="title">
            <h2>Employee List</h2>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Profile Image</th>
                    <th>Name</th>
                    <th>Gender</th>
                    <th>Date of Birth</th>
                    <th>Position</th>
                    <th>Salary Per Day</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($employee = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $employee['id']; ?></td>
                            <td>
                                <?php if ($employee['profile_image']): ?>
                                    <img src="employee_profile/<?php echo $employee['profile_image']; ?>" alt="Profile Image">
                                <?php else: ?>
                                    <img src="employee_profile/default.png" alt="Default Image">
                                <?php endif; ?>
                            </td>
                            <td><?php echo $employee['name']; ?></td>
                            <td><?php echo $employee['gender']; ?></td>
                            <td><?php echo $employee['dob']; ?></td>
                            <td><?php echo $employee['position']; ?></td>
                            <td><?php echo $employee['salary_per_hour']; ?></td>
                            <td><a href="edit_employee.php?id=<?php echo $employee['id']; ?>">Edit</a> | <a href="delete_employee.php?id=<?php echo $employee['id']; ?>" onclick="return confirm('Are you sure you want to delete this employee?');">Delete</a></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8">No employees found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php include 'footer.php';?>
</body>
</html>
