<?php include "partials/html_header.php"; ?>
<?php include "partials/navbar_view.php"; ?>

<?php
if (!isset($_SESSION['email'])) {
    $error_login_first = "Bitte melde dich zuerst an.";
    header("Location: login_view.php?login_first=$error_login_first");
    die();
}
?>

<div class="container mt-5">
    <h1 class="text-muted text-center mb-3"><i class="far fa-user"></i></h1>
    <p class="text-center mb-1"><i class="fas fa-user"></i> <?php echo $_SESSION['username'] ?></p>
    <p class="text-center"><i class="fas fa-envelope"></i> <?php echo $_SESSION['email'] ?></p>

    <div class="row text-center">
        <button type="button" name="delete_user_final" class="btn btn-outline-danger mx-auto btn-sm" id="delete_user_btn">Account löschen</button>
    </div>
    <!-- verstecktes Formular wird mit JS abgeschickt -->
    <form method="post" action="settings.php" id="delete_user_form" hidden>
        <button type="submit" name="delete_user_final" id="delete_user_final">Submit</button>
    </form>
</div>

<?php include "partials/html_footer.php"; ?>
