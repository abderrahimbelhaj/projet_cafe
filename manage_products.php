<?php
session_start();
require_once 'config.php';

// Vérifier si l'utilisateur est connecté et est un admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    echo "Accès refusé. Vous devez être admin pour voir cette page.";
    exit();
}

// Requête pour récupérer tous les produits
$sql = "SELECT id, name, price, image FROM products";
$result = $conn->query($sql);

// Suppression d'un produit
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM products WHERE id = ?";
    if ($delete_stmt = $conn->prepare($delete_sql)) {
        $delete_stmt->bind_param("i", $delete_id);
        $delete_stmt->execute();
        $delete_stmt->close();
    }
}

// Fermer la connexion à la base de données
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Produits</title>
    <link rel="stylesheet" href="manage_products.css"> <!-- Assurez-vous que le chemin est correct -->
</head>
<body>
    
        <div style="display:flex;justify-content:space-between">
         <a href="hello_admin.php">Home</a>   
        <h2>Menu managment</h2>
        <a href="ajouter_produit.php">Add food</a>  
        </div>
        <table>
            <tr>
                <th style="background-color:#3b141c;">Name</th>
                <th>Price</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td>" . number_format($row['price'], 2) . " €</td>";
                    echo "<td><img src='" . htmlspecialchars($row['image']) . "' alt='" . htmlspecialchars($row['name']) . "' style='width:50px;height:50px;'></td>";
                    echo "<td>
                        <a href='edit_product.php?id=" . $row['id'] . "'>Modify</a> | 
                        <a href='manage_products.php?delete_id=" . $row['id'] . "' onclick=\"return confirm('Êtes-vous sûr de vouloir supprimer ce produit?');\">Delete</a>
                    </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>Aucun produit trouvé.</td></tr>";
            }
            ?>
        </table>

</body>
</html>
