<div class="authDiv">
    <h3 class="text-center">Inscription</h3>
    <hr>

    <div id="registerError">
        <?php if (isset($error)) {?>
        <div class="alert alert-danger">
            <strong>Erreur ! </strong>
            <?=$error?>
        </div>
        <?php }?>
        <br>
    </div>


    <form class="form form-auth" role="form" method="post" action="login.php#register-tab" accept-charset="UTF-8" id="register">

        <label for="registerId">Votre identifiant :</label>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="fa fa-user"></i>
                </span>
            </div>
            <input type="id" class="form-control" name="registerId" placeholder="Identifiant" id="registerId" required>
        </div>

        <label for="registerPassword">Votre mot de passe :</label>
        <span class="float-right" id="passwordHelp"></span>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="fa fa-lock"></i>
                </span>
            </div>
            <input type="password" class="form-control" name="registerPassword" id="registerPassword" placeholder="Mot de passe" required>
        </div>

        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="fa fa-lock"></i>
                </span>
            </div>
            <input type="password" class="form-control" name="registerPassword2" id="registerPassword2" placeholder="Veuillez retaper votre mot de passe"
                required>
            <i title="" id="passwordIcon"></i>
        </div>

        <label for="registerMail">Votre adresse mail</label>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="fa fa-at"></i>
                </span>
            </div>
            <input type="mail" class="form-control" id="registerMail" name="registerMail" aria-describedby="emailHelp" placeholder="Adresse mail" required>
        </div>

        <div class="form-group">
            <button type="submit" id="registerButton" class="btn btn-lg btn-primary btn-block btn-auth">S'inscrire</button>
        </div>
    </form>
</div>
<script src="includes/register.js"></script>