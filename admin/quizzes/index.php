<?php
$pageTitle = "Gestion des Quiz - Administration";
include __DIR__ . '/../header.php';
require_once __DIR__ . '/../../db_conn.php';
$connection = getConnection();

$quizzes = mysqli_query($connection, "SELECT * FROM quizzes");
?>

<h1 class="mb-4"><span class="highlight">🎯 Liste des quiz</span></h1>
<div class="row">
    <?php if (mysqli_num_rows($quizzes) > 0): ?>
        <?php while ($quiz = mysqli_fetch_assoc($quizzes)): ?>
            <div class="col-md-6">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($quiz['question']) ?></h5>
                        <p class="card-text">Cours lié : <?= htmlspecialchars($quiz['course_id']) ?></p>
                        <a href="../../quiz.php?course_id=<?= urlencode($quiz['course_id']) ?>" class="btn btn-success">🚀 Commencer</a>
                        <a href="edit_question.php?id=<?= $quiz['id'] ?>" class="btn btn-warning btn-sm">✏️ Modifier</a>
                        <a href="delete_question.php?id=<?= $quiz['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer cette question ?')">🗑️ Supprimer</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="text-muted">Aucun quiz disponible.</p>
    <?php endif; ?>
</div>

<a href="add_question.php" class="btn btn-success mt-3">➕ Ajouter une question</a>
<a href="../index.php" class="btn btn-secondary mt-3">⬅️ Retour au tableau de bord</a>

<?php include __DIR__ . '/../footer.php'; ?>
