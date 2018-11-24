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

$idExp = $_GET['idExp'];
$idUtil = $_SESSION['login'];

$getExp = getDb()->prepare('select nom, descr,idCamp from experience WHERE id=?');
$getExp->execute([$idExp]);
$exp = $getExp->fetchAll();

$getAdmins = getDb()->prepare('select idutilisateur from gerer WHERE idCamp=?');
$getAdmins->execute([$exp[0][2]]);
$admins = $getAdmins->fetchAll();
?>


    <body class="campaign">

        <br>

        <div class="container">
            <h2 class="text-center">Modification de mon expérience</h2>
            <br>
            <div class="card card-container newCampaign">
                <form class="form" role="form" method="post" action="campaign.php?expToModify=<?php echo $idExp ?>" accept-charset="UTF-8"
                    id="createCampaignForm">

                    <h5>Informations</h5>
                    <br>
                    <div class="form-group row">
                        <label class="col-2">Nom de l'expérience: </label>
                        <input type="text" class="form-control col-8" autocomplete="off" name="elementsToModify[nom]" placeholder='<?php echo $exp[0][0]
?>'>
                    </div>

                    <div class="form-group row">
                        <label class=" col-2">Description :</label>
                        <textarea class="form-control col-8" name="elementsToModify[descr]" rows="3" placeholder="Nouvelle description" ></textarea>
                    </div>

                    <br>
                    <button type="submit" class="btn btn-primary col-2 float-left">Valider</button>

                </form>

            </div>

        </div>

        <!-- Sert pour passer l'id de l'utilisateur au js (impossible de le récupérer depuis le retrieveUsers.php > erreur du a l encodage en json) -->
        <script>
            let userId = "<?php echo $_SESSION['login'] ?>"
        </script>
        <script src="includes/campaign.js"></script>

    </body>

    </html>