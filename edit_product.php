<?php
session_start();
require_once 'config.php';

// Vérifier si l'utilisateur est connecté et est un admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    echo "Accès refusé. Vous devez être admin pour voir cette page.";
    exit();
}

// Vérifier si l'ID du produit est passé
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Récupérer les informations du produit
    $sql = "SELECT name, price, image FROM products WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $stmt->bind_result($name, $price, $image);
        $stmt->fetch();
        $stmt->close();
    }

    // Vérifier si le formulaire a été soumis
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];
        $price = $_POST['price'];
        $image = $_FILES['image'];

        // Vérification de la validité des données
        if (empty($name) || empty($price)) {
            echo "Tous les champs sont obligatoires.";
        } else {
            // Mettre à jour le produit
            $update_sql = "UPDATE products SET name = ?, price = ? WHERE id = ?";
            if ($update_stmt = $conn->prepare($update_sql)) {
                $update_stmt->bind_param("sdi", $name, $price, $product_id);
                if ($update_stmt->execute()) {
                    // Si une nouvelle image est téléchargée, déplacer l'ancienne image
                    if (!empty($image['name'])) {
                        // Définir le répertoire cible
                        $target_dir = "uploads/";
                        $target_file = $target_dir . basename($image["name"]);
                        if (move_uploaded_file($image["tmp_name"], $target_file)) {
                            $update_image_stmt = $conn->prepare("UPDATE products SET image = ? WHERE id = ?");
                            $update_image_stmt->bind_param("si", $target_file, $product_id);
                            $update_image_stmt->execute();
                            $update_image_stmt->close();
                        }
                    }
                    echo "Produit modifié avec succès.";
                    header("Location: manage_products.php");
                    exit(); // Ajout de exit après redirection
                } else {
                    echo "Erreur lors de la modification du produit : " . $conn->error;
                }
                $update_stmt->close();
            }
        }
    }
} else {
    echo "ID de produit manquant.";
}

// Fermer la connexion à la base de données
$conn->close();
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
        <h2>Modify</h2>
        <form method="post" enctype="multipart/form-data">
            <div class="form-control">
            <label for="name"></label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required><br>
            </div>
            <div class="form-control">
            <label for="price"></label>
            <input type="number" id="price" name="price" value="<?php echo htmlspecialchars($price); ?>" step="0.01" required><br>
            </div>
           
            <label for="image">Image (facultatif):</label>
            <input type="file" id="image" name="image"><br>
    
            <button type="submit">Modify</button>
          
        </form>
        <?php if (isset($image) && !empty($image)) : ?>
            <img src="<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($name); ?>" style="width:100px;height:100px;">
        <?php endif; ?>
    </div>
</body>
</html>
