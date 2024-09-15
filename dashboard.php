<?php
include 'db.php'; // Database connection

// Fetch total number of employees
$totalEmployeesQuery = "SELECT COUNT(*) AS total FROM employees";
$totalEmployeesResult = mysqli_query($conn, $totalEmployeesQuery);
$totalEmployees = mysqli_fetch_assoc($totalEmployeesResult)['total'];

// Fetch number of male employees
$maleEmployeesQuery = "SELECT COUNT(*) AS male FROM employees WHERE gender = 'Male'";
$maleEmployeesResult = mysqli_query($conn, $maleEmployeesQuery);
$maleEmployees = mysqli_fetch_assoc($maleEmployeesResult)['male'];

// Fetch number of female employees
$femaleEmployeesQuery = "SELECT COUNT(*) AS female FROM employees WHERE gender = 'Female'";
$femaleEmployeesResult = mysqli_query($conn, $femaleEmployeesQuery);
$femaleEmployees = mysqli_fetch_assoc($femaleEmployeesResult)['female'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Statistics</title>
    <?php include 'cdn.php' ?>
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/dashboard.css">

</head>

<body>
    <?php include 'navbar.php' ?>
    <div class="container">

        <div class="dashboard_title">
            <h1>Employee Statistics</h1>
        </div>

     
        <div class="statistics_grid">
            <div class="statistics">
                <p>Total Employees:</p>
                <h3><?php echo $totalEmployees; ?></h3>
            </div>
            <div class="statistics">
                <p>Male Employees: </p>
                <h3><?php echo $maleEmployees; ?></h3>
            </div>
            <div class="statistics">
                <p>Female Employees: </p>
                <h3><?php echo $femaleEmployees; ?></h3>
            </div>
        </div>

        <div class="dashboard_title">
            <h1>Employee Chart</h1>
        </div>
    
        <div class="chart_grid">
            <div class="chart">
                <canvas id="totalEmployeesChart" width="400" height="200"></canvas>
            </div>
            <div class="chart">
                <canvas id="employeeGenderChart" width="400" height="200"></canvas>
            </div>
        </div>
      
    </div>

    <?php include 'footer.php' ?>
    <script>
        // Bar chart for total employees
        const totalEmployeesCtx = document.getElementById('totalEmployeesChart').getContext('2d');
        const totalEmployeesChart = new Chart(totalEmployeesCtx, {
            type: 'bar',
            data: {
                labels: ['Total Employees'],
                datasets: [{
                    label: '# of Employees',
                    data: [<?php echo $totalEmployees; ?>],
                    backgroundColor: 'rgba(75, 192, 192, 0.7)', // Bar color
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Pie chart for gender distribution
        const employeeGenderCtx = document.getElementById('employeeGenderChart').getContext('2d');
        const employeeGenderChart = new Chart(employeeGenderCtx, {
            type: 'bar',
            data: {
                labels: ['Male Employees', 'Female Employees'],
                datasets: [{
                    label: 'Gender Distribution',
                    data: [<?php echo $maleEmployees; ?>, <?php echo $femaleEmployees; ?>],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.7)', // Male color
                        'rgba(255, 99, 132, 0.7)' // Female color
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                }
            }
        });
    </script>

</body>

</html>