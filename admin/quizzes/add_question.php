<?php
session_start();
require_once '../../db_conn.php';
$connection = getConnection();

// Vérifier si admin
if (!isset($_SESSION['idu'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['idu'];
$isAdminQuery = mysqli_query($connection, "SELECT is_admin FROM registerform WHERE id=$user_id");
$isAdmin = mysqli_fetch_assoc($isAdminQuery)['is_admin'];

if ($isAdmin != 1) {
    echo "<p>Accès refusé.</p>";
    exit();
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_id     = intval($_POST['course_id']);
    $question      = mysqli_real_escape_string($connection, $_POST['question']);
    $option_a      = mysqli_real_escape_string($connection, $_POST['option_a']);
    $option_b      = mysqli_real_escape_string($connection, $_POST['option_b']);
    $option_c      = mysqli_real_escape_string($connection, $_POST['option_c']);
    $option_d      = mysqli_real_escape_string($connection, $_POST['option_d']);
    $correct_option= mysqli_real_escape_string($connection, $_POST['correct_option']);

    $query = "INSERT INTO quizzes (course_id, question, option_a, option_b, option_c, option_d, correct_option) 
              VALUES ($course_id, '$question', '$option_a', '$option_b', '$option_c', '$option_d', '$correct_option')";

    if (mysqli_query($connection, $query)) {
        echo "<p style='color:green;'>✅ Question ajoutée avec succès !</p>";
        echo "<p><a href='index.php'>Retour à la liste des quiz</a></p>";
    } else {
        echo "<p style='color:red;'>❌ Erreur : " . mysqli_error($connection) . "</p>";
    }
}
?>

<h1>➕ Ajouter une question de quiz</h1>
<style>
form {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    max-width: 600px;
    margin: 20px auto;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
form label { font-weight: bold; color: #333; }
form input[type="text"], form textarea, form select {
    width: 100%; padding: 10px; margin-top: 5px; margin-bottom: 15px;
    border: 1px solid #ccc; border-radius: 6px;
}
form input[type="submit"] {
    background: #2196f3; color: #fff; border: none; padding: 12px 20px;
    border-radius: 6px; cursor: pointer; transition: background 0.3s ease;
}
form input[type="submit"]:hover { background: #1976d2; }
</style>

<form method="POST">
    <label>Cours associé :</label><br>
    <select name="course_id" required>
        <?php
        $courses = mysqli_query($connection, "SELECT id, title FROM courses");
        while ($c = mysqli_fetch_assoc($courses)) {
            echo "<option value='{$c['id']}'>".htmlspecialchars($c['title'])."</option>";
        }
        ?>
    </select><br><br>

    <label>Question :</label><br>
    <textarea name="question" rows="4" cols="50" required></textarea><br><br>

    <label>Option A :</label><br>
    <input type="text" name="option_a" required><br><br>

    <label>Option B :</label><br>
    <input type="text" name="option_b" required><br><br>

    <label>Option C :</label><br>
    <input type="text" name="option_c" required><br><br>

    <label>Option D :</label><br>
    <input type="text" name="option_d" required><br><br>

    <label>Bonne réponse :</label><br>
    <select name="correct_option" required>
        <option value="a">Option A</option>
        <option value="b">Option B</option>
        <option value="c">Option C</option>
        <option value="d">Option D</option>
    </select><br><br>

    <input type="submit" value="Ajouter la question">
</form>
