<?php
// Démarrer la session
session_start();
require_once 'config.php'; // Inclure votre fichier de connexion à la base de données

// Vérifier si l'utilisateur est connecté en tant que client
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'client') {
    echo "Accès refusé. Vous devez être connecté en tant que client pour réserver une table.";
    exit();
}

// Récupérer l'ID de l'utilisateur depuis la session
$user_id = $_SESSION['user_id']; // Assurez-vous que l'ID de l'utilisateur est stocké dans la session lors de la connexion

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $numero_de_telephone = $_POST['numero_de_telephone'];
    $jour = $_POST['jour'];
    $heure = $_POST['heure'];
    $nombre_de_personnes = $_POST['nombre_de_personnes'];

    // Validation des champs
    if (empty($nom) || empty($numero_de_telephone) || empty($jour) || empty($heure) || empty($nombre_de_personnes)) {
        echo "Tous les champs sont obligatoires.";
    } else {
        // Insérer les informations dans la base de données
        $sql = "INSERT INTO reservations (nom, numero_de_telephone, jour, heure, nombre_de_personnes, user_id) VALUES (?, ?, ?, ?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            // Lier les paramètres, y compris user_id
            $stmt->bind_param("ssssis", $nom, $numero_de_telephone, $jour, $heure, $nombre_de_personnes, $user_id); // ici, `user_id` est un entier
            if ($stmt->execute()) {
                echo "<script>alert('Réservation effectuée avec succès.');</script>";
            } else {
                echo "Erreur lors de la réservation : " . $conn->error;
            }
            $stmt->close();
        } else {
            echo "Erreur de préparation de la requête : " . $conn->error; // Message d'erreur si la préparation échoue
        }
    }
}

// Fermer la connexion à la base de données
$conn->close();
?>

<!-- Formulaire HTML pour la réservation -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserve a table</title>
    <link rel="stylesheet" href="reservation.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
</head>
<body>

    <section class="contact-section" id="contact">
    
<ul style="position: absolute; top: 15px; left: 15px;">
          <li class="nav-item">
            <a href="myreservations.php" class="nav-link" style="color:#3b141c;padding:7px;border-radius:15px;border:1px #3b141c  solid;background-color:#faf4f5;">My reservations</a>
          </li>
          
</ul>
       
        <form method="post" action="logout.php" style="position: absolute; top: 15px; right: 15px;">
            <button type="submit">Logout</button>
        </form>
     
        <h2 class="section-title">Reserve a table</h2>
        <div class="section-content">
            <form method="post" action="" class="contact-form">
                <input type="text" id="nom" name="nom" class="form-input" placeholder="Nom" required><br>
                <input type="text" id="numero_de_telephone" name="numero_de_telephone" placeholder="Numéro de téléphone" class="form-input" required />
                <input type="date" id="jour" name="jour" required><br><br>
                <input type="time" id="heure" name="heure" class="form-input" required />
                <input type="number" id="nombre_de_personnes" name="nombre_de_personnes" placeholder="Nombre de personnes" class="form-input" required />
                <button type="submit" class="button submit-button">Submit</button>
            </form>
        </div>
    </section>
   

</body>
</html>
