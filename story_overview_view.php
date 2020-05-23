<?php include "partials/html_header.php"; ?>
<?php include "partials/navbar_view.php"; ?>

<div class="jumbotron">
    <h2>PLATZHALTER</h2>
</div>

<div class="container mt-5">

    <?php
    $points = $_GET['aParam1'];
    $userstorys = $_GET['aParam']; ?>

    <table class="table table-hover">
        <thead>
        <tr>
            <th>Userstory</th>
            <th>Durchschnitt Storypoints</th>
        </tr>
        </thead>
        <tbody>
        <?php for ($i = 0; $i < count($points); $i++): ?>
            <tr>
                <td><?php echo $userstorys[$i]; ?></td>
                <td><?php echo $points[$i]; ?></td>
            </tr>
        <?php endfor; ?>
        </tbody>
    </table>

    <form action="session_list.php" method="post">
        <button type="submit" class="btn btn-link" name="get_sessions">Zurück</button>
    </form>
</div>

<?php include "partials/html_footer.php"; ?>
