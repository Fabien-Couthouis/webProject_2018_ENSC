<?php
session_start();
require_once "includes/functions.php";
needLogin();
?>
    <!DOCTYPE html>
    <html>

    <?php include "includes/head.php";?>
    <?php include "includes/header.php";?>

    <body class="tests">
        <div class="container">
            <br>
            <h3 class="text-center">Mes tests effectués</h3>
            <br>
            <br>

<?php
$iduser = $_SESSION['login'];
$getNbTests = getDb()->prepare("SELECT COUNT(*) FROM reponse WHERE idUtilisateur = ?");
$getNbTests->execute([$iduser]);
$nbTests = $getNbTests->fetch()[0];

if ($nbTests == 0) {
    echo "Vous n'avez effectué aucun test.";
} else {
    $getTests = getDb()->prepare("SELECT * FROM reponse WHERE idUtilisateur = ?");
    $getTests->execute([$iduser]);
    $tests = $getTests->fetchAll();
    ?>

                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Expérience</th>
                            <th scope="col">Type</th>
                            <th scope="col">Score</th>
                            <th scope="col">Description</th>
                            <th scope="col">Campagne</th>
                        </tr>
                    </thead>
                    <tbody>
    <?php

    $getCampaignName = getDb()->prepare("SELECT nom from campagne WHERE id=?");
    $getExpInfo = getDb()->prepare("SELECT nom, descr from experience WHERE id=?");

    //On récupère et on affiche tous les tests
    for ($i = 0; $i < $nbTests; $i++) {
        $testId = $tests[$i][0];
        $expId = $tests[$i][1];
        $testType = $tests[$i][2];
        $testScore = $tests[$i][3];

        $getCampaignId = getDb()->prepare("SELECT idcamp from experience WHERE id=?");
        $getCampaignId->execute([$expId]);
        $campId = $getCampaignId->fetchAll()[0][0];

        $getCampaignName->execute([$campId]);
        $campName = $getCampaignName->fetch()[0];

        $getExpInfo->execute([$expId]);
        $expInfo = $getExpInfo->fetch();
        $expName = $expInfo[0];
        $expDesc = $expInfo[1]
        ?>
                            <tr>
                                <th scope="row">
                                    <?php echo $i ?>
                                </th>
                                <td>
                                    <?php echo $expName ?>
                                </td>
                                <td>
                                    <?php echo $testType ?>
                                </td>
                                <td>
                                    <?php echo $testScore ?>
                                </td>
                                <td>
                                    <?php echo $expDesc ?>
                                </td>
                                <td>
                                    <?php echo $campName ?>
                                </td>
                            </tr>
<?php }
}
?>
                    </tbody>
                </table>
        </div>

    </body>

    <?php require_once "includes/dependencies.php";?>

    </html>