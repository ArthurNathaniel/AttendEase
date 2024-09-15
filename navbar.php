<div class="navbar_all">
    <div class="logo"></div>
    <button id="toggleButton">
        <i class="fa-solid fa-bars-staggered"></i>
        
    </button>

    <div class="mobile">
    <a href="dashboard.php">Dashboard</a>
<a href="employee_registration.php">Onboarding Employee</a>
<a href="view_employees.php">View Employee</a>
<a href="reporting_attendance.php">Reporting Attendance</a>
<a href="closing_attendance.php">Closing Attendance</a>   
<a href="">Salary</a>

    </div>
   
</div>

<script>
        // Get the button and sidebar elements
        var toggleButton = document.getElementById("toggleButton");
    var sidebar = document.querySelector(".mobile");
    var icon = toggleButton.querySelector("i");

    // Add click event listener to the button
    toggleButton.addEventListener("click", function() {
        // Toggle the visibility of the sidebar
        if (sidebar.style.display === "none" || sidebar.style.display === "") {
            sidebar.style.display = "flex";
            sidebar.style.flexDirection = "column";
            icon.classList.remove("fa-bars-staggered");
            icon.classList.add("fa-xmark");
        } else {
            sidebar.style.display = "none";
            icon.classList.remove("fa-xmark");
            icon.classList.add("fa-bars-staggered");
        }
    });
</script>