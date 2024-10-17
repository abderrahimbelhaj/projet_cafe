<?php
// Démarrer la session
session_start();

require_once 'config.php';

// Vérifier si le formulaire de login a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $name = trim($_POST['name']);
    $password = trim($_POST['password']);

    // Valider les champs
    if (empty($name) || empty($password)) {
        echo "Tous les champs sont obligatoires.";
    } else {
        // Hacher le mot de passe avec password_hash
        $hashed_password = md5($password);

        // Préparer et exécuter la requête SQL pour vérifier l'utilisateur
        $sql = "SELECT id, name, role FROM users WHERE name = ? AND password = ?";
        if ($stmt = $conn->prepare($sql)) {
            // Associer les paramètres
            $stmt->bind_param("ss", $name, $hashed_password);
            $stmt->execute();
            $stmt->store_result();

            // Vérifier si un utilisateur a été trouvé
            if ($stmt->num_rows == 1) {
                // Associer les résultats aux variables
                $stmt->bind_result($user_id, $username, $role);
                $stmt->fetch();

                // Enregistrer les informations de l'utilisateur dans la session
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $role;

                // Redirection basée sur le rôle
                if ($role == 'client') {
                    header("Location: reservation.php");
                } elseif ($role == 'admin') {
                    header("Location: hello_admin.php");
                }
                exit();
            } else {
                echo "Nom d'utilisateur ou mot de passe incorrect.";
            }
            $stmt->close();
        } else {
            echo "Erreur lors de la préparation de la requête.";
        }
    }

    // Fermer la connexion à la base de données
    $conn->close();
}
?>

<!-- Formulaire HTML de login -->

    
    
    
   
   




<!DOCTYPE html>
<!-- Coding By CodingNepal - www.codingnepalweb.com -->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Netflix Login Page | CodingNepal</title>
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <!-- Linking Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="style.css" />
</head>
<body>
<header>
      <nav class="navbar">
       

        <ul class="nav-menu">
          <button id="menu-close-button" class="fas fa-times"></button>

          <li class="nav-item">
            <a href="index.php" class="nav-link"> Home</a>
          </li>
          
          
        </ul>

        <button id="menu-open-button" class="fas fa-bars"></button>
      </nav>
    </header>

   
    <div class="form-wrapper">
        <h2>Sign In</h2>
        <form method="post" action="">
            <div class="form-control">
            <input type="text" id="name" name="name" required><br>
            <label for="name">Username</label>
            </div>
            <div class="form-control">
            <input type="password" id="password" name="password" required><br>
            <label for="password">Password</label>
            </div>
            <button type="submit">Sign in</button>
          
        </form>
        <p >Don't have an account? <a href="inscription.php">Sign up now</a></p>
        
    </div>
</body>
</html>