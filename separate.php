<?php
session_start();

$db = mysqli_connect('localhost', 'root', 'root', 'planningpoker');

if (!isset($_SESSION['email'])) {
    $error_login_first = "Du musst dich erst einloggen";
    header("Location: login_view.php?var1=$error_login_first");
    die();
}

if (isset($_POST['upload_textfile'])) {
    upload_textfile($db);
}
if (isset($_POST['fileToUpload'])) {
    fileToUpload($db);
}

function upload_textfile() {
    $var = 1;
    header("function: separate_view.php?var=$var");
}

function fileToUpload($db)
{
    $file_name = $_FILES["file_name"]["name"];

    $query = mysqli_prepare($db, "SELECT id FROM session WHERE user_id = ?");
    $search = $_SESSION['user_id'];
    mysqli_stmt_bind_param($query, "s", $search);
    mysqli_stmt_execute($query);
    mysqli_stmt_bind_result($query, $id);

    while (mysqli_stmt_fetch($query)) {
        if ($id == $_SESSION['session_id']) {
            $_SESSION['admin'] = 1; // sobald die Session verlassen wird, oder eine neue gejoint wird, muss $_SESSION['admin'] auf '0' gesetzt werden
            if (!empty($file_name)) {
                $format = "Correct Format";
                header("Location: separate_view.php?format=$format&filename=$file_name");
                die();
            }
            else {
                $format = "Keine Datei ausgewählt";
                $_SESSION['admin'] = 0;
                header("Location: separate_view.php?format=$format");
                die();
            }

        }
    }
    $error = "keine Berechtigung ein Textfile hochzuladen";
    header("Location: separate_view.php?error=$error");
    die();
}
