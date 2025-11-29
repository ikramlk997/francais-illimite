<?php
session_start();
require_once 'db_conn.php';

if(!isset($_SESSION['emailu'])){
    header("Location: login.php");
    exit();
}

$connection = getConnection();

$course_id = intval($_GET['id'] ?? 0);
$course_query = mysqli_query($connection, "SELECT * FROM courses WHERE id=$course_id");
$course = mysqli_fetch_assoc($course_query);

$quizzes_query = mysqli_query($connection, "SELECT * FROM quizzes WHERE course_id=$course_id");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= $course['title'] ?></title>
    <link rel="stylesheet" href="css/home.css">
</head>
<body>
    <header>
        <h1><?= $course['title'] ?></h1>
        <nav>
            <a href="home.php">Accueil</a>
            <a href="logout.php">Déconnexion</a>
        </nav>
    </header>

    <main>
        <section class="course-content">
            <h2>Contenu du cours</h2>
            <p><?= nl2br($course['content']) ?></p>
        </section>

        <section class="course-quiz">
            <h2>Quiz du cours</h2>
            <form method="POST" action="submit_quiz.php">
                <?php while($q = mysqli_fetch_assoc($quizzes_query)) { ?>
                    <div class="quiz-question">
                        <p><?= $q['question'] ?></p>
                        <input type="hidden" name="quiz_id[]" value="<?= $q['id'] ?>">
                        <label><input type="radio" name="answer_<?= $q['id'] ?>" value="a" required> <?= $q['option_a'] ?></label><br>
                        <label><input type="radio" name="answer_<?= $q['id'] ?>" value="b"> <?= $q['option_b'] ?></label><br>
                        <label><input type="radio" name="answer_<?= $q['id'] ?>" value="c"> <?= $q['option_c'] ?></label><br>
                        <label><input type="radio" name="answer_<?= $q['id'] ?>" value="d"> <?= $q['option_d'] ?></label><br>
                    </div>
                    <hr>
                <?php } ?>
                <input type="hidden" name="course_id" value="<?= $course_id ?>">
                <input type="submit" name="submit_quiz" value="Soumettre le quiz">
            </form>
        </section>
    </main>
</body>
</html>
