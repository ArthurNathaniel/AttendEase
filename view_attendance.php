<?php
include 'db.php';

// Fetch attendance records
$query = "
    SELECT e.name, a.date, a.reporting_time 
    FROM attendance a 
    JOIN employees e ON a.employee_id = e.id
    ORDER BY a.date DESC
";
$result = mysqli_query($conn, $query);

// Salary calculation logic (example: $20 per day)
$salary_per_day = 20;
$salary_data = [];

$salaryQuery = "
    SELECT e.name, COUNT(*) as days_present 
    FROM attendance a 
    JOIN employees e ON a.employee_id = e.id
    GROUP BY e.id
";
$salaryResult = mysqli_query($conn, $salaryQuery);

while ($row = mysqli_fetch_assoc($salaryResult)) {
    $salary_data[$row['name']] = $row['days_present'] * $salary_per_day;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Attendance & Salary</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .salary {
            color: green;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h2>Attendance Records</h2>

    <table>
        <thead>
            <tr>
                <th>Employee Name</th>
                <th>Date</th>
                <th>Reporting Time</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['date']; ?></td>
                    <td><?php echo $row['reporting_time']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <h2>Salary Calculation</h2>
    <table>
        <thead>
            <tr>
                <th>Employee Name</th>
                <th>Days Present</th>
                <th>Salary</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($salary_data as $name => $salary): ?>
                <tr>
                    <td><?php echo $name; ?></td>
                    <td><?php echo $salary / $salary_per_day; ?></td>
                    <td class="salary"><?php echo "$" . $salary; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
