<?php
$pageTitle = "Cours - Français Illimité";
include 'header.php';
require_once 'db_conn.php';
$connection = getConnection();

// Récupérer l'ID du cours depuis l'URL
if (!isset($_GET['id'])) {
    echo "<div class='alert alert-danger'>Aucun cours sélectionné.</div>";
    include 'footer.php';
    exit();
}

$course_id = intval($_GET['id']);
$course = mysqli_query($connection, "SELECT * FROM courses WHERE id = $course_id");
$c = mysqli_fetch_assoc($course);

if (!$c) {
    echo "<div class='alert alert-warning'>Cours introuvable.</div>";
    include 'footer.php';
    exit();
}
?>

<!-- ✅ Affichage du cours -->
<div class="card shadow mb-4">
    <div class="card-body">
        <h2 class="card-title"><?= htmlspecialchars($c['title']) ?> 
            <small class="text-muted">(<?= htmlspecialchars($c['level']) ?>)</small>
        </h2>
        <p class="card-text"><?= nl2br(htmlspecialchars($c['description'])) ?></p>
    </div>
</div>

<!-- ✅ Bouton retour -->
<a href="home.php" class="btn btn-secondary">⬅️ Retour à l'accueil</a>

<?php include 'footer.php'; ?>
