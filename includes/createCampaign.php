<br>
<div class="container">
    <div class="card card-container newCampaign">
        <form class="form" role="form" method="post" action="campaign.php" accept-charset="UTF-8" id="createCampaignForm">

            <h5>Informations</h5>
            <br>
            <div class="form-group row">
                <label class="col-2">Nom de la campagne: </label>
                <input type="text" class="form-control col-8" name="newCampaignName" placeholder="Entrez le nom de votre campagne" required>
            </div>

            <div class="form-group row">
                <label class=" col-2">Description :</label>
                <textarea class="form-control col-8" name="newCampaignDesc" placeholder="Description de votre campagne" rows="3"></textarea>
            </div>

            <div class="form-group row" id="adminNames">
                <label class="col-2">Expérimentateurs : </label>
                <input list="admins" type="text" class="form-control col-sm-8" id="campaignAdmins" onInput='onAdminClick()' placeholder="Entrez le nom d'un autre expérimentateur (laissez vide si vous ne souhaitez pas en ajouter)">
                <datalist id="admins">
                </datalist>
            </div>
            <div id="addedAdmins"></div>




            <br>
            <h5>Expériences</h5>
            <br>

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
                            <input type="text" class="form-control" name="expNames[]" placeholder="Entrez le nom de votre expérience">
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
            <button type="submit" class="btn btn-primary col-2 float-left">Créer la campagne</button>


        </form>
    </div>

</div>

<!-- Sert pour passer l'id de l'utilisateur au js (impossible de le récupérer depuis le retrieveUsers.php > erreur du a l encodage en json) -->
<script>
    let userId = "<?php echo $_SESSION['login'] ?>"
</script>
<script src="includes/campaign.js"></script>