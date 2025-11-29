<?php
session_start();
require_once '../../db_conn.php';
$connection = getConnection();

if (!isset($_SESSION['idu'])) {
    header("Location: ../login.php");
    exit();
}

$id = intval($_GET['id']);
$query = mysqli_query($connection, "SELECT * FROM registerform WHERE id=$id");
$user = mysqli_fetch_assoc($query);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomu = mysqli_real_escape_string($connection, $_POST['nomu']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $is_admin = isset($_POST['is_admin']) ? 1 : 0;

    mysqli_query($connection, "UPDATE registerform SET nomu='$nomu', email='$email', is_admin='$is_admin' WHERE id=$id");
    header("Location: index.php");
    exit();
}
?>

<?php include '../header.php'; ?>

<h1>✏️ Modifier utilisateur</h1>
<form method="POST">
    <label>Nom :</label><br>
    <input type="text" name="nomu" value="<?= htmlspecialchars($user['nomu']) ?>"><br><br>

    <label>Email :</label><br>
    <input type="text" name="email" value="<?= htmlspecialchars($user['email']) ?>"><br><br>

    <label>Admin :</label>
    <input type="checkbox" name="is_admin" <?= $user['is_admin'] ? 'checked' : '' ?>><br><br>

    <input type="submit" value="Mettre à jour">
</form>

<?php include '../footer.php'; ?>
