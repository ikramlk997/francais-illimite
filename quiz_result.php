<?php
$pageTitle = "Résultats du Quiz - Français Illimité";
include 'header.php';
require_once 'db_conn.php';
$connection = getConnection();

if (!isset($_GET['course_id'])) {
    echo "<div class='alert alert-danger'>Aucun cours sélectionné.</div>";
    include 'footer.php';
    exit();
}

$course_id = intval($_GET['course_id']);
$quizzes = mysqli_query($connection, "SELECT * FROM quizzes WHERE course_id = $course_id");

$score = 0;
$total = mysqli_num_rows($quizzes);
?>

<h2 class="mb-4">🎯 Résultats du quiz du cours <?= $course_id ?></h2>

<ul class="list-group mb-4">
<?php while ($q = mysqli_fetch_assoc($quizzes)): ?>
    <?php
    $userAnswer = $_POST['question_' . $q['id']] ?? null;
    $isCorrect = ($userAnswer === $q['correct_option']);
    if ($isCorrect) $score++;
    ?>
    <li class="list-group-item">
        <strong><?= htmlspecialchars($q['question']) ?></strong><br>
        Votre réponse : <?= $userAnswer ? htmlspecialchars($q['option_' . $userAnswer]) : "<em>Aucune</em>" ?><br>
        ✅ Réponse correcte : <?= htmlspecialchars($q['option_' . $q['correct_option']]) ?><br>
        <?= $isCorrect ? "<span class='text-success'>✔️ Correct</span>" : "<span class='text-danger'>❌ Incorrect</span>" ?>
    </li>
<?php endwhile; ?>
</ul>

<?php
// ✅ Enregistrer le résultat du quiz (une seule fois après calcul)
if (isset($_SESSION['idu'])) {
    $user_id = intval($_SESSION['idu']);
    $quiz_id = $course_id;
    $score_percent = $total > 0 ? round(($score / $total) * 100) : 0;

    mysqli_query($connection, "
        INSERT INTO quiz_results (user_id, quiz_id, score) 
        VALUES ($user_id, $quiz_id, $score_percent)
    ");
}
?>

<div class="alert alert-info text-center">
    Score final : <strong><?= $score ?>/<?= $total ?></strong> (<?= $score_percent ?>%)
</div>

<div class="text-center mt-3">
    <a href="home.php" class="btn btn-secondary">⬅️ Retour à l'accueil</a>
    <a href="profile.php" class="btn btn-primary">👤 Voir mes statistiques</a>
</div>

<?php include 'footer.php'; ?>
