<?php
$campaigns = getAllCampaigns($_SESSION['login']);
?>
    <table class="table">
<?php
for ($c = 0; $c < count($campaigns); $c++) {
    $id = $campaigns[$c]['id'];
    $name = $campaigns[$c]['nom'];
    $descr = $campaigns[$c]['descr'];
    $dateCreation = $campaigns[$c]['dateCreation'];
    $etat = $campaigns[$c]['etat'];
    $admins = $campaigns[$c]['admins'];

    if ($etat == true) {

        if ($dateCreation == "0000-00-00") {
            $dateCreation = "Non précisé";
        }

        ?>
                <tbody>
                <tr>
                    <th scope="row">
                        <a>
                            <?php echo $c + 1 . ". " . $name ?>
                        </a>
                    </th>
                    <td>
                        <a class="dropdown-toggle linkCampaign" data-toggle="collapse" href="<?php echo "#viewCampaign$id" ?>" aria-expanded="false">
                            <i class="fa fa-eye campaign-icon"> </i>Consulter</a>
                        <div class="collapse" id="<?php echo "viewCampaign$id" ?>">
                            <div class="card card-body viewcampaignCard">
                                <?php $dontShowUrl = false?>
                                <?php include "viewCampaign.php";?>
                            </div>
                        </div>
                    </td>
                    <td>
                            <a class="linkCampaign" href="modifCampaign.php?idCamp=<?php echo $id ?>">
                            <i id="modifyButton" class="fa fa-edit campaign-icon"> </i> Modifier</a>
                    </td>
                    <td>
                        <a class="linkCampaign" href="campaign.php?campToFinish=<?php echo $id ?>" onclick="return(confirm('Etes-vous sûr de vouloir terminer la campagne <?php echo $name ?> ?'));">
                            <i class="fa fa-stop campaign-icon"> </i>Terminer</a>
                    </td>
                    <td>
                        <a class="linkCampaign"  href="campaign.php?campToSuppress=<?php echo $id ?>" onclick="return(confirm('Etes-vous sûr de vouloir supprimer la campagne <?php echo $name ?> ?'));">
                            <i class="fa fa-trash campaign-icon"> </i>Supprimer</a>
                    </td>

                </tr>
                </tbody>

<?php }
}

?>
    </table>

    <?php require_once "includes/dependencies.php";?>