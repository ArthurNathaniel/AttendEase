<?php
include 'db.php';

// Set timezone to GMT (Ghana Time)
date_default_timezone_set('Africa/Accra');

// Fetch employees with profile images and attendance status
$query = "SELECT e.id, e.name, e.profile_image, a.date AS attendance_date
          FROM employees e
          LEFT JOIN attendance a ON e.id = a.employee_id AND a.date = ?
          WHERE a.date IS NULL OR a.date = ?";

$date = date('Y-m-d'); // Today's date in GMT
$result = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($result, 'ss', $date, $date);
mysqli_stmt_execute($result);
$result_set = mysqli_stmt_get_result($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $message = '';

    foreach ($_POST['employee_id'] as $index => $employee_id) {
        $date = $_POST['date'][$index];
        $reporting_time = $_POST['reporting_time'][$index];

        // Check if attendance for the employee on the selected date already exists
        $checkAttendanceQuery = "SELECT * FROM attendance WHERE employee_id = ? AND date = ?";
        $stmt = mysqli_prepare($conn, $checkAttendanceQuery);
        mysqli_stmt_bind_param($stmt, 'is', $employee_id, $date);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $message .= "Error: Attendance already marked for employee ID " . $employee_id . " on " . $date . "!<br>";
        } else {
            $attendanceQuery = "INSERT INTO attendance (employee_id, date, reporting_time) VALUES (?, ?, ?)";
            $stmt_insert = mysqli_prepare($conn, $attendanceQuery);
            mysqli_stmt_bind_param($stmt_insert, 'iss', $employee_id, $date, $reporting_time);

            if (mysqli_stmt_execute($stmt_insert)) {
                $message .= "Attendance marked successfully for employee ID " . $employee_id . " on " . $date . "!<br>";
            } else {
                $message .= "Error: " . mysqli_error($conn) . "<br>";
            }

            mysqli_stmt_close($stmt_insert);
        }

        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mark Employee Attendance</title>
    <?php include 'cdn.php'; ?>
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/attendance.css">
</head>
<body>
<?php include 'navbar.php'; ?>
    <div class="attendance_all">
        <h2 style="text-align: center;">Mark Employee Attendance</h2>

        <?php if (isset($message) && $message != ''): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST">
            <table class="form-table">
                <tr>
                    <th>Employee Name</th>
                    <th>Profile Image</th>
                    <th>Date</th>
                    <th>Reporting Time</th>
                    <th>Status</th>
                </tr>

                <?php while ($row = mysqli_fetch_assoc($result_set)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td>
                            <?php if ($row['profile_image']): ?>
                                <img src="employee_profile/<?php echo htmlspecialchars($row['profile_image']); ?>" alt="Profile Image">
                            <?php else: ?>
                                <img src="employee_profile/default.png" alt="Default Image">
                            <?php endif; ?>
                        </td>
                        <td>
                            <input type="hidden" name="employee_id[]" value="<?php echo htmlspecialchars($row['id']); ?>">
                            <input type="text" name="date[]" value="<?php echo date('Y-m-d'); ?>" readonly>
                        </td>
                        <td>
                            <input type="text" name="reporting_time[]" value="<?php echo date('H:i A'); ?>" readonly>
                        </td>
                        <td>
                            <?php if ($row['attendance_date']): ?>
                                <span class="status">Marked</span>
                            <?php else: ?>
                                <span class="status error">Not Marked</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
            <button type="submit">Submit Attendance</button>
        </form>
    </div>
</body>
</html>
