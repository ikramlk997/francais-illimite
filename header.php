<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) : "Français Illimité" ?></title>
    <link rel="stylesheet" href="css/home.css">
    <link rel="icon" type="image/png" href="css/images/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<header>
    <!-- ✅ Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
      <div class="container-fluid">
        <a class="navbar-brand" href="home.php">Français Illimité</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                data-bs-target="#navbarNav" aria-controls="navbarNav" 
                aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link" href="home.php">🏠 Accueil</a></li>
            <li class="nav-item"><a class="nav-link" href="course.php">📘 Cours</a></li>
            <li class="nav-item"><a class="nav-link" href="quiz.php">🎯 Quiz</a></li>
            
            <!-- ✅ Bouton Mon Profil (visible si connecté) -->
            <?php if (isset($_SESSION["idu"])): ?>
                <li class="nav-item"><a class="nav-link" href="profile.php">👤 Mon Profil</a></li>
            <?php endif; ?>

            <?php if (isset($_SESSION["is_admin"]) && $_SESSION["is_admin"] == 1): ?>
                <li class="nav-item"><a class="nav-link" href="admin/admin.php">⚙️ Administration</a></li>
            <?php endif; ?>
            
            <li class="nav-item"><a class="nav-link" href="logout.php">🚪 Déconnexion</a></li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- ✅ Message utilisateur -->
    <div class="user-info text-center mt-3">
        <?php if (isset($_SESSION["nomu"])): ?>
            <h2>Bonjour, <?= htmlspecialchars($_SESSION["nomu"] . " " . ($_SESSION["prenomu"] ?? "")) ?></h2>
        <?php endif; ?>
    </div>
</header>

<main class="container fade-in mt-4">
