<?php
session_start();

$db = mysqli_connect('localhost', 'root', 'root', 'planningpoker');

if (isset($_POST['task'])) {
    planning_poker($db);
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

// Userstory wird gesetzt und in der Datenbank gespeichert
function planning_poker($db)
{
    $_SESSION['task'] = $_POST['task'];
    $tmp_session = $_POST['task'];
    $tmp_session_id  = $_SESSION['session_id'];
    $tmp_session_name  = $_SESSION['session_name'];
    // Datenbank mit der Userstory wird geupdatet
    $query = "UPDATE session SET tmp_userstory = '$tmp_session' WHERE id = '$tmp_session_id' AND name = '$tmp_session_name'";
    mysqli_query($db, $query);
    mysqli_close($db);
    header("Location: poker_view.php");
    die();
}

function print_members($db)
{
    // Funktion "count_members()" wird aufgerufen und in einer Variable gespeichert
    $counts = count_members($db);
    // Funktion "get_members()" wird aufgerufen und wird ebenfalls in einer Variable gespeichert
    $members = get_members($db);

    //damit die Mitglieder übergeben werden können werden diese in einen String geschrieben und mit Komma getrennt
    $members = implode(",", $members);
    mysqli_close($db);
    header("Location: poker_view.php?total=$counts&members=$members");
    die();
}

// sobald Mitglieder einer Session rausgeworfen werden - diese sind zwar noch auf der Seite, aber Ihre Abstimmung hat keinen Einfluss
function kickMember($db)
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
        //Ersteller der Session kann sich nicht rauswerfen. dies muss über die "session_list_view.php" - Seite passieren
        if ($id == $_SESSION['session_id']) {
            $error = "Ersteller der Session kann nicht rausgeworfen werden";
            header("Location: poker_view.php?notpossible=$error");
            die();
        }
    }

    // Lösche den Eintrag in Session_users und Storypoints mit der übergebenen Id
    $query = "DELETE FROM session_users WHERE user_id = '$user_id'";
    mysqli_query($db, $query);

    $query = "DELETE FROM storypoints WHERE user_id = '$user_id'";
    mysqli_query($db, $query);
    mysqli_close($db);
}


function start_poker($db)
{
    $user_id = $_SESSION['user_id'];
    $session_id = $_SESSION['session_id'];

    // wird die Abstimmung gestartet, wird der Wert, ob abgestimmt wurde (isset) auf 0 gesetzt
    // die Funktion wird auch aufgerufen, sobald die Mitglieder auf erneut Abstimmen klickt; Personen die nicht auf erneut klicken, behalten ihre Werte
    $query = "UPDATE storypoints SET isset = 0 WHERE user_id = '$user_id' AND session_id = '$session_id'";
    mysqli_query($db, $query);
    $var_start = 1;
    mysqli_close($db);
    header("Location: poker_view.php?start=$var_start");
    die();
}

// Mitglieder werden gezählt
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

// Namen der Mitglieder wird übergeben
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

    if ($user) { // wenn der Benutzer existiert
        // die von den einzelnen Personen gedrückten Punkte werden in der Datenbank gespeichert
        $query = "UPDATE storypoints SET points = '$points', isset = 1 WHERE user_id = '$user_id' AND session_id = '$session_id'";
        mysqli_query($db, $query);
    } else {
        // wenn das der erste Eintrag einer Person ist, wird der WErt nicht geupdatet sondern gesetzt
        $query = "INSERT INTO storypoints (user_id, session_id, points, isset) VALUES('$user_id', '$session_id', '$points', 1)";
        mysqli_query($db, $query);
    }
    $countmembers = count_members($db);
    mysqli_close($db);
    header("Location: storypoints_view.php?countmembers=$countmembers");
    die();
}


function save_results($db)
{
    $average = $_POST['final_result'];
    $userstory = $_SESSION['task'];
    $session_id = $_SESSION['session_id'];
    $user_id = $_SESSION['user_id'];

    $query = "SELECT * FROM storypoints_summary WHERE userstory = '$userstory' AND session_id = '$session_id'";
    $result = mysqli_query($db, $query);
    $story = mysqli_fetch_assoc($result);

    if ($story) {
        // wenn die Abstimmung zu Ende ist und der Mittelwert abgegeben werden soll --> Datenbankeintrag ändern
        $query = "UPDATE storypoints_summary SET points = '$average' WHERE userstory = '$userstory' AND session_id = '$session_id'";
        mysqli_query($db, $query);
    } else {
        $query = "INSERT INTO storypoints_summary (session_id, points, userstory) VALUES('$session_id', '$average', '$userstory')";
        mysqli_query($db, $query);
    }
    // Isset, ob abgestimmt wurde, wird auf 0 gesetzt
    $query = "UPDATE storypoints SET isset = 0 WHERE user_id = '$user_id' AND session_id = '$session_id'";
    mysqli_query($db, $query);

    // der Ersteller einer Session wird auf die "separate_view.php" - Seite weitergeleitet, um eine neue Story zu setzen...
    if ($_SESSION['admin'] == 1) {
        mysqli_close($db);
        $newformat = "Textfile bei neuer Abstimmung hochladen und neue Story auswählen";
        header("Location: separate_view.php?format=$newformat");
        die();
    }
    // ... andere Benutzer kommen auf die "session_view.php" - Seite um bei neuer "tmp_userstory" erneut beizutreten
    else
    {
        $error_new_story = "Bei erneuter Abstimmung, warte bis der Admin eine neue Story ausgewählt hat";
        $success = "Punkte erfolgreich abgespeichert";
        mysqli_close($db);
        header("Location: session_view.php?error_story=$error_new_story&erfolgreich_abgespeichert=$success");
        die();
    }
}

