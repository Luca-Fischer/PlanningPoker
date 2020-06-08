<?php
$db = mysqli_connect('localhost', 'root', 'root', 'planningpoker');

if (isset($_POST['reg_user'])) {
    register_user($db);
}

function register_user($db)
{
    // erhält alle übergebenen Werte mittels $_POST
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
    $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

    // form validation: Überprüfen, dass die Form richtig ist
    if (empty($username) || empty($email) || empty($password_1) || empty($password_2)) {
        mysqli_close($db);
        $error = "Nicht alle Felder sind ausgefüllt";
        header("Location: register_view.php?error=$error");
        die();
    }
    // E-Mail (ref und test: regex101.com)
    $re = "/^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/";
    if (!preg_match($re, $email)) {
        mysqli_close($db);
        $error = "Bitte gebe eine gültige E-Mail Adresse ein";
        header("Location: register_view.php?error=$error");
        die();
    }
    // Passwort und Password prüfen auf Übereinstimmung
    if ($password_1 != $password_2) {
        mysqli_close($db);
        $error = "Die eingegebenen Passwörter stimmen nicht überein";
        header("Location: register_view.php?error=$error");
        die();
    }

    // überprüfen in der Datenbank, dass ein Benutzer mit der Email oder dem Benutzername nicht bereits existiert
    $user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1";
    $result = mysqli_query($db, $user_check_query);
    $user = mysqli_fetch_assoc($result);

    if ($user) { // wenn der Benutzer existiert...
        if ($user['email'] === $email) {
            mysqli_close($db);
            $error_exists = "E-Mail existiert bereits";
            header("Location: register_view.php?error=$error_exists");
            die();
        }
        if ($user['username'] === $username) {
            mysqli_close($db);
            $error_exists = "Username existiert bereits";
            header("Location:register_view.php?error=$error_exists");
            die();
        }
    }

    $password = sha1($password_1); //verschlüsseln des Passworts, bevor es in der Datenbank gespeichert wird
                                   // https://www.w3schools.com/php/func_string_sha1.asp
    $query = "INSERT INTO users (username, email, password) VALUES('$username', '$email', '$password')";
    mysqli_query($db, $query);
    mysqli_close($db);
    header("Location: login_view.php");
    die();
}

