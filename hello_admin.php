<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard admin</title>
    <style>
        body {
            display: flex;
            margin: 0; /* Remove default body margins */
            height: 100vh; /* Make sure body takes full viewport height */

        }
        a{
            text-decoration:none;
            color:black;
        }

        #sidebar {
            background-color: #f1f1f1;
            width: 300px;
            height: 100%; /* Sidebar takes full height */
            padding: 15px;
        }

        #navbar {
            background-color: #3b141c;
            color: #fff;
            padding: 10px 20px;
        }

        #content {
            flex: 1; /* Content takes remaining space */
            padding: 0px;
           
        }
    </style>
</head>
<body>

    <aside id="sidebar">
        <h1>Menu</h1>
        
            <a href="manage_products.php">Menu management</a><br>
            <br>
            <a href="list_reservation.php">List of reservations</a><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
            <a href="logout.php" class="logout-btn">Log out</a>
      
    </aside>

    <main id="content">
        <div id="navbar">
            <h2>Dashboard admin</h2>
        </div>
        </main>

</body>
</html>