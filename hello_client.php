<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'client') {
    header("Location: login.php");
    exit();
}

echo "Bienvenue, " . $_SESSION['username'] . "! Vous êtes connecté en tant que client.";
?>
