<?php include "partials/html_header.php"; ?>
<?php include "partials/navbar_view.php"; ?>

<!-- Login-Seite mit Formular zum Einloggen -->
<div class="container-fluid mt-5">
    <div class="row justify-content-center mt-5">
        <div class="col-sm-12 col-md-6 col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h4>Login</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($_GET["error_combination"])) : ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo htmlspecialchars($_GET["error_combination"]) ?>
                        </div>
                    <?php endif; ?>
                    <?php if (isset($_GET["login_first"])) : ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo htmlspecialchars($_GET["login_first"]) ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="login.php">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" name="email" id="email" placeholder="Gebe deine E-Mail Adresse ein" autofocus autocomplete="off" required>
                        </div>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" name="username" placeholder="Gebe deinen Usernamen ein" id="username" autocomplete="off" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" name="password" placeholder="Gebe dein Passwort ein" id="password" autocomplete="off" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success btn-block" name="login_user">Login</button>
                        </div>
                        <p class="text-muted">
                            Noch keinen Benutzeraccount? <a href="./register_view.php">Registrieren</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include "partials/html_footer.php"; ?>
