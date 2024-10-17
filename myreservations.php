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



// Préparer la requête pour récupérer les réservations de l'utilisateur connecté
$sql = "SELECT nom, numero_de_telephone, jour, nombre_de_personnes, heure, decision FROM reservations WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Réservations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color:#faf4f5;">

<ul style="position: absolute; top: 10px; left: 0px;list-style: none;">
          <li class="nav-item">
            <a href="reservation.php" class="nav-link" style="color:#3b141c;;padding:7px 50px;border-radius:15px;border:1px #3b141c  solid;background-color:#faf4f5;">Home</a>
          </li>
          
</ul>


    <div class="container mt-5">
        <h2 style="text-align:center ; margin-bottom:25px;">My Reservations</h2>
        <table class="table table-bordered">
            <thead >
                <tr>
                    <th>Name</th>
                   
                    <th>Phone number</th>
                    <th>Date</th>
                    <th>Hour</th>
                    <th>Number of person</th>
                    <th>Statuts</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['nom']); ?></td>
                       
                        <td><?php echo htmlspecialchars($row['numero_de_telephone']); ?></td>
                        <td><?php echo htmlspecialchars($row['jour']); ?></td>
                        <td><?php echo htmlspecialchars($row['heure']); ?></td>
                        <td><?php echo htmlspecialchars($row['nombre_de_personnes']); ?></td>
                        <td><?php echo htmlspecialchars($row['decision']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
