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

// Suppression après confirmation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
        $delete = "DELETE FROM courses WHERE id=$id";
        if (mysqli_query($connection, $delete)) {
            echo "<p style='color:green;'>✅ Cours supprimé avec succès !</p>";
            echo "<p><a href='index.php'>Retour à la liste des cours</a></p>";
        } else {
            echo "<p style='color:red;'>❌ Erreur : " . mysqli_error($connection) . "</p>";
        }
    } else {
        echo "<p>Suppression annulée.</p>";
        echo "<p><a href='index.php'>Retour à la liste des cours</a></p>";
    }
    exit();
}
?>
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

<h1>🗑️ Supprimer un cours</h1>
<p><strong>Titre :</strong> <?= htmlspecialchars($course['title']) ?></p>
<p><strong>Niveau :</strong> <?= htmlspecialchars($course['level']) ?></p>
<p><strong>Description :</strong> <?= htmlspecialchars($course['description']) ?></p>

<form method="POST">
    <p style="color:red;">⚠️ Êtes-vous sûr de vouloir supprimer ce cours ?  
    <br>Les quiz associés seront également supprimés.</p>
    <button type="submit" name="confirm" value="yes">Oui, supprimer</button>
    <button type="submit" name="confirm" value="no">Non, annuler</button>
</form>
