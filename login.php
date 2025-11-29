<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'db_conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $md5_pass = md5($password);

    $conn = getConnection();

    // Vérifier utilisateur
    $query = "SELECT id, nom, prenom, email, md5_pass, is_admin 
              FROM registerform 
              WHERE email = '$email' AND md5_pass = '$md5_pass'";

    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        // Stocker infos en session
        $_SESSION["idu"]      = $user['id'];
        $_SESSION["nomu"]     = $user['nom'];
        $_SESSION["prenomu"]  = $user['prenom'];
        $_SESSION["emailu"]   = $user['email'];
        $_SESSION["is_admin"] = $user['is_admin'];

        // Redirection selon rôle
        if ($user['is_admin'] == 1) {
            header("Location: admin/admin.php"); // tableau de bord admin
        } else {
            header("Location: home.php"); // accueil utilisateur normal
        }
        exit();
    } else {
        echo '<script>alert("Email ou mot de passe incorrect !");</script>';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <img src="css/images/deletedback/Français_illimité-removebg-preview.png" alt="logo">
    <div class="center">
        <br>
        <h1>Connexion</h1>
        <form method="POST">
            <div class="text_field">
                <input type="text" name="email" required>
                <span></span>
                <label>Email</label>
            </div>
            <div class="text_field">
                <input type="password" name="password" required>
                <label>Mot de passe</label>
            </div>
            <div class="pass">Mot de passe oublié ?</div>
            <input type="submit" value="Connexion">
            <div class="signup_link">
                Vous n'êtes pas membre ?  
                <a href="register.php">S'inscrire</a>
            </div>
        </form>
    </div>
</body>
</html>
