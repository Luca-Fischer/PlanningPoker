<?php
session_start();

$db = mysqli_connect('localhost', 'root', 'root', 'planningpoker');

echo "<pre>";
print_r($_POST);
echo "</pre>";

if (isset($_POST['delete_user_final'])) {
    delete_user($db);
}

function delete_user($db) {
    $value = $_SESSION['user_id'];
    $query = "DELETE FROM users WHERE id = '$value'";
    mysqli_query($db, $query);
    mysqli_close($db);
    session_destroy();
    unset($_SESSION['email']);
    $info = "Benutzeraccount erfolgreich gelöscht";
    header("Location: register_view.php?success=$info");
}
