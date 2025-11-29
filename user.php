<?php
session_start();

if (!isset($_SESSION["emailu"])) {
    header("Location: login.php");
    exit();
}

echo "Nom : " . $_SESSION["nomu"] . "<br>";
echo "Prénom : " . $_SESSION["prenomu"] . "<br>";
echo "Email : " . $_SESSION["emailu"] . "<br>";
