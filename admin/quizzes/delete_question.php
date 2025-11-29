<?php
session_start();
require_once '../../db_conn.php';

if(!isset($_SESSION['emailu']) || $_SESSION['is_admin'] != 1){
    header("Location: ../../login.php");
    exit();
}

if(!isset($_GET['id'])){
    header("Location: index.php");
    exit();
}

$question_id = intval($_GET['id']);
$connection = getConnection();

// Récupérer course_id pour redirection
$res = mysqli_query($connection, "SELECT course_id FROM quizzes WHERE id=$question_id");
$course = mysqli_fetch_assoc($res);

mysqli_query($connection, "DELETE FROM quizzes WHERE id=$question_id");

header("Location: index.php?course_id=".$course['course_id']);
exit();
