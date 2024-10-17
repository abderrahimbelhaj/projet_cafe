<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once 'config.php'; // Assurez-vous que ce fichier configure correctement la connexion à la base de données

// Vérifier si l'utilisateur est connecté et est un admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    echo "Access denied. You must be an admin to view this page.";
    exit();
}

// Mettre à jour le statut de la réservation si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reservation_id']) && isset($_POST['decision'])) {
    $reservation_id = intval($_POST['reservation_id']);
    $decision = $_POST['decision'] === 'accepted' ? 'accepté' : 'rejeté';
    
    // Préparer et exécuter la requête pour mettre à jour le statut
    $stmt = $conn->prepare("UPDATE reservations SET decision = ? WHERE id = ?");
    $stmt->bind_param('si', $decision, $reservation_id); // 'si' signifie string et integer
    if ($stmt->execute()) {
        echo "La décision a été mise à jour avec succès.";
    } else {
        echo "Erreur lors de la mise à jour de la décision : " . $stmt->error;
    }
    $stmt->close();

    // Rediriger pour éviter la soumission multiple du formulaire
    header("Location: list_reservation.php");
    exit();
}

// Requête pour récupérer toutes les réservations
$sql = "SELECT id, nom, numero_de_telephone, jour, heure, nombre_de_personnes, decision FROM reservations";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservations List</title>
    <link rel="stylesheet" href="manage_products.css">
    <style>
        .accepted {
            background-color: green;
            color: white;
        }
        .rejected {
            background-color: red;
            color: white;
        }
        .pending {
            background-color: #3b141c; /* Couleur marron */
            color: white;
        }
    </style>
</head>
<body>
    <header>
        <a href="hello_admin.php" class="nav-link">Home</a>
    </header>
    <div class="reservation-container">
        <h2>List of Reservations</h2>
        <table>
            <tr>
                <th>Name</th>
                <th>Phone Number</th>
                <th>Day</th>
                <th>Time</th>
                <th>Number of People</th>
                <th>Decision</th>
            </tr>
            <?php
            // Vérifier si des réservations existent
            if ($result->num_rows > 0) {
                // Afficher chaque réservation dans une ligne de tableau
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['nom']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['numero_de_telephone']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['jour']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['heure']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['nombre_de_personnes']) . "</td>";

                    // Classes CSS pour les boutons et le statut
                    $acceptClass = $row['decision'] === 'accepté' ? 'accepted' : 'pending';
                    $rejectClass = $row['decision'] === 'rejeté' ? 'rejected' : 'pending';

                    echo "<td>
                        <form method='post' action='list_reservation.php'>
                            <input type='hidden' name='reservation_id' value='" . htmlspecialchars($row['id']) . "' />";

                    // Afficher les boutons seulement si la décision est encore "pending"
                    if ($row['decision'] === 'pending') {
                        echo "<button type='submit' name='decision' value='accepted' class='$acceptClass'>Accept</button>
                              <button type='submit' name='decision' value='rejected' class='$rejectClass'>Reject</button>";
                    } else {
                        echo "<span class='{$acceptClass} {$rejectClass}'>" . ucfirst($row['decision']) . "</span>";
                    }

                    echo "</form></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No reservations found.</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>
