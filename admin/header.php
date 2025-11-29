<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../db_conn.php';
$connection = getConnection();

// Vérification accès admin
if (empty($_SESSION['idu'])) {
    header("Location: /francais-illimite/login.php");
    exit;
}
if (empty($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    echo "<p style='color:red; text-align:center;'>⛔ Accès refusé. Section réservée aux administrateurs.</p>";
    exit;
}

// Initialiser le mode
$_SESSION['mode'] = $_SESSION['mode'] ?? "admin";
$basePath = "/francais-illimite/admin/";

// Gestion du changement de mode
if (!empty($_POST['switch_mode'])) {
    $_SESSION['mode'] = $_POST['switch_mode'];
    $redirect = ($_SESSION['mode'] === "admin") ? "{$basePath}admin.php" : "/francais-illimite/home.php";
    header("Location: $redirect");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Administration - Français Illimité</title>
    <link rel="stylesheet" href="../css/admin.css?v=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <nav class="admin-header">
        <div class="logo"><i class="fas fa-cogs"></i> Français Illimité - Admin</div>
        <span class="menu-toggle" id="menuToggle"><i class="fas fa-bars"></i></span>
        <ul id="adminMenu">
            <li><a href="<?= $basePath ?>admin.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="<?= $basePath ?>courses/index.php"><i class="fas fa-book"></i> Cours</a></li>
            <li><a href="<?= $basePath ?>quizzes/index.php"><i class="fas fa-question-circle"></i> Quiz</a></li>
            <li><a href="<?= $basePath ?>users/index.php"><i class="fas fa-users"></i> Utilisateurs</a></li>
            <li><a href="/francais-illimite/logout.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
        </ul>
    </nav>
    <main class="main-content">
