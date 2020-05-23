<?php
$db = mysqli_connect('localhost', 'root', 'root', 'planningpoker');

if (isset($_POST['reg_user'])) {
    register_user($db);
}

function register_user($db)
{
    // receive all input values from the form
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
    $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

    // form validation: ensure that the form is correctly filled ...
    // by adding (array_push()) corresponding error unto $errors array
    if (empty($username) || empty($email) || empty($password_1) || empty($password_2)) {
        $error = "Nicht alle Felder sind ausgefüllt";
        header("Location: register_view.php?error=$error");
        die();
    }
    // E-Mail (ref und test: regex101.com)
    $re = "/^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/";
    if (!preg_match($re, $email)) {
        $error = "Bitte gebe eine gültige E-Mail Adresse ein";
        header("Location: register_view.php?error=$error");
        die();
    }
    // Passwort und Password Confirm prüfen auf Übereinstimmung
    if ($password_1 != $password_2) {
        $error = "Die eingegebenen Passwörter stimmen nicht überein";
        header("Location: register_view.php?error=$error");
        die();
    }

    // first check the database to make sure
    // a user does not already exist with the same username and/or email
    $user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1";
    $result = mysqli_query($db, $user_check_query);
    $user = mysqli_fetch_assoc($result);

    if ($user) { // if user exists
        if ($user['email'] === $email) {
            $error_exists = "E-Mail existiert bereits";
            header("Location: register_view.php?error=$error_exists");
            die();
        }
        if ($user['username'] === $username) {
            $error_exists = "Username existiert bereits";
            header("Location:register_view.php?error=$error_exists");
            die();
        }
    }

    // Finally, register user if there are no errors in the form
    $password = sha1($password_1); //encrypt the password before saving in the database

    $query = "INSERT INTO users (username, email, password) VALUES('$username', '$email', '$password')";
    mysqli_query($db, $query);
    header("Location: login_view.php");
}

