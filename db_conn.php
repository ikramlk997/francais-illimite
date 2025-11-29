<?php

function getConnection() {
    $host = "localhost";
    $user = "root";
    $password = "";
    $db = "francais_illimite";

    $connexion = mysqli_connect($host, $user, $password, $db);

    if (!$connexion) {
        die("Erreur de connexion à la base de données: " . mysqli_connect_error());
    }

    return $connexion;
}
