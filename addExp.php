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

?>


    <body class="campaign">

        <br>
            <h2 class="text-center">Expériences</h2>
        <br>

<div class="container">
    <div class="card card-container newCampaign">
        <form class="form" role="form" method="post" action="campaign.php?idCampAddExp=<?php echo $idCamp ?>" accept-charset="UTF-8" id="createCampaignForm">

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Type</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody id="expArea">
                    <tr>
                        <td>
                            <input type="text" class="form-control" name="expNames[]" placeholder="Entrez le nom de votre expérience" required>
                        </td>
                        <td>
                            <select name="expTypes[]" class="custom-select">
                                <option value="SUS" selected>SUS</option>
                                <option value="NASA-TLX">NASA-TLX</option>
                            </select>
                        </td>
                        <td>
                            <textarea class="form-control" name="expDescs[]" placeholder="Description de l'expérience" rows="2"></textarea>
                        </td>
                    </tr>
                </tbody>
            </table>

            <br>
            <button type="button" id="addExperience" class="btn btn-secondary col-2 float-right">Ajouter une expérience</button>
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