<div class="authDiv">

    <h3 class="text-center">Veuillez vous connecter pour continuer</h3>
    <hr>
    <img class="login-img" src="//ssl.gstatic.com/accounts/ui/avatar_2x.png" />

    <?php if (isset($error)) {?>
    <div class="alert alert-danger">
        <strong>Erreur ! </strong>
        <?=$error?>
    </div>
    <?php }?>

    <?php
    $action =  "login.php";
    if (!empty($_GET['redirect'])){
        $action = "login.php?redirect=" . $_GET['redirect'];
    }
    ?>
    <form class="form-auth form-horizontal" role="form" action="<?php echo $action ?>" method="post">

        <div class="input-group ">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="fa fa-user"></i>
                </span>
            </div>

            <input type="text" name="login" class="form-control" placeholder="Entrez votre login" required autofocus>
        </div>

        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="fa fa-lock"></i>
                </span>
            </div>
            <input type="password" name="password" class="form-control" placeholder="Entrez votre mot de passe" required>
        </div>
        <br>

        <div class="form-group">
            <button type="submit" class="btn btn-lg btn-primary btn-block btn-auth">Se connecter</button>
        </div>

    </form>
</div>