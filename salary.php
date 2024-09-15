<?php
include 'db.php';

// Get current month and year
$current_month = date('m');
$current_year = date('Y');

// Fetch all employees and calculate their total hours worked and salary
$query = "SELECT e.id, e.name, e.salary_per_hour, 
                 SUM(h.hours_worked) AS total_hours
          FROM employees e
          LEFT JOIN employee_hours h 
          ON e.id = h.employee_id 
          AND MONTH(h.work_date) = ? 
          AND YEAR(h.work_date) = ?
          GROUP BY e.id";

$result = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($result, 'ii', $current_month, $current_year);
mysqli_stmt_execute($result);
$result_set = mysqli_stmt_get_result($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Salary Calculation</title>
    <link rel="stylesheet" href="./css/base.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px auto;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .message {
            color: green;
            text-align: center;
            margin-bottom: 20px;
        }

        .salary-table {
            margin-top: 50px;
            padding: 0 7%;
        }

        button {
            background-color: #007BFF;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="salary-table">
    <h2 style="text-align: center;">Employee Monthly Salary Calculation</h2>

    <table>
        <tr>
            <th>Employee Name</th>
            <th>Total Hours Worked</th>
            <th>Hourly Rate</th>
            <th>Total Salary (Take Home)</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result_set)): 
            // Calculate total salary based on total hours worked and hourly rate
            $total_hours = $row['total_hours'] ? $row['total_hours'] : 0;  // Handle NULL values
            $salary_per_hour = $row['salary_per_hour'];
            $total_salary = $total_hours * $salary_per_hour;
        ?>
            <tr>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo number_format($total_hours, 2); ?> hrs</td>
                <td><?php echo '₵' . number_format($salary_per_hour, 2); ?> / hr</td>
                <td><?php echo '₵' . number_format($total_salary, 2); ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>
