<?php include "partials/html_header.php"; ?>
<?php include "partials/navbar_view.php"; ?>

<?php
if (!isset($_SESSION['email'])) {
    $error = "Du musst dich erst einloggen";
    header("Location: login_view.php?login_first=$error");
    die();
}
?>
<!-- Hier werden die Formulare für das Beitreten und das erstellen einer eigenen Session dargestellt -->
<div class="container mt-5">
    <div class="row">
        <div class="col-sm-12 col-md-6">
            <form method="post" action="session.php" class="session_form">
                <h4>Create Session</h4>
                <?php if (isset($_GET["error_create"])) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo htmlspecialchars($_GET["error_create"]) ?>
                    </div>
                <?php endif; ?>
                <div class="form-group">
                    <label for="name">Session Name</label>
                    <input type="text" class="form-control" name="session_name" id="name" placeholder="Gebe einen Session Name ein" required autocomplete="off">
                </div>
                <button type="submit" class="btn btn-success btn-block" name="create_session">Session erstellen</button>
            </form>
        </div>
        <div class="col-sm-12 col-md-6">
            <form method="post" action="session.php" class="session_form">
                <h4>Join Session</h4>
                <?php if (isset($_GET["error_join"])) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo htmlspecialchars($_GET["error_join"]) ?>
                    </div>
                <?php endif; ?>
                <?php if (isset($_GET["error_story"])) : ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo htmlspecialchars($_GET["erfolgreich_abgespeichert"]) ?>
                    </div>
                    <div class="alert alert-info" role="alert">
                        <?php echo htmlspecialchars($_GET["error_story"]) ?>
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <label for="name_join">Session Name</label>
                    <input type="text" class="form-control" name="session_name" id="name_join" placeholder="Gebe einen Session Name ein" required autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="id">Session Id</label>
                    <input type="text" class="form-control" name="session_id" id="id" placeholder="Gebe eine Session Id ein" required autocomplete="off">
                </div>
                <button type="submit" class="btn btn-success btn-block" name="join_session">Session beitreten</button>
            </form>
        </div>
    </div>
</div>

<?php include "partials/html_footer.php"; ?>

