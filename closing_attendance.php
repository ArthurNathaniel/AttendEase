<?php
include 'db.php';

// Set timezone to GMT (Ghana Mean Time)
date_default_timezone_set('Africa/Accra');

// Fetch employees with profile images and attendance status
$query = "SELECT e.id, e.name, e.profile_image, a.date AS attendance_date, a.closing_time
          FROM employees e
          LEFT JOIN attendance a ON e.id = a.employee_id AND a.date = ?
          WHERE a.date IS NULL OR a.date = ?";

$date = date('Y-m-d'); // Today's date
$result = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($result, 'ss', $date, $date);
mysqli_stmt_execute($result);
$result_set = mysqli_stmt_get_result($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $employee_id = $_POST['employee_id'];
    $date = $_POST['date'];
    $closing_time = $_POST['closing_time'];

    // Check if attendance for the employee on the selected date already has a closing time
    $checkAttendanceQuery = "SELECT * FROM attendance WHERE employee_id = ? AND date = ? AND closing_time IS NOT NULL";
    $stmt = mysqli_prepare($conn, $checkAttendanceQuery);
    mysqli_stmt_bind_param($stmt, 'is', $employee_id, $date);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        $message = "Error: Closing time already marked for this employee on " . $date . "!";
    } else {
        // Update closing time for the existing attendance record
        $attendanceQuery = "UPDATE attendance SET closing_time = ? WHERE employee_id = ? AND date = ?";
        $stmt_insert = mysqli_prepare($conn, $attendanceQuery);
        mysqli_stmt_bind_param($stmt_insert, 'sis', $closing_time, $employee_id, $date);

        if (mysqli_stmt_execute($stmt_insert)) {
            $message = "Closing time marked successfully for " . $date . "!";
        } else {
            $message = "Error: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt_insert);
    }

    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mark Employee Closing Attendance</title>
    <?php include 'cdn.php'; ?>
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/attendance.css">
</head>
<body>
<?php include 'navbar.php'; ?>
    <div class="attendance_all">
        <h2 style="text-align: center;">Mark Employee Closing Attendance</h2>

        <?php if (isset($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST">
            <table class="form-table">
                <tr>
                    <th>Employee Name</th>
                    <th>Profile Image</th>
                    <th>Date</th>
                    <th>Closing Time</th>
                    <th>Action</th>
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
                            <input type="hidden" name="employee_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                            <input type="text" name="date" value="<?php echo date('Y-m-d'); ?>" readonly>
                        </td>
                        <td>
                            <!-- Display closing time with AM/PM format -->
                            <input type="text" name="closing_time" value="<?php echo date('h:i A'); ?>" readonly>
                        </td>
                        <td>
                            <button type="submit">Mark Closing Attendance</button>
                        </td>
                        <td>
                            <?php if ($row['closing_time']): ?>
                                <span class="status">Closing Marked</span>
                            <?php else: ?>
                                <span class="status error">Not Marked</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </form>
    </div>

</body>
</html>
