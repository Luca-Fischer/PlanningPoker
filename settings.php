<?php
session_start();

$db = mysqli_connect('localhost', 'root', 'root', 'planningpoker');

if (isset($_POST['delete_user_final'])) {
    delete_user($db);
}
// Es werden alle Zeilen gelöscht, in der der User einen Eintrag hat
function delete_user($db) {
    $value = $_SESSION['user_id'];
    $query = "DELETE FROM users WHERE id = '$value'";
    mysqli_query($db, $query);

    $query = "DELETE FROM session WHERE user_id = '$value'";
    mysqli_query($db, $query);

    $query = "DELETE FROM session_user WHERE user_id = '$value'";
    mysqli_query($db, $query);

    $query = "DELETE FROM storypoints WHERE user_id = '$value'";
    mysqli_query($db, $query);

    mysqli_close($db);
    session_destroy();
    unset($_SESSION['email']);
    $info = "Benutzeraccount erfolgreich gelöscht";
    header("Location: register_view.php?success=$info");
    die();
}
