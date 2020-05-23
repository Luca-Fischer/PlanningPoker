<?php include "partials/html_header.php"; ?>
<?php include "partials/navbar_view.php"; ?>

<div class="jumbotron">
    <h2><?php echo $_SESSION['session_name'] . "  #" . $_SESSION['session_id'] ?></h2>
</div>

<div class="container mt-5">
    <div class="alert alert-info" role="alert">Select Textfile to upload (With 'Upload Textfile' you start the Planning-Poker)</div>

    <form action="separate.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="file_name">1) Datei auswählen</label>
            <input type="file" id="fileToUpload" name="file_name" accept=".txt">
            <?php if ($_SESSION['admin'] == 0) : ?>
                <input type="submit" name="fileToUpload" value="Check file format">
                <?php if (isset($_GET["format"])) : ?>
                    </br></br>
                    <div class='error'> <?php echo htmlspecialchars($_GET["format"]) ?></div>
                <?php endif; ?>
                <?php if (isset($_GET["error"])) : ?>
                    </br></br>
                    <div class='error'><?php echo $_GET["error"] ?></div>
                <?php endif; ?>
            <?php endif; ?>

        </div>
        <div class="form-group">
            <?php if ($_SESSION['admin'] == 1) : ?>
                <label for="fileToUpload">2) Ausgewählte Datei überprüfen</label>
                <input type="submit" name="fileToUpload" id="" value="Check file format">
                <?php echo "</br></br><div class='alert alert-success' role='alert'>" . htmlspecialchars($_GET["format"]) . "</div>" ?>
            <?php endif; ?>
        </div>
    </form>

    <?php if ($_GET["filename"]) :
        $filename = $_GET["filename"];
        $contents = file($filename);
        ?>
        <hr>
        <h3>Wähle eine User Story aus</h3>
        <form action='poker.php' method='post'>
            <ol>
                <?php foreach ($contents as $line): ?>
                    <li><button type='submit' class="btn btn-link" name='task' value='<?php echo $line ?>'><?php echo $line ?></button></li>
                <?php endforeach; ?>
            </ol>
        </form>
    <?php endif; ?>


</div>




<?php include "partials/html_footer.php"; ?>
