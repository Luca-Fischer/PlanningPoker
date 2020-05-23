<?php include "partials/html_header.php"; ?>
<?php include "partials/navbar_view.php"; ?>

<div class="jumbotron" id="welcomeJumbo">
    <div class="overlay">
        <div class="container-fluid">
            <h1>SCRUM POKER</h1>
        </div>
    </div>
</div>

<div class="container mt-5">

    <?php if (isset($_GET["error"])) : ?>
        <br>
        <div class="alert alert-danger" role="alert">
            <?php echo htmlspecialchars($_GET["error"]) ?>
        </div>
        <br>
    <?php endif; ?>

    <div class="jumbotron">
        <h1 class="display-4">Willkommen <?php echo $_SESSION['username'] ?></h1>

        <blockquote class="blockquote mt-3">
            <p class="mb-0">We are uncovering better ways of developing software by doing it and helping others do it.</p>
            <footer class="blockquote-footer">agilemanifesto.org</footer>
        </blockquote>

        <p class="lead">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut
            labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea
            rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit
            amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam
            erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no
            sea takimata sanctus est Lorem ipsum dolor sit amet.</p>
        <hr class="my-4">
        <p>Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat.</p>
        <a class="btn btn-primary btn-block btn-lg" href="session_view.php" role="button">Hier geht's zum Planning Poker</a>
    </div>
</div>

<?php include "partials/html_footer.php"; ?>
