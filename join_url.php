<?php
session_start();
$db = mysqli_connect('localhost', 'root', 'root', 'planningpoker');

// diese Datei wird aufgerufen, sobald einer Session über ein Link beigetreten wird

if (!isset($_SESSION['email'])) {
    $error = "Bitte melde dich zuerst an.";
    mysqli_close($db);
    header("Location: login_view.php?login_first=$error");
    die();
}

// überprüfen, ob die Id und der Name der Session mitgegeben wird
if (isset($_GET['session_name']) && isset($_GET['session_id'])) {
    $id = htmlspecialchars($_GET['session_id']);
    $user_id = $_SESSION['user_id'];
    $_SESSION['session_name'] = htmlspecialchars($_GET['session_name']);
    $_SESSION['session_id'] = htmlspecialchars($_GET['session_id']);
} else {
    mysqli_close($db);
    $error_mistake = "Ein Fehler ist aufgetreten. Ungültige Session_Id oder Session_Name";
    header("Location: session_view.php?error_join=$error_mistake");
    die();
}
$query = "SELECT tmp_userstory FROM session WHERE id = '$id'";
$result = mysqli_query($db, $query);
$story = mysqli_fetch_assoc($result);
// Es kann erst beigetreten werden, sobald die erste Userstory gesetzt ist
if (!empty($story['tmp_userstory'])) {
    $_SESSION['task'] = $story['tmp_userstory'];
}
else{
    mysqli_close($db);
    $error_mistake = "Ungültig. Story nicht gesetzt";
    header("Location: session_view.php?error_join=$error_mistake");
    die();
}
$query = "INSERT INTO session_users (session_id, user_id) VALUES('$id', '$user_id')";
mysqli_query($db, $query);
mysqli_close($db);
header("Location: poker_view.php");
die();
