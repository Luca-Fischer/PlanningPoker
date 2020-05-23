<?php
session_start();

$db = mysqli_connect('localhost', 'root', 'root', 'planningpoker');

if (isset($_POST['task'])) {
    planning_poker();
} elseif (isset($_GET['count_members'])) {
    print_members($db);
} elseif (isset($_POST['start_poker'])) {
    start_poker($db);
} elseif (isset($_POST['kick'])) {
    kickMember($db);
    print_members($db);
} elseif (isset($_POST['storypoints'])) {
    storypoints($db);
} elseif (isset($_POST['final_result'])) {
    save_results($db);
}

function planning_poker()
{
    $_SESSION['task'] = $_POST['task'];
    header("Location: poker_view.php");
    die();
}

function print_members($db)
{
    $counts = count_members($db);

    $members = get_members($db);

    $members = implode(",", $members);
    header("Location: poker_view.php?total=$counts&members=$members");
    die();
}

function kickMember($db)   //TODO: JOIN!
{
    $value = $_POST['kick'];
    $query = "SELECT * FROM users WHERE username = '$value'";
    $result = mysqli_query($db, $query);
    $user = mysqli_fetch_assoc($result);
    $user_id = $user['id'];

    $query = mysqli_prepare($db, "SELECT id FROM session WHERE user_id = ?");
    mysqli_stmt_bind_param($query, "s", $user_id);
    mysqli_stmt_execute($query);
    mysqli_stmt_bind_result($query, $id);
    while (mysqli_stmt_fetch($query)) {
        if ($id == $_SESSION['session_id']) {
            $error = "Creator of the Poker is not deletable";
            header("Location: poker_view.php?notpossible=$error");
            die();
        }
    }

    $query = "DELETE session_users FROM session_users LEFT JOIN users ON session_users.user_id = users.id WHERE users.username = '$value'";
    mysqli_query($db, $query);

    $query = "DELETE FROM storypoints WHERE user_id = '$user_id'";
    mysqli_query($db, $query);
    mysqli_close($db);

}

function start_poker($db)
{
    $user_id = $_SESSION['user_id'];
    $session_id = $_SESSION['session_id'];

    $query = "UPDATE storypoints SET isset = 0 WHERE user_id = '$user_id' AND session_id = '$session_id'";
    mysqli_query($db, $query);
    $var_start = 1;
    header("Location: poker_view.php?start=$var_start");
}


function count_members($db)
{
    $search = $_SESSION['session_id'];
    $counts = 0;

    $query = mysqli_prepare($db, "SELECT COUNT(*) FROM session_users WHERE session_id = ?");
    mysqli_stmt_bind_param($query, "i", $search);
    mysqli_stmt_execute($query);
    mysqli_stmt_bind_result($query, $count);
    while (mysqli_stmt_fetch($query)) {
        $counts = $count;
    }
    return $counts;
}

function get_members($db)
{
    $search = $_SESSION['session_id'];
    $members = array();
    $query = mysqli_prepare($db, "SELECT users.username FROM session_users LEFT JOIN users ON session_users.user_id = users.id WHERE session_id = ?");
    mysqli_stmt_bind_param($query, "s", $search);
    mysqli_stmt_execute($query);
    mysqli_stmt_bind_result($query, $member);
    while (mysqli_stmt_fetch($query)) {
        array_push($members, $member);
    }
    mysqli_stmt_close($query);

    return $members;
}


function storypoints($db)
{
    $points = $_POST['storypoints'];

    $user_id = $_SESSION['user_id'];
    $session_id = $_SESSION['session_id'];
    $query = "SELECT user_id, session_id FROM storypoints WHERE user_id = '$user_id' AND session_id = '$session_id'";
    $result = mysqli_query($db, $query);
    $user = mysqli_fetch_assoc($result);

    if ($user) { // if user exists
        $query = "UPDATE storypoints SET points = '$points', isset = 1 WHERE user_id = '$user_id' AND session_id = '$session_id'";
        mysqli_query($db, $query);
    } else {
        $query = "INSERT INTO storypoints (user_id, session_id, points, isset) VALUES('$user_id', '$session_id', '$points', 1)";
        mysqli_query($db, $query);
    }

    $countmembers = count_members($db);

    header("Location: storypoints_view.php?countmembers=$countmembers");
    die();
}

function save_results($db)
{
    $average = $_POST['final_result'];
    $userstory = $_SESSION['task'];
    $session_id = $_SESSION['session_id'];

    $query = "SELECT * FROM storypoints_summary WHERE userstory = '$userstory' AND session_id = '$session_id'";
    $result = mysqli_query($db, $query);
    $story = mysqli_fetch_assoc($result);

    if ($story) {
        $query = "UPDATE storypoints_summary SET points = '$average' WHERE userstory = '$userstory' AND session_id = '$session_id'";
        mysqli_query($db, $query);
    } else {
        $query = "INSERT INTO storypoints_summary (session_id, points, userstory) VALUES('$session_id', '$average', '$userstory')";
        mysqli_query($db, $query);
    }
    if ($_SESSION['admin'] == 1) {
        $newformat = "Textfile bei neuer Abstimmung hochladen und neue Story auswählen";
        header("Location: separate_view.php?format=$newformat");
        die();
    }
    else
    {
        $error_new_story = "Bei erneuter Abstimmung, warte bis der Admin eine neue Story ausgewählt hat";
        $success = "Punkte erfolgreich abgespeichert";
        header("Location: session_view.php?error_story=$error_new_story&erfolgreich_abgespeichert=$success");
        die();
    }
}

