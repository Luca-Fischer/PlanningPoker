<?php include "partials/html_header.php"; ?>
<?php include "partials/navbar_view.php"; ?>

<div class="jumbotron">
    <h2>Storypoints - <?php echo $_SESSION['task'] ?></h2>
</div>

<div class="container mt-5">
    <?php if ($_GET['countmembers'] == $_GET['counttrue']) : ?>
    <?php
    $points = explode(",", htmlspecialchars($_GET['points']));
    $usernames = explode(",", htmlspecialchars($_GET['usernames']));

    $average = 0;
    $i = 0;
    foreach ($points as $point) {
        $average += $point;
        $i++;
    }
    $average = $average / $i;
    ?>

    <table class="table table-hover">
        <thead>
        <tr>
            <th>Name</th>
            <th>Storypoints</th>
        </tr>
        </thead>
        <tbody>
        <?php for ($i = 0; $i < count($points); $i++): ?>
            <tr>
                <td><?php echo $usernames[$i]; ?></td>
                <td><?php echo $points[$i]; ?></td>
            </tr>
        <?php endfor; ?>
        </tbody>
    </table>

    <p class="text-right mb-0">Durchschnitt: <?php echo $average; ?></p>
    <form action="poker.php" method="post" class="text-right">
        <button type="submit" class="btn btn-success btn-sm" value="<?php echo $average ?>" name="final_result">
            <i class="fas fa-save"></i> Mittelwert speichern
        </button>
    </form>

    <form action="poker.php" method="post" class="text-right mt-4">
        <button type="submit" class="btn btn-link" name="start_poker"><i class="fas fa-undo"></i> Erneut abstimmen</button>
    </form>



    <?php else: ?>
        <br>
        <div class="spinner-border" role="status">
            <span class="sr-only">Loading...</span>
        </div>
        Warte bis alle Teilnehmer abgestimmt haben...
        <?php header("refresh:4;url=storypoints.php"); ?>
    <?php endif; ?>


</div>


<?php include "partials/html_footer.php"; ?>
