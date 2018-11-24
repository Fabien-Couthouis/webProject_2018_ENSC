<?php
session_start();
require_once "includes/functions.php";
needLogin();
?>

    <!DOCTYPE html>
    <html>

    <?php
include "includes/head.php";
include "includes/header.php";

$idCamp = $_GET['idCamp'];
$idUtil = $_SESSION['login'];

$getCampaign = getDb()->prepare('select nom, descr, dateCreation, etat from campagne WHERE id=?');
$getCampaign->execute([$idCamp]);
$campaign = $getCampaign->fetchAll();

$getAdmins = getDb()->prepare('select idutilisateur from gerer WHERE idCamp=?');
$getAdmins->execute([$idCamp]);
$admins = $getAdmins->fetchAll();

?>


    <body class="campaign">

        <br>

        <div class="container">
            <h2 class="text-center">Modification de ma campagne</h2>
            <br>
            <div class="card card-container newCampaign">
                <form class="form" role="form" method="post" action="campaign.php?campToModify=<?php echo $idCamp ?>" accept-charset="UTF-8"
                    id="createCampaignForm">

                    <h5>Informations</h5>
                    <br>
                    <div class="form-group row">
                        <label class="col-2">Nom de la campagne: </label>
                        <input type="text" class="form-control col-8" autocomplete="off" name="elementsToModify[nom]" placeholder='<?php echo $campaign[0][0] ?>'>
                    </div>

                    <div class="form-group row">
                        <label class=" col-2">Description :</label>
                        <textarea class="form-control col-8" name="elementsToModify[descr]" rows="3" placeholder="Nouvelle description"></textarea>
                    </div>

                    <div class="form-group row" id="adminNames">
                        <label class="col-2">Expérimentateurs : </label>
                        <input list="admins" type="text" class="form-control col-sm-8" id="campaignAdmins" onInput='onAdminClick()' placeholder="Entrez le nom d'un autre expérimentateur (laissez vide si vous ne souhaitez pas en ajouter)">
                        <datalist id="admins">
                        </datalist>
                    </div>

                    <div id="addedAdmins">
<?php
foreach ($admins as $admin) {
    if ($admin[0] != $idUtil) {
        ?>

                        <span class='adminNames'>
                            <i class="fa fa-minus-circle" onClick=removeAdmin(this.parentNode)> </i>
                            <?php echo ' ' . $admin[0] ?>
                            <input hidden type="text" name="adminNames[]" value="<?php echo $admin[0] ?>">
                            <br>
                        </span>

                        <?php
} else {
        ?>
                        <span class='adminNames'>
                            <input hidden type="text" name="adminNames[]" value="<?php echo $admin[0] ?>">
                        </span>

                        <?php

    }
}
?>


                    </div>

                    <br>
                    <button type="button" id="addExperience" class="btn btn-secondary col-2 float-right">Ajouter une expérience</button>
                    <br>
                    <button type="submit" class="btn btn-primary col-2 float-left">Valider</button>

            </div>

            </form>

        </div>

        <!-- Sert pour passer l'id de l'utilisateur au js (impossible de le récupérer depuis le retrieveUsers.php > erreur du a l encodage en json) -->
        <script>
            let userId = "<?php echo $_SESSION['login'] ?>"
        </script>
        <script src="includes/campaign.js"></script>

    </body>

    </html>