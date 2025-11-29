<?php
$pageTitle = "Gestion des Cours - Administration";
include __DIR__ . '/../../header.php';
require_once __DIR__ . '/../../db_conn.php';
$connection = getConnection();

$courses = mysqli_query($connection, "SELECT * FROM courses");
?>

<h1 class="mb-4"><span class="highlight">📘 Liste des cours</span></h1>
<div class="row">
    <?php if (mysqli_num_rows($courses) > 0): ?>
        <?php while ($course = mysqli_fetch_assoc($courses)): ?>
            <div class="col-md-4">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($course['title']) ?></h5>
                        <p class="card-text"><?= nl2br(htmlspecialchars($course['description'])) ?></p>
                        <a href="../../course.php?id=<?= urlencode($course['id']) ?>" class="btn btn-primary">📖 Voir le cours</a>
                        <a href="../../quiz.php?course_id=<?= urlencode($course['id']) ?>" class="btn btn-success">🎯 Faire le quiz</a>
                        <a href="edit_course.php?id=<?= $course['id'] ?>" class="btn btn-warning btn-sm">✏️ Modifier</a>
                        <a href="delete-course.php?id=<?= $course['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer ce cours ?')">🗑️ Supprimer</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="text-muted">Aucun cours disponible.</p>
    <?php endif; ?>
</div>

<a href="add_course.php" class="btn btn-success mt-3">➕ Ajouter un cours</a>
<a href="../index.php" class="btn btn-secondary mt-3">⬅️ Retour au tableau de bord</a>

<?php include __DIR__ . '/../footer.php'; ?>
