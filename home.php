<?php
$pageTitle = "Accueil - Français Illimité";
include 'header.php';
require_once 'db_conn.php';
$connection = getConnection();
$courses = mysqli_query($connection, "SELECT * FROM courses");
?>

<!-- Message de bienvenue -->
<?php if (isset($_SESSION['nomu'])): ?>
    <div class="alert alert-info text-center">
        Bienvenue <strong><?= htmlspecialchars($_SESSION['nomu']) ?></strong> 
        (mode <strong><?= $_SESSION['mode'] ?? 'utilisateur' ?></strong>)
    </div>
<?php endif; ?>

<!-- Liste des cours -->
<h1 class="mb-4"><span class="highlight">📘 Cours disponibles</span></h1>
<div class="row">
    <?php if (mysqli_num_rows($courses) > 0): ?>
        <?php while ($c = mysqli_fetch_assoc($courses)): ?>
            <div class="col-md-4">
                <div class="card shadow course-card">
                    <div class="card-body">
                        <h5 class="card-title">
                            <?= htmlspecialchars($c['title']) ?> 
                            <small class="text-muted">(<?= htmlspecialchars($c['level']) ?>)</small>
                        </h5>
                        <p class="card-text"><?= nl2br(htmlspecialchars($c['description'])) ?></p>
                        
                        <!-- Boutons -->
                        <a href="course.php?id=<?= urlencode($c['id']) ?>" class="btn btn-primary">📖 Voir le cours</a>
                        <a href="quiz.php?course_id=<?= urlencode($c['id']) ?>" class="btn btn-success">🎯 Faire le quiz</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="text-muted">Aucun cours disponible pour le moment.</p>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
