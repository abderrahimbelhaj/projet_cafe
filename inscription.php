<?php
// Démarrer la session
session_start();

require_once 'config.php';
// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $name = $_POST['name'];
    $password = $_POST['password'];

    // Valider les champs
    if (empty($name) || empty($password)) {
        echo "Tous les champs sont obligatoires.";
    } else {
        // Hacher le mot de passe avec md5
        $hashed_password = md5($password);

        // Rôle par défaut 'client'
        $role = 'client';

        // Préparer et exécuter la requête SQL d'insertion
        $sql = "INSERT INTO users (name, password, role) VALUES (?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            // Associer les paramètres
            $stmt->bind_param("sss", $name, $hashed_password, $role);
            if ($stmt->execute()) {
                // Inscription réussie, enregistrer le nom dans la session
                $_SESSION['user_id'] = $stmt->insert_id;
                $_SESSION['username'] = $name;
                $_SESSION['role'] = $role;  // Rôle client
                echo "Inscription réussie!";

                // Redirection vers la page d'accueil ou tableau de bord
                header("Location: login.php");
                exit();
            } else {
                echo "Erreur: " . $stmt->error;
            }
            $stmt->close();
        }
    }

    // Fermer la connexion à la base de données
    $conn->close();
}
?>

<!-- Formulaire HTML d'inscription -->

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
        <h2>Sign Up</h2>
        <form method="post" action="">
            <div class="form-control">
            <input type="text" id="name" name="name" required><br>
            <label for="name">Username:</label>
            </div>
            <div class="form-control">
            <input type="password" id="password" name="password" required><br>
            <label for="password">Password:</label>
            </div>
            <button type="submit">Sign Up</button>
          
        </form>
        <p >Already have an account? <a href="login.php">Sign in now</a></p>
        
    </div>
</body>
</html>