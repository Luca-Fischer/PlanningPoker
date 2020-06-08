<?php
session_start();

$db = mysqli_connect('localhost', 'root', 'root', 'planningpoker');

if (isset($_SESSION['email'])) {
    header("Location: index_view.php");
}

if (isset($_POST['login_user'])) {
    login_user($db);
}

// Funktion zum Einloggen
function login_user($db)
{
    // alle drei Felder werden in den Variablen gespeichert
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password = mysqli_real_escape_string($db, $_POST['password']);
    $username = mysqli_real_escape_string($db, $_POST['username']);

    // alle Felder ausgefüllt?
    if (empty($email) || empty($password) || empty($username)) {
        mysqli_close($db);
        $error_empty = "Nicht alle Felder sind ausgefüllt";
        header("Location: login_view.php?error_combination=$error_empty");
        die();
    }

    // Passwort verschlüssen
    $password = sha1($password);
    $query = "SELECT * FROM users WHERE email='$email' AND password='$password' AND username='$username'";

    $results = mysqli_query($db, $query);
    // existiert der Benutzer...
    if (mysqli_num_rows($results) == 1) {
        $_SESSION['email'] = $email;
        $_SESSION['username'] = $username;

        $user_query = "SELECT id FROM users WHERE email = '$email'";
        $user_result = mysqli_query($db, $user_query);
        $user_id = mysqli_fetch_assoc($user_result);
        mysqli_free_result($user_result);
        mysqli_close($db);
        $_SESSION['user_id'] = $user_id['id'];

        $_SESSION['admin'] = 0;
        mysqli_close($db);
        header("Location: index_view.php");
        die();
        // sonst Fehlermeldung ausgeben
    } else {
        mysqli_close($db);
        $error_combination = "Überprüfen Sie die kombination aus Username/email/Passwort";
        header("Location: login_view.php?error_combination=$error_combination");
        die();
    }
}
