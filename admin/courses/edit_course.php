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
    echo "<p>Cours introuvable.</p>";
    exit();
}

$id = intval($_GET['id']);
$query = mysqli_query($connection, "SELECT * FROM courses WHERE id=$id");

if (mysqli_num_rows($query) == 0) {
    echo "<p>Cours introuvable.</p>";
    exit();
}

$course = mysqli_fetch_assoc($query);

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = mysqli_real_escape_string($connection, $_POST['title']);
    $description = mysqli_real_escape_string($connection, $_POST['description']);
    $content     = mysqli_real_escape_string($connection, $_POST['content']);
    $pdf         = mysqli_real_escape_string($connection, $_POST['pdf']);
    $video       = mysqli_real_escape_string($connection, $_POST['video']);
    $level       = mysqli_real_escape_string($connection, $_POST['level']);

    $update = "UPDATE courses 
               SET title='$title', description='$description', content='$content', 
                   pdf='$pdf', video='$video', level='$level'
               WHERE id=$id";

    if (mysqli_query($connection, $update)) {
        echo "<p style='color:green;'>✅ Cours mis à jour avec succès !</p>";
        echo "<p><a href='index.php'>Retour à la liste des cours</a></p>";
    } else {
        echo "<p style='color:red;'>❌ Erreur : " . mysqli_error($connection) . "</p>";
    }
}
?>
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

<h1>✏️ Modifier un cours</h1>
<form method="POST">
    <label>Titre :</label><br>
    <input type="text" name="title" value="<?= htmlspecialchars($course['title']) ?>" required><br><br>

    <label>Description :</label><br>
    <textarea name="description" rows="4" cols="50" required><?= htmlspecialchars($course['description']) ?></textarea><br><br>

    <label>Contenu :</label><br>
    <textarea name="content" rows="6" cols="50"><?= htmlspecialchars($course['content']) ?></textarea><br><br>

    <label>PDF (lien ou chemin) :</label><br>
    <input type="text" name="pdf" value="<?= htmlspecialchars($course['pdf']) ?>"><br><br>

    <label>Vidéo (lien YouTube ou fichier) :</label><br>
    <input type="text" name="video" value="<?= htmlspecialchars($course['video']) ?>"><br><br>

    <label>Niveau :</label><br>
    <select name="level">
        <option value="Débutant" <?= $course['level']=='Débutant'?'selected':'' ?>>Débutant</option>
        <option value="Intermédiaire" <?= $course['level']=='Intermédiaire'?'selected':'' ?>>Intermédiaire</option>
        <option value="Avancé" <?= $course['level']=='Avancé'?'selected':'' ?>>Avancé</option>
    </select><br><br>

    <input type="submit" value="Mettre à jour">
</form>
