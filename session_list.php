<?php
session_start();

$db = mysqli_connect('localhost', 'root', 'root', 'planningpoker');

if (isset($_POST['get_sessions'])) {
    get_sessions($db);
} elseif (isset($_POST['delete_session'])) {
    delete_session($db);
    get_sessions($db);
} elseif (isset($_POST['join_session'])) {
    join_session($db);
} elseif (isset($_POST['story_overview'])) {
    story_overview($db);
}

// ERSTE LÖSUNG - Werte über GET übergeben. Allerdings konnten so die Tabellenwerte geändert werden und fremde Sessions gelöscht werden

function get_sessions($db)
{
    $ids = array();
    $names = array();
    $dates = array();

    $query = mysqli_prepare($db, "SELECT id,name,date FROM session WHERE user_id =?");
    $search = $_SESSION['user_id'];
    mysqli_stmt_bind_param($query, "s", $search);
    mysqli_stmt_execute($query);
    mysqli_stmt_bind_result($query, $id, $name, $date);

    while (mysqli_stmt_fetch($query)) {
        array_push($ids, $id);
        array_push($names, $name);
        array_push($dates, $date);
    }
    mysqli_stmt_close($query);
    $ids = implode(",", $ids);
    $names = implode(",", $names);
    $dates = implode(",", $dates);
    header("Location: session_list_view.php?ids=$ids&names=$names&dates=$dates");
    die();
}


function join_session($db)
{
    $session_id = $_POST['join_session'];
    $query = "SELECT user_id FROM session WHERE id = '$session_id'";
    $result = mysqli_query($db, $query);
    $user = mysqli_fetch_assoc($result);
    if ($user['user_id'] != $_SESSION['user_id']) {
        $error = "Ungültig";
        header("Location: session_list_view.php?invalid=$error");
        die();
    } else {
        $query = "SELECT * FROM session WHERE id='$session_id'";
        $results = mysqli_query($db, $query);
        if (mysqli_num_rows($results) == 1) {
            $row = $results->fetch_assoc();
            $session_name = $row['name'];
            $_SESSION['session_name'] = $session_name;
            $_SESSION['session_id'] = $session_id;
            $_SESSION['admin'] = 0;
            header("Location: separate_view.php");
            die();
        } else {
            $fatal_error = "Es ist ein nicht erwarteter Fehler mit der Session aufgetreten";
            header("Location: session_list_view.php?var4=$fatal_error");
            die();
        }
    }
}

function delete_session($db)
{
    $value = $_POST['delete_session'];
    $query = "SELECT user_id FROM session WHERE id = '$value'";
    $result = mysqli_query($db, $query);
    $user = mysqli_fetch_assoc($result);
    if ($user['user_id'] != $_SESSION['user_id']) {
        $error = "Ungültig";
        header("Location: session_list_view.php?invalid=$error");
        die();
    } else {
        $query = "DELETE FROM session WHERE id = '$value'";
        mysqli_query($db, $query);

        $query = "DELETE FROM storypoints WHERE session_id = '$value'";
        mysqli_query($db, $query);

        $query = "DELETE FROM session_users WHERE session_id = '$value'";
        mysqli_query($db, $query);
        mysqli_close($db);

        header("Location: session_list_view.php");
        die();
    }
}

function story_overview($db) {
    $points = array();
    $userstorys = array();
    $session_id = $_POST['story_overview'];

    $query = mysqli_prepare($db, "SELECT points, userstory FROM storypoints_summary WHERE session_id = ?");
    mysqli_stmt_bind_param($query, "s", $session_id);
    mysqli_stmt_execute($query);
    mysqli_stmt_bind_result($query, $db_point, $db_userstory);

    while (mysqli_stmt_fetch($query)) {
        array_push($points, $db_point);
        array_push($userstorys, $db_userstory);
    }
    mysqli_close($db);

    $query_story = http_build_query(array('aParam' => $userstorys));
    $query_points = http_build_query(array('aParam1' => $points));

    header("Location: story_overview_view.php?$query_story&$query_points");
    die();
}

