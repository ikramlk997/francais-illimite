<?php
session_start();
require_once '../../db_conn.php';
$connection = getConnection();

if (!isset($_SESSION['idu'])) {
    header("Location: ../login.php");
    exit();
}

$id = intval($_GET['id']);
mysqli_query($connection, "DELETE FROM registerform WHERE id=$id");

header("Location: index.php");
exit();
