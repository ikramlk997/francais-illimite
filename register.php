<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<?php
require_once 'db_conn.php';

if (isset($_POST['submit'])) {

    $nomR       = strtoupper(trim($_POST['nom']));
    $prenomR    = strtolower(trim($_POST['prenom']));
    $emailR     = trim($_POST['email']);
    $passwordR  = trim($_POST['password']);
    $newPass    = trim($_POST['newpassword']);
    $md5_pass   = md5($passwordR);

    $conn = getConnection();

    // Vérifier email unique
    $check = mysqli_query($conn, "SELECT * FROM registerform WHERE email = '$emailR'");
    if (mysqli_num_rows($check) > 0) {
        echo '<script>alert("Cet email existe déjà !");</script>';
    } elseif ($passwordR !== $newPass) {
        echo '<script>alert("Les mots de passe ne sont pas identiques !");</script>';
    } else {
        $query = "INSERT INTO registerform(nom, prenom, email, password, md5_pass)
                  VALUES ('$nomR', '$prenomR', '$emailR', '$passwordR', '$md5_pass')";

        if (mysqli_query($conn, $query)) {
            echo '<script>alert("Inscription réussie !");</script>';
        } else {
            echo '<script>alert("Erreur lors de l\'inscription.");</script>';
        }
    }
}
?>
<!DOCTYPE html> 
<head> 
    <meta charset="utf-8"> 
    <title>Inscription</title> 
    <link rel="stylesheet" href="css/register.css"> 
</head> 
<body> 
    <img src="css/images/deletedback/Français_illimité-removebg-preview.png" alt="logo"> 
    <div class="center"> <br> 
    <h1>Inscription</h1> 
    <form method="post"> 
        <div class="text_field"> 
            <input type="text" name="nom" required> <span></span> 
            <label>Nom</label> 
        </div> 
            <div class="text_field"> 
                <input type="text" name="prenom" required> <span></span> 
                <label>Prénom</label> 
            </div> 
            <div class="text_field"> 
                <input type="text" name="email" required> <span></span> 
                <label>Email</label> 
            </div> 
            <div class="text_field"> 
                    <input type="password" name="password" required> 
                    <label>Mot de passe</label> 
            </div> 
            <div class="text_field"> 
                    <input type="password" name="newpassword" required> 
                    <label>Confirmer le mot de passe</label> 
            </div> <input type="submit" name="submit" value="S'inscrire"> 
            <div class="signin_link"> 
                <a href="/français-illimite/login.php"> <--- Aller pour connecter </a> 
            </div> 
        </form> 
    </div> 
</body> 
</html>
