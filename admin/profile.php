<?php
include 'auth_check.php';
checkAuth();

$servername = "localhost";
$username = "root";
$db_password = "";
$dbname = "th_db";

$conn = new mysqli($servername, $username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_SESSION['email'];
$query = "SELECT * FROM admin WHERE email = '$email'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $name = $user['name'];
    $email = $user['email'];
    // Password should not be displayed for security reasons
    $passwordPlaceholder = '********'; // Placeholder for password field
} else {
    // Handle error if user not found (though it should not happen if user is logged in)
    $name = 'N/A';
    $email = 'N/A';
    $passwordPlaceholder = 'N/A';
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="short icon" href="../portal/logo.jpg" type="x-icon">
    <title><?php echo "Admin | Profile"; ?></title>
    <link rel="stylesheet" href="css/profile.css">
    <link rel="stylesheet" href="../assets/font/inter.css">
    <link rel="stylesheet" href="../node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
    
    <style>
        /* Hide the default browser tooltip */
        .tooltip-text {
            position: absolute;
            display: none;
            background-color: #5C5C5C;
            color: #fff;
            padding: 5px;
            border-radius: 5px;
            font-size: 10px;
            white-space: nowrap;
            z-index: 999;
            font-family:'Inter';
        }

        .active-icon {
            color: green !important;
        }

        .dashboard-content {
            width: 100%;
            padding: 20px;
        }

        #example {
            width: 100% !important;
            margin: auto;
        }

        #example th,
        #example td {
            text-align: center;
        }
    </style>
</head>
<body>
    <header class="nav">
        <div class="nav-left">
            <img src="../assets/img/tech-haven-logo2.png" alt="Tech Haven Logo" id="logo">
        </div>
        <div class="nav-right">
            <p style="font-size: 0.8rem; font-style: normal; margin-top:5px;">
                Hello, <?php echo htmlspecialchars($_SESSION['name']); ?>
            </p>
        </div>
    </header>
    <section class="container">
        <div class="sidebar">
            <ul class="sidebar-list">
                <li class="sidebar-list-item">
                    <a href="dashboard.php" class="sidebar-link tooltip-trigger" data-tooltip="Dashboard">
                        <i class="bi bi-bar-chart-line-fill"></i>
                    </a>
                </li>
                <li class="sidebar-list-item">
                    <a href="orders.php" class="sidebar-link tooltip-trigger" data-tooltip="Orders">
                        <i class="bi bi-clipboard2-fill"></i>
                    </a>
                </li>
                <li class="sidebar-list-item">
                    <a href="products.php" class="sidebar-link tooltip-trigger" data-tooltip="Products">
                        <i class="bi bi-box-seam-fill"></i>
                    </a>
                </li>
                <li class="sidebar-list-item">
                    <a href="inventory.php" class="sidebar-link tooltip-trigger" data-tooltip="Inventory">
                        <i class="bi bi-inboxes-fill"></i>
                    </a>
                </li>
                <li class="sidebar-list-item">
                    <a href="feedbacks.php" class="sidebar-link tooltip-trigger" data-tooltip="Feedbacks">
                        <i class="bi bi-chat-square-dots-fill"></i>
                    </a>
                </li>
                <li class="sidebar-list-item">
                    <a href="advertisement.php" class="sidebar-link tooltip-trigger" data-tooltip="Messages">
                        <i class="bi bi-envelope-plus-fill"></i>
                    </a>
                </li>
                <li class="sidebar-list-item">
                    <a href="users.php" class="sidebar-link tooltip-trigger" data-tooltip="Users">
                        <i class="bi bi-people-fill"></i>
                    </a>
                </li>
                <hr>

                <li class="sidebar-list-item">
                    <a href="profile.php" class="sidebar-link tooltip-trigger active" data-tooltip="Edit Profile">
                        <i class="bi bi-person-fill-gear"></i>
                    </a>
                </li>
                <li class="sidebar-list-item">
                    <a href="logout.php" class="sidebar-link tooltip-trigger" data-tooltip="Logout">
                        <i class="bi bi-box-arrow-right"></i>
                    </a>
                </li>
            </ul>
        </div>
    </aside>
    <div class="tooltip-text"></div>
    <div class="dashboard-content">
        <h1>Admin Profile</h1><br>
        <div class="wrapper-dashboard">
            <h3 style="text-align:center;">Account Information</h3><br>

            <div class="container2">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label for="password">Password:</label>
                            <input type="password" class="form-control" id="password" name="password" value="<?php echo htmlspecialchars($passwordPlaceholder); ?>" disabled>
                        </div>
                        <a href="edit_profile.php" class="btn-edit"><i class="bi bi-pencil-fill"></i> Change Password</a><br>
                        <br><?php
                        if (isset($_SESSION['password_change_success'])) {
                            echo "<p style='color: green;'>Password changed successfully</p>";
                            unset($_SESSION['password_change_success']); // Clear the success message
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var tooltipTriggers = document.querySelectorAll('.tooltip-trigger');
            var tooltipText = document.querySelector('.tooltip-text');

            tooltipTriggers.forEach(function (trigger) {
                trigger.addEventListener('mouseover', function (event) {
                    var tooltipContent = this.getAttribute('data-tooltip');
                    tooltipText.textContent = tooltipContent;
                    tooltipText.style.display = 'block';
                    tooltipText.style.top = (event.clientY + window.scrollY + 10) + 'px';
                    tooltipText.style.left = (event.clientX + window.scrollX + 10) + 'px';
                });

                trigger.addEventListener('mouseout', function () {
                    tooltipText.style.display = 'none';
                });
            });
        });
    </script>
</body>
</html>
