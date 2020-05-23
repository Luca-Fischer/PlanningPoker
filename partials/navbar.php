<?php
session_start();

if (isset($_GET['logout'])) {
    log_out();
}
function log_out()
{
    session_destroy();
    unset($_SESSION['email']);
    header("Location: login_view.php");
    die();
}

