<?php
$pageTitle = "Tableau de bord - Français Illimité";
include 'header.php';

require_once 'db_conn.php';
$connection = getConnection();

$user_id = $_SESSION['idu']; // utiliser la même clé que login.php

// Nombre total de cours
$courses = mysqli_query($connection, "SELECT COUNT(*) as total FROM courses");
$totalCourses = mysqli_fetch_assoc($courses)['total'];

// Nombre de cours suivis par l’utilisateur
$userCourses = mysqli_query($connection, "SELECT COUNT(*) as suivi FROM user_courses WHERE user_id=$user_id");
$suiviCourses = mysqli_fetch_assoc($userCourses)['suivi'];

$progression = $totalCourses > 0 ? round(($suiviCourses / $totalCourses) * 100, 2) : 0;
?>

<div class="dashboard">
    <h1>Bienvenue <?= htmlspecialchars($_SESSION['nomu']) ?> 👋</h1>
    <div class="stats">
        <div class="card">
            <h2>📘 Cours suivis</h2>
            <p><?= $suiviCourses ?> / <?= $totalCourses ?> (<?= $progression ?>%)</p>
            <div class="progress-bar"><span style="width:<?= $progression ?>%"></span></div>
        </div>
        <div class="card">
            <h2>🎯 Résultats Quiz</h2>
            <p>Consultez vos scores dans <a href="profile.php">votre profil</a></p>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
