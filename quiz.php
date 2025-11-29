<?php
$pageTitle = "Quiz - Français Illimité";
include 'header.php';
require_once 'db_conn.php';
$connection = getConnection();

// Vérifier l'ID du cours/quiz
if (!isset($_GET['course_id'])) {
    echo "<div class='alert alert-danger'>Aucun cours sélectionné.</div>";
    include 'footer.php';
    exit();
}

$course_id = intval($_GET['course_id']);
$quizzes = mysqli_query($connection, "SELECT * FROM quizzes WHERE course_id = $course_id");

if (!$quizzes || mysqli_num_rows($quizzes) === 0) {
    echo "<div class='alert alert-warning'>Aucun quiz trouvé pour ce cours.</div>";
    include 'footer.php';
    exit();
}
?>

<h2 class="mb-4">🎯 Quiz du cours <?= $course_id ?></h2>

<form method="post" action="quiz_result.php?course_id=<?= $course_id ?>">
    <?php $i = 1; while ($q = mysqli_fetch_assoc($quizzes)): ?>
        <div class="mb-4">
            <h5>Q<?= $i ?>. <?= htmlspecialchars($q['question']) ?></h5>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="question_<?= $q['id'] ?>" value="a" required>
                <label class="form-check-label"><?= htmlspecialchars($q['option_a']) ?></label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="question_<?= $q['id'] ?>" value="b">
                <label class="form-check-label"><?= htmlspecialchars($q['option_b']) ?></label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="question_<?= $q['id'] ?>" value="c">
                <label class="form-check-label"><?= htmlspecialchars($q['option_c']) ?></label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="question_<?= $q['id'] ?>" value="d">
                <label class="form-check-label"><?= htmlspecialchars($q['option_d']) ?></label>
            </div>
        </div>
    <?php $i++; endwhile; ?>
    <button type="submit" class="btn btn-success">✅ Soumettre le quiz</button>
</form>

<a href="home.php" class="btn btn-secondary mt-3">⬅️ Retour à l'accueil</a>

<?php include 'footer.php'; ?>
