<?php
$getExperiences = getDb()->prepare("SELECT * from experience WHERE idCamp=?");
$getExperiences->execute([$id]);
$experiences = $getExperiences->fetchAll();

$getCampaign = getDb()->prepare('select etat from campagne WHERE id=?');
$getCampaign->execute([$id]);
$etatCamp = $getCampaign->fetch();
?>

    <table class="table viewCampaignTable">

        <tbody>

            <tr>
                <th scope="row">Description</th>
                <td>
                    <?php echo $descr ?>
                </td>
            </tr>

            <tr>
                <th scope="row">Expérimentateurs</th>
                <td>
                    <?php
for ($i = 0; $i < count($admins); $i++) {
    echo $admins[$i];

    if ($i != count($admins) - 1) {
        echo ", ";
    }
}
?>
                </td>
            </tr>

            <tr>
                <th scope="row">Date de création</th>
                <td>
                    <?php echo $dateCreation ?>
                </td>
            </tr>

            <tr>
                <th scope="row">Expériences</th>
                <?php
$getNbExp = getDb()->prepare("SELECT count(*) from experience WHERE idCamp=?");
$getNbExp->execute([$id]);
$nbExp = $getNbExp->fetch();

if ($nbExp[0] == 0) {?>
                    <td>
                            <a class="linkCampaign" href="addExp.php?idCamp=<?php echo $id ?>">
                            <i id="modifyButton" class="fa fa-edit campaign-icon"> </i> Ajouter une expérience</a>
                    </td>
                    <?php } else {?>

                    <td>
                        <?php
foreach ($experiences as $experience) {
    $expId = $experience[0];
    $expName = $experience[1];
    $expDesc = $experience[2];
    $expDate = $experience[3];
    $expType = $experience[4];
    $expCamp = $experience[5];

    $getNb = getDb()->prepare("SELECT count(*) from reponse WHERE idExp=?");
    $getNb->execute([$expId]);
    $nb = $getNb->fetch();

    $getScore = getDb()->prepare("SELECT sum(score) from reponse WHERE idExp=?");
    $getScore->execute([$expId]);
    $score = $getScore->fetch();
    ?>

                        <table class="table viewExpTable">

                            <thead class="thead-light">
                                <tr>
                                    <th scope="row">
                                        <?php
if ($etatCamp[0] == 1) {
        if ($expType == "SUS") {
            $testLink = "localhost/projet-web/sus.php?idExp=" . $expId;
        } else {
            $testLink = "localhost/projet-web/nasa.php?idExp=" . $expId;
        }

        ?>

                                        <a class="getLink" title="Obtenir le lien partageable" onCLick='prompt("URL du test :", "<?php echo $testLink ?>"); return false'
                                            href="#">
                                            <i class="fa fa-link campaign-icon"> </i>
                                        </a>
                                        <?php }?>
                                        <?php echo $expName ?>
                                    </th>
                                    <th>
                                        <a class="linkCampaign" href="campaign.php?expToSuppress=<?php echo $expId ?>&expCamp=<?php echo $expCamp ?>" onclick="return(confirm('Etes-vous sûr de vouloir supprimer l'experience <?php echo $expName ?> ?'));">
                                            <i class="fa fa-trash campaign-icon"></i> Supprimer</a>
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <th scope="row">Description</th>
                                    <td>
                                        <?php echo $expDesc ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Type</th>
                                    <td>
                                        <?php echo $expType ?>
                                    </td>
                                </tr>

                                <tr>
                                    <th scope="row">Date de création</th>
                                    <td>
                                        <?php echo $expDate ?>
                                    </td>
                                </tr>

                                <tr>
                                    <th scope="row">Nombre de participant</th>
                                    <td>
                                        <?php echo $nb[0] ?>
                                    </td>
                                </tr>

                                <tr>
                                    <th scope="row">Moyenne des scores</th>
                                    <td>
                                        <?php if ($nb[0] == 0) {
        echo "Aucune réponse pour le moment";
    } else {
        printf("%.3f", $score[0] / $nb[0]);
    }?>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <a class="linkCampaign2" href="includes/downloadExp.php?idExp=<?php echo $expId ?>">
                                            <i class="fa fa-download campaign-icon"> </i> Télécharger les données</a>
                                    </td>

                                  <td>
                                        <a class="linkCampaign" href="modifExp.php?idExp=<?php echo $expId ?>">
                                        <i id="modifyButton" class="fa fa-edit campaign-icon"> </i> Modifier cette expérience</a>
                                  </td>

                    </td>

                    </tr>

                    </tbody>
                    </table>

                    <?php }
    if ($etat == true) {

        ?>

                            <a class="linkCampaign" href="addExp.php?idCamp=<?php echo $id ?>">
                            <i id="modifyButton" class="fa fa-edit campaign-icon"> </i> Ajouter une expérience</a>

                    </td>
                    <?php }
}?>

            </tr>

        </tbody>
    </table>