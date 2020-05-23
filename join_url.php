<?php
session_start();
$db = mysqli_connect('localhost', 'root', 'root', 'planningpoker');

if (isset($_GET['session_name']) && isset($_GET['session_id'])) {
    $id = $_GET['session_id'];
    $_SESSION['session_name'] = htmlspecialchars($_GET['session_name']);
    $_SESSION['session_id'] = htmlspecialchars($_GET['session_id']);
} else {
    $error_mistake = "Ein Fehler ist aufgetreten. Ungültige Session_Id oder Session_Name";
    header("Location: index_view.php?error=$error_mistake");
    die();
}
$query = "SELECT tmp_userstory FROM session WHERE id = '$id'";
$result = mysqli_query($db, $query);
$story = mysqli_fetch_assoc($result);
if (!empty($story['tmp_userstory'])) {
    $_SESSION['task'] = $story['tmp_userstory'];
}
else{
    $error_mistake = "Ungültige Session_Id/Session_Name oder Story nicht gesetzt";
    header("Location: index_view.php?error=$error_mistake");
    die();
}
header("Location: poker_view.php");
die();
