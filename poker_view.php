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

<div class="container mt-5">
    <h3><i class="fas fa-tasks"></i> <?php echo $_SESSION['task'] ?></h3>
    <?php if (!$_GET['start']) : ?>
    <?php if (isset($_GET['notpossible'])) : ?>
        <div class="alert alert-danger" role="alert"><?php echo $_GET['notpossible'] ?></div>
    <?php endif; ?>
    <div class="spinner-border" role="status">
        <span class="sr-only">Loading...</span>
    </div>

    <?php if ($_GET['members']): ?>
        <p class="text-muted"><i class="fas fas-users"></i> Anzahl Mitglieder: <?php echo htmlspecialchars($_GET["total"]); ?></p>
        <p>Mitglieder-Liste</p>
        <form action='poker.php' method='post'>
            <ol>
            <?php
                $members = explode(",", htmlspecialchars($_GET['members']));
                foreach ($members as $member): ?>
                <li>
                    <?php echo $member; ?>
                    <?php if ($_SESSION['admin'] == 1): ?>
                        <button type='submit' class='btn btn-sm btn-link' name='kick' value='$member'><i class="far fa-trash-alt"></i> Rauswerfen</button>
                    <?php endif; ?>
                </li>

            <?php endforeach; ?>
            </ol>
        </form>
    <?php endif; ?>

    <hr>

    <div class="input-group mt-5 mb-5">
        <input type="text" class="form-control" placeholder="Session Link" id="copy_link_input" aria-label="Session Link" readonly
               value="<?php $folder = explode("/", __DIR__); echo "http://$_SERVER[HTTP_HOST]/". end($folder) . "/join_url.php?session_name=" . $_SESSION['session_name'] . "&session_id=" . $_SESSION['session_id'] ?>">
        <div class="input-group-append">
            <button class="btn btn-outline-secondary" type="button" id="copy_link_btn" onclick="copyToClipboard('#copy_link_input')" >Link kopieren</button>
        </div>
    </div>
        <?php header("refresh:4;url=poker.php?count_members=1"); ?>
        <hr>
        <?php if ($_GET['total']) : ?>
            <form action="poker.php" method="post" class="text-center">
                <button type="submit" class="btn btn-primary btn-lg" name="start_poker">Für diese User Story abstimmen</button>
            </form>
        <?php endif; ?>
    <?php endif; ?>

    <?php if ($_GET["start"] == 1) : ?>
        <form action='poker.php' method='post' class="mt-5">
            <div class="row">
                <?php
                $members = explode(",", htmlspecialchars($_GET['members']));
                $cards = array("0", "0,5", "1", "2", "3", "5", "8", "13", "20", "40", "100", "&#x2615");
                for ($s = 0; $s < count($cards); $s++): ?>
                    <div class="col-6 col-sm-6 col-md-3 col-lg-2">
                        <button type ='submit' class='btn planning-card' value='<?php echo $cards[$s]; ?>' name='storypoints'><?php echo $cards[$s]; ?></button>
                    </div>

                <?php endfor; ?>
            </div>
        </form>
    <?php endif; ?>

</div>

<?php include "partials/html_footer.php"; ?>
