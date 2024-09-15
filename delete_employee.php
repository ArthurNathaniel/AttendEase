<?php
session_start();
include 'db.php';

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// Check if the employee ID is provided
if (isset($_GET['id'])) {
    $employee_id = intval($_GET['id']); // Get employee id from URL

    // Prepare the delete query
    $deleteQuery = "DELETE FROM employees WHERE id = ?";
    $stmt = mysqli_prepare($conn, $deleteQuery);

    if ($stmt) {
        // Bind the employee ID to the query
        mysqli_stmt_bind_param($stmt, 'i', $employee_id);

        // Execute the query
        if (mysqli_stmt_execute($stmt)) {
            // Redirect back to the employee list page after successful deletion
            header("Location: view_employees.php?msg=Employee deleted successfully");
            exit;
        } else {
            echo "Error: Could not delete employee.";
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        echo "Error: Could not prepare the delete query.";
    }
} else {
    echo "Error: Employee ID not provided.";
}

// Close the database connection
mysqli_close($conn);
?>
