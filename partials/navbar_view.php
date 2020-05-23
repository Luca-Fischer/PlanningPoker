<?php include "navbar.php"; ?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="./index.php">Planning Poker</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="./index_view.php">
                    <i class="fas fa-home"></i>
                    Home
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="./session_view.php">
                    <i class="fas fa-users"></i>
                    Session
                </a>
            </li>
            <li class="nav-item">
                <form method="post" action="./session_list.php" id="formbutton">
                    <button type="submit" class="nav-link btn btn-link" id="buttonAsLink" name="get_sessions">
                        <i class="fas fa-tachometer-alt"></i>
                        Verwalten
                    </button>
                </form>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link settings" href="./settings_view.php">
                    <i class="fas fa-users-cog"></i>
                    Benutzeraccount
                </a>
            </li>
            <?php if (!isset($_SESSION['email'])) : ?>
                <li class="nav-item">
                    <a class="nav-link" href="./login_view.php">
                        <i class="fas fa-sign-in-alt"></i>
                        Login
                    </a>
                </li>
            <?php endif ?>
            <?php if (isset($_SESSION['email'])) : ?>
                <li class="nav-item">
                    <a class="nav-link" href="./register_view.php?logout='1'">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </a>
                </li>
            <?php endif ?>
        </ul>
    </div>
</nav>
