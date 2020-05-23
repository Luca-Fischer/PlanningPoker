<?php include "partials/html_header.php"; ?>
<?php include "partials/navbar_view.php"; ?>

<?php
if (!isset($_SESSION['email'])) {
    $error_login_first = "Du musst dich erst einloggen";
    header("Location: login_view.php?login_first=$error_login_first");
    die();
}
?>

<div class="container mt-5">
    <h2>Hier siehst du alle erstellten Sessions</h2>
    <?php if (isset($_GET['var4'])) : ?>
        <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($_GET["var4"]) ?></div>
    <?php endif; ?>
    <?php if (isset($_GET['invalid'])) : ?>
        <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($_GET["invalid"]) ?></div>
    <?php endif; ?>

    <table class="table table-hover mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Erstelldatum</th>
                <th>Aktionen</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $ids = explode(",", htmlspecialchars($_GET['ids']));
            $names = explode(",", htmlspecialchars($_GET['names']));
            $dates = explode(",", htmlspecialchars($_GET['dates']));

            $count = count($ids);
            if ($count > 0): ?>
                <?php for ($i = 0; $i < $count; $i++): ?>
                    <form method='post' action='session_list.php'>
                        <tr>
                            <td><?php echo $ids[$i] ?></td>
                            <td><button class='btn btn-sm btn-link' type='submit' name='story_overview' value='<?php echo $ids[$i] ?>'><?php echo $names[$i] ?></button></td>
                            <td><?php $date = date("d.m.Y - H:i", $dates[$i]); echo $date; ?></td>
                            <td>
                                <button type='submit' name='join_session' value='<?php echo $ids[$i] ?>' class='btn btn-outline-success btn-sm'>Join</button>
                                <button type='submit' name='delete_session' value='<?php echo $ids[$i] ?>' class='btn btn-outline-warning btn-sm'>Delete</button>
                            </td>
                        </tr>
                    </form>
                <?php endfor; ?>
            <?php endif; ?>
            <?php if($count == 0): ?>
                <tr>
                    <td colspan="4">Du hast noch keine eigenen Sessions erstellt. <a href='session_view.php'>Erstelle</a> jetzt deine erste Session!</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</div>

<?php include "partials/html_footer.php"; ?>

