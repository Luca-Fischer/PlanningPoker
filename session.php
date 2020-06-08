<?php
session_start();

$db = mysqli_connect('localhost', 'root', 'root', 'planningpoker');

if (isset($_POST['create_session'])) {
    create_session($db);
} elseif (isset($_POST['join_session'])) {
    join_session($db);
}

// hier werden Sessions erstellt
function create_session($db)
{
    // values from the form
    $session_name = mysqli_real_escape_string($db, $_POST['session_name']);

    if (empty($session_name)) {
        mysqli_close($db);
        $error_empty = "Das Feld muss ausgefüllt sein";
        header("Location: session_view.php?error_create=$error_empty");
        die();
    }

// überprüft, ob die zufällig generierte Zahl bereits existiert ...
    $check_query = "SELECT id FROM session";
    do {
        $result = mysqli_query($db, $check_query);
        $check = false;
        $rand_varchar = mt_rand(1000, 9999);
        // ... wenn ja, wird die do-Schleife wiederholt
        while ($row_user = mysqli_fetch_array($result)) {
            if ($rand_varchar == $row_user['id']) {
                $check = true;
            }
        }

    } while ($check);

    $user_logged_in_id = intval($_SESSION['user_id']);

    $timestamp = time();

    $query = "INSERT INTO session (id, name, user_id, date, tmp_userstory) VALUES ('$rand_varchar', '$session_name', '$user_logged_in_id', '$timestamp', '')";
    mysqli_query($db, $query);
    $query = "INSERT INTO session_users (session_id, user_id) VALUES('$rand_varchar', '$user_logged_in_id')";
    mysqli_query($db, $query);
    mysqli_close($db);
    $_SESSION['session_name'] = $session_name;
    $_SESSION['session_id'] = $rand_varchar;
    $_SESSION['admin'] = 0;
    header("Location: separate_view.php");
    die();
}

//bereits bestehender Session beitreten
function join_session($db)
{
    $session_name = mysqli_real_escape_string($db, $_POST['session_name']);
    $session_id = mysqli_real_escape_string($db, $_POST['session_id']);
    $user_logged_id = $_SESSION['user_id'];

    if (empty($session_name) || empty($session_id)) {
        mysqli_close($db);
        header("Location: session_view.php");
        die();
    }

    $query = "SELECT * FROM session WHERE id='$session_id' AND name = '$session_name'";
    $results = mysqli_query($db, $query);

    // gibt es eine Session mit der eingegebenen Id und zugehörigen Name...
    if (mysqli_num_rows($results) == 1) {

        $query = mysqli_prepare($db, "SELECT * FROM session_users WHERE user_id = ?");
        $search = $_SESSION['user_id'];
        mysqli_stmt_bind_param($query, "s", $search);
        mysqli_stmt_execute($query);
        mysqli_stmt_bind_result($query, $db_id, $db_session_id, $db_user_id);

        // $_SESSION - Variablen werden gesetzt
        while (mysqli_stmt_fetch($query)) {
            if ($session_id == $db_session_id && $user_logged_id == $db_user_id) {
                $_SESSION['session_name'] = $session_name;
                $_SESSION['session_id'] = $session_id;
                $_SESSION['admin'] = 0;

                $query = "SELECT tmp_userstory FROM session WHERE id = '$session_id'";
                $result = mysqli_query($db, $query);
                $story = mysqli_fetch_assoc($result);

                // ein Benutzer kann der Session erst beitreten, sobald ein Thema von Ersteller ausgewählt wurde
                if (!empty($story['tmp_userstory'])) {
                    mysqli_close($db);
                    $_SESSION['task'] = $story['tmp_userstory'];
                    header("Location: poker_view.php");
                    die();
                } else {
                    mysqli_close($db);
                    $error_mistake = "Admin hat keine Story ausgewählt";
                    header("Location: session_view.php?error_join=$error_mistake");
                    die();
                }
            }
        }

        $query = "INSERT INTO session_users (session_id, user_id) VALUES('$session_id', '$user_logged_id')";
        mysqli_query($db, $query);
        mysqli_close($db);
        header("Location: poker_view.php");
    } else {
        mysqli_close($db);
        $error_combination = "Überprüfen Sie die kombination aus Name und Id";
        header("Location: session_view.php?error_join=$error_combination");
        die();
    }
}
