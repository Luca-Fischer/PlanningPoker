<?php include "partials/html_header.php"; ?>
<?php include "partials/navbar_view.php"; ?>

<?php
if (!isset($_SESSION['email'])) {
$error_login_first = "Du musst dich erst einloggen";
header("Location: login_view.php?login_first=$error_login_first");
die();
}
?>

<div class="jumbotron">
    <h2><?php echo $_SESSION['session_name'] . "  #" . $_SESSION['session_id'] ?></h2>
</div>
<!-- Formular zum auswählen einer Textdatei für die Userstorys -->
<div class="container mt-5">
    <div class="alert alert-info" role="alert">Wähle eine Textdatei aus, um Themen für das Planning Poker zu laden. Jede Zeile der Textdatei steht für eine Userstory. <br>Jede Zeile muss mit einem Semikolon enden.</div>

    <form action="separate.php" method="post" enctype="multipart/form-data">
        <div class="input-group mb-3">
            <div class="custom-file">
                <input type="file" class="custom-file-input" id="fileToUpload" name="file_name" accept=".txt">
                <label class="custom-file-label" for="fileToUpload">Datei auswählen</label>
            </div>
            <div class="input-group-append">
                <input type="submit" name="fileToUpload" class="input-group-text" id="" value="Hochladen">
            </div>
        </div>

        <?php if (isset($_GET["wrong_format"])) : ?>
            <?php echo "</br><div class='alert alert-danger' role='alert'>" . htmlspecialchars($_GET["wrong_format"]) . "</div>" ?>
        <?php endif; ?>

        <?php if (isset($_GET['format'])) : ?>
            <?php echo "<br><div class='alert alert-success' role='alert'>" . htmlspecialchars($_GET["format"]) . "</div>" ?>
        <?php endif; ?>
    </form>

    <!-- Die übergebenen Zeilen der Textdatei werden hier ausgegeben -->

    <?php if ($_GET["filename"]) :
        $textfile = $_GET['aParam'];
    ?>

        <hr>
        <h3>Wähle eine User Story aus</h3>
        <form action='poker.php' method='post'>
            <ol>
                <?php foreach ($textfile as $line): ?>
                    <?php if ($line != "") : ?>
                        <li><button type='submit' class="btn btn-link" name='task' value='<?php echo $line ?>'><?php echo $line ?></button></li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ol>
        </form>
    <?php endif; ?>
</div>

<?php include "partials/html_footer.php"; ?>
