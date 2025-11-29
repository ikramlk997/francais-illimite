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
    $title       = mysqli_real_escape_string($connection, $_POST['title']);
    $description = mysqli_real_escape_string($connection, $_POST['description']);
    $content     = mysqli_real_escape_string($connection, $_POST['content']);
    $pdf         = mysqli_real_escape_string($connection, $_POST['pdf']);
    $video       = mysqli_real_escape_string($connection, $_POST['video']);
    $level       = mysqli_real_escape_string($connection, $_POST['level']);

    $query = "INSERT INTO courses (title, description, content, pdf, video, level) 
              VALUES ('$title', '$description', '$content', '$pdf', '$video', '$level')";

    if (mysqli_query($connection, $query)) {
        echo "<p style='color:green;'>✅ Cours ajouté avec succès !</p>";
        echo "<p><a href='index.php'>Retour à la liste des cours</a></p>";
    } else {
        echo "<p style='color:red;'>❌ Erreur : " . mysqli_error($connection) . "</p>";
    }
}
?>

<h1>➕ Ajouter un cours</h1>

<style>
form {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    max-width: 600px;
    margin: 20px auto;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

form label {
    font-weight: bold;
    color: #333;
}

form input[type="text"],
form textarea,
form select {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 6px;
}

form input[type="submit"] {
    background: #2196f3;
    color: #fff;
    border: none;
    padding: 12px 20px;
    border-radius: 6px;
    cursor: pointer;
    transition: background 0.3s ease;
}

form input[type="submit"]:hover {
    background: #1976d2;
}
</style>

<form method="POST">
    <label>Titre :</label><br>
    <input type="text" name="title" required><br><br>

    <label>Description :</label><br>
    <textarea name="description" rows="4" cols="50" required></textarea><br><br>

    <label>Contenu :</label><br>
    <textarea name="content" rows="6" cols="50"></textarea><br><br>

    <label>PDF (lien ou chemin) :</label><br>
    <input type="text" name="pdf"><br><br>

    <label>Vidéo (lien YouTube ou fichier) :</label><br>
    <input type="text" name="video"><br><br>

    <label>Niveau :</label><br>
    <select name="level">
        <option value="Débutant">Débutant</option>
        <option value="Intermédiaire">Intermédiaire</option>
        <option value="Avancé">Avancé</option>
    </select><br><br>

    <input type="submit" value="Ajouter le cours">
</form>
