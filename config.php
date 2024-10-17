<?php
$servername = "localhost";  // Ou le nom du serveur
$username = "root";         // Nom d'utilisateur de MySQL
$password = "new_password";             // Mot de passe de MySQL (laissez vide si aucun)
$dbname = "coffeee";        // Nom de la base de données

// Créer la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}
?>
