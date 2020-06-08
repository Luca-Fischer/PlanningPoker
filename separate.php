<?php
session_start();

$db = mysqli_connect('localhost', 'root', 'root', 'planningpoker');

if (isset($_POST['fileToUpload'])) {
    fileToUpload($db);
}


function fileToUpload($db)
{
    $myfile = fopen($_FILES["file_name"]["tmp_name"], "r");
    $file = fread($myfile, filesize($_FILES["file_name"]["tmp_name"]));
    fclose($myfile);

    $file_array = explode(";", $file);

    $query_story = http_build_query(array('aParam' => $file_array));


    // nur der Ersteller der Session kann eine Textdatei auswählen und somit auch die Userstory
    $query = mysqli_prepare($db, "SELECT id FROM session WHERE user_id = ?");
    $search = $_SESSION['user_id'];
    mysqli_stmt_bind_param($query, "s", $search);
    mysqli_stmt_execute($query);
    mysqli_stmt_bind_result($query, $id);

    while (mysqli_stmt_fetch($query)) {
        if ($id == $_SESSION['session_id']) {
            $_SESSION['admin'] = 1;
            // sobald die Session verlassen wird, oder eine neue gejoint wird, muss $_SESSION['admin'] auf '0' gesetzt werden
            if (!empty($file)) {
                mysqli_close($db);
                $format = "Korrektes Format";

                header("Location: separate_view.php?format=$format&$query_story&filename=1");
                die();
            }
            else {
                $format = "Keine Datei ausgewählt";
                $_SESSION['admin'] = 0;
                mysqli_close($db);
                header("Location: separate_view.php?wrong_format=$format");
                die();
            }

        }
    }
    $error = "keine Berechtigung ein Textfile hochzuladen";
    header("Location: separate_view.php?error=$error");
    die();
}
