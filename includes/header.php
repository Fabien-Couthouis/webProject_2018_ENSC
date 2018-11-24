<?php require_once "includes/functions.php";?>
<header class="header">

    <?php

if (isUserConnected()) {
    $pages = ["campaign.php", "tests.php"];

} else {
    $pages = ["login.php?redirect=campaign.php", "login.php?redirect=test.php"];
}
?>

        <nav class="navbar navbar-toggleable-md background-faded navbar-dark bg-dark justify-content inverse sticky-top">

            <a class="navbar-brand" href="index.php">
                <img src="images/logo.png" width="30" height="30" class="d-inline-block align-top" alt=""> MyForms </a>

            <a class="nav-link" href="campaign.php">Mes campagnes</a>
            <a class="nav-link" href="tests.php">Tests effectués</a>

            <?php
if (isUserConnected()) {?>
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle" href="" data-toggle="dropdown">
                        <i class="fa fa-user nav-icon">
                            <span class="connect">
                                <?php echo $_SESSION['login'] ?> </span>
                        </i>
                    </a>
                    <div id="login-dp" class="row dropdown-menu">
                        <div class="">
                            <a class="nav-link" href="logout.php">
                                <i class="fa fa-sign-out">
                                    <span class="userMenuText"> Déconnexion </span>
                                </i>
                            </a>
                        </div>
                    </div>

                    <?php } else {

    ?>

                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle" href="" data-toggle="dropdown">
                            <i class="fa fa-user nav-icon">
                                <span class="connect"> Se connecter </span>
                            </i>
                        </a>
                        <div id="login-dp" class="row dropdown-menu">
                            <div class="col-md-12">
                                <form class="form" role="form" method="post" action="login.php" accept-charset="UTF-8" id="login-nav">

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span id="fa-user-form" class="input-group-text">
                                                <i class="fa fa-user login-icon"></i>
                                            </span>
                                        </div>
                                        <input type="id" class="form-control" name="login" placeholder="Identifiant" required>
                                    </div>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span id="fa-user-form" class="input-group-text">
                                                <i class="fa fa-lock login-icon"></i>
                                            </span>
                                        </div>
                                        <input type="password" class="form-control" name="password" placeholder="Mot de passe" required>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-block btn-auth">Connexion</button>
                                    </div>
                                    <div class="help-block text-right float-left">
                                        <a class="registerLink" href="login.php?activetab=register-tab">Inscription</a>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <?php }?>
        </nav>


</header>