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

// Vérifier si un ID est passé
if (!isset($_GET['id'])) {
    echo "<p>Question introuvable.</p>";
    exit();
}

$id = intval($_GET['id']);
$query = mysqli_query($connection, "
    SELECT q.id, q.question, c.title 
    FROM quizzes q 
    JOIN courses c ON q.course_id = c.id
    WHERE q.id=$id
");

if (mysqli_num_rows($query) == 0) {
    echo "<p>Question introuvable.</p>";
    exit();
}

$quiz = mysqli_fetch_assoc($query);

// Suppression après confirmation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
        $delete = "DELETE FROM quizzes WHERE id=$id";
        if (mysqli_query($connection, $delete)) {
            echo "<p style='color:green;'>✅ Question supprimée avec succès !</p>";
            echo "<p><a href='index.php'>Retour à la liste des quiz</a></p>";
        } else {
            echo "<p style='color:red;'>❌ Erreur : " . mysqli_error($connection) . "</p>";
        }
    } else {
        echo "<p>Suppression annulée.</p>";
        echo "<p><a href='index.php'>Retour à la liste des quiz</a></p>";
    }
    exit();
}
?>

<h1>🗑️ Supprimer une question</h1>
<style>
form {
    margin-top: 20px;
}
form button {
    background: #f44336;
    color: #fff;
    border: none;
    padding: 10px 18px;
    border-radius: 6px;
    cursor: pointer;
    margin-right: 10px;
    transition: background 0.3s ease;
}
form button[value="no"] {
    background: #9e9e9e;
}
form button:hover {
    opacity: 0.9;
}
</style>

<p><strong>Cours :</strong> <?= htmlspecialchars($quiz['title']) ?></p>
<p><strong>Question :</strong> <?= htmlspecialchars($quiz['question']) ?></p>

<form method="POST">
    <p style="color:red;">⚠️ Êtes-vous sûr de vouloir supprimer cette question ?</p>
    <button type="submit" name="confirm" value="yes">Oui, supprimer</button>
    <button type="submit" name="confirm" value="no">Non, annuler</button>
</form>
