<?php
session_start();

$db = mysqli_connect('localhost', 'root', 'root', 'planningpoker');
$user_id = $_SESSION['user_id'];
$session_id = $_SESSION['session_id'];

// Es werden alle Mitglieder einer Session gezählt
$query = mysqli_prepare($db, "SELECT COUNT(*) FROM session_users WHERE session_id = ?");
mysqli_stmt_bind_param($query, "i", $session_id);
mysqli_stmt_execute($query);
mysqli_stmt_bind_result($query, $count);
while (mysqli_stmt_fetch($query)) {
    $counts = $count;
}

// Es werden alle Mitglieder einer Session gezählt, die bereits abgestimmt haben
$query = "SELECT * FROM storypoints WHERE isset = 1 AND session_id = '$session_id'";
$result = mysqli_query($db, $query);
$countTrue = mysqli_num_rows($result);

// Es werden die Punkte und Namen der bereits abgestimmten Personen selektiert
$points = array();
$usernames = array();
$query = mysqli_prepare($db, "SELECT storypoints.points, users.username FROM storypoints LEFT JOIN users ON users.id = storypoints.user_id WHERE session_id = ? AND isset = 1");
mysqli_stmt_bind_param($query, "s", $session_id);
mysqli_stmt_execute($query);
mysqli_stmt_bind_result($query, $point, $username);
while (mysqli_stmt_fetch($query)) {
    array_push($points, $point);
    array_push($usernames, $username);
}
mysqli_close($db);
// Mit Implode wird das Array in ein String umgewandelt und die einzelnen Werte mit Komma getrennt
$points = implode(",", $points);
$usernames = implode(",", $usernames);

header("Location: storypoints_view.php?countmembers=$counts&counttrue=$countTrue&points=$points&usernames=$usernames");
die();

