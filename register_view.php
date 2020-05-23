<?php include "partials/html_header.php"; ?>
<?php include "partials/navbar_view.php"; ?>


    <div class="container-fluid mt-5">
        <div class="row justify-content-center mt-5">
            <div class="col-sm-12 col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Registrieren</h4>
                    </div>
                    <div class="card-body">
                        <?php if (isset($_GET["error"])) : ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo htmlspecialchars($_GET["error"]) ?>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($_GET["success"])) : ?>
                            <div class="alert alert-success" role="alert">
                                <?php echo htmlspecialchars($_GET["success"]) ?>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="register.php">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" name="email" id="email" placeholder="Gebe deine E-Mail Adresse ein" autofocus autocomplete="off" required>
                            </div>
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" name="username" placeholder="Gebe deinen Usernamen ein" id="username" autocomplete="off" required>
                            </div>
                            <div class="form-group">
                                <label for="password_1">Password</label>
                                <input type="password" class="form-control" name="password_1" placeholder="Gebe dein Passwort ein" id="password_1" autocomplete="off" required>
                            </div>
                            <div class="form-group">
                                <label for="password_2">Password</label>
                                <input type="password" class="form-control" name="password_2" placeholder="Bestätige dein Passwort" id="password_2" autocomplete="off" required>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success btn-block" name="reg_user">Login</button>
                            </div>
                            <p class="text-muted">
                                Du hast schon einen Benutzeraccount? <a href="./login_view.php">Zum Login</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include "partials/html_footer.php"; ?>