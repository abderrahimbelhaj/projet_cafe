<?php
error_reporting(E_ALL);
ini_set('display_errors', 2);

// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté et est un admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    die("Accès refusé. Vous devez être admin pour voir cette page.");
}

// Inclure le fichier de connexion à la base de données
require_once 'config.php'; // Assurez-vous que ce fichier configure correctement la connexion

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $price = $_POST['price'];
    $image = $_FILES['image'];

    // Vérification de la validité des données
    if (empty($name) || empty($price) || empty($image['name'])) {
        echo "Tous les champs sont obligatoires.";
    } else {
        // Définir le répertoire cible
        $target_dir = "uploads/";

        // Vérifier si le dossier existe, sinon le créer
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true); // Créer le dossier avec les permissions 0755
        }

        // Déplacer le fichier uploadé dans un dossier spécifique
        $target_file = $target_dir . basename($image["name"]);

        // Vérifier si le fichier est une image
        $check = getimagesize($image["tmp_name"]);
        if ($check === false) {
            echo "Le fichier n'est pas une image.";
        } else {
            // Déplacer le fichier dans le dossier de destination
            if (move_uploaded_file($image["tmp_name"], $target_file)) {
                // Préparer et exécuter la requête SQL pour insérer le produit
                $sql = "INSERT INTO products (name, price, image) VALUES (?, ?, ?)";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("sds", $name, $price, $target_file);
                    if ($stmt->execute()) {
                        echo "Produit ajouté avec succès.";
                    } else {
                        echo "Erreur lors de l'ajout du produit : " . $conn->error;
                    }
                    $stmt->close();
                } else {
                    echo "Erreur lors de la préparation de la requête : " . $conn->error;
                }
            } else {
                echo "Erreur lors du téléchargement de l'image.";
            }
        }
    }
}
?>










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
            <a href="manage_products.php" class="nav-link"> return</a>
          </li>
          
          
        </ul>

        <button id="menu-open-button" class="fas fa-bars"></button>
      </nav>
    </header>

   
    <div class="form-wrapper">
        <h2>Add product</h2>
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-control">
            <label for="name">Name </label>
        <input type="text" name="name" required>
            </div>
            <div class="form-control">
            <label for="price">Price </label>
            <input type="number" name="price" step="0.01" required>
            </div>
           
            
            <input type="file" name="image" accept="image/*" required>
                                                                                                                                                                                                                                                    
<button type="submit">Add</button>
          
        </form>
       
    </div>
</body>
</html>