<?php
// Inclure le fichier de connexion à la base de données
require_once 'config.php'; // Assurez-vous que ce fichier configure correctement la connexion

// Initialiser la variable de recherche
$search = '';

// Vérifier si le formulaire de recherche a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search = trim($_POST['search']); // Récupérer le terme de recherche et supprimer les espaces
}

// Requête pour récupérer les produits en fonction du terme de recherche
$sql = "SELECT name, price, image FROM products WHERE name LIKE ?";
$stmt = $conn->prepare($sql);
$search_param = "%" . $search . "%"; // Ajouter des jokers pour la recherche
$stmt->bind_param("s", $search_param);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <link rel="stylesheet" href="menu.css"> <!-- Assurez-vous que le chemin est correct -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <!-- Linking Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="style.css" />
</head>
<body style="background-color:#3b141c;">
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

    
        <h2 style="color:white"> Our Menu</h2>
        
        <!-- Formulaire de recherche -->
        <form method="POST" action="">
            <input type="text" name="search" placeholder="Rechercher un produit..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit"style="background-color:#f3961c";>Rechercher</button>
        </form>

        <div class="menu-items">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='menu-item'>";
                    echo "<img src='" . htmlspecialchars($row['image']) . "' alt='" . htmlspecialchars($row['name']) . "'>";
                    echo "<h3>" . htmlspecialchars($row['name']) . "</h3>";
                    echo "<p> " . number_format($row['price'], 2) . " €</p>";
                    echo "</div>";
                }
            } else {
                echo "<p>Aucun produit trouvé.</p>";
            }
            ?>
        </div>
    
</body>
</html>










