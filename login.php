<?php
session_start();
require_once "includes/functions.php";

//Gestion des onglets

if (!empty($_GET['activetab']) && $_GET['activetab'] == 'register-tab') {
    $activeTab[0] = '';
    $activeTab[1] = 'active';
} else {
    $activeTab[0] = 'active';
    $activeTab[1] = '';
}

//LOGIN
if (!empty($_POST['login']) and !empty($_POST['password'])) {
    $login = $_POST['login'];
    $password = $_POST['password'];
    $auth = getDb()->prepare('select * from utilisateur where id=? and mdp=?');
    $auth->execute(array($login, $password));
    
    if ($auth->rowCount() == 1) {
        // Authentication successful
        $_SESSION['login'] = $login;

        //Redirection
        if (!empty($_GET['redirect'])) {
            redirect($_GET['redirect']);
        } else {
            redirect("index.php");
        }

    } else {
        $error = "Utilisateur non reconnu";
    }
}
//REGISTER
if (!empty($_POST['registerId']) && !empty($_POST['registerPassword']) && ($_POST['registerPassword'] == $_POST['registerPassword2']) && !empty($_POST['registerMail'])) {
    $id = $_POST['registerId'];
    $password = $_POST['registerPassword'];
    $mail = $_POST['registerMail'];

    $verifIfExists = getDb()->prepare("SELECT * FROM utilisateur WHERE id = ?");
    $verifIfExists->execute([$id]);
    if ($verifIfExists->rowCount() == 0) {
        $insertUser = getDb()->prepare("INSERT INTO utilisateur (id, mdp, mail) VALUES (:id, :mdp, :mail)");
        $insertUser->execute(array(
            "id" => $id,
            "mdp" => $password,
            "mail" => $mail,
        ));
        if ($insertUser->rowCount() == 1) {
            // Inscription réussie
            $_SESSION['login'] = $id;
            redirect("index.php");
        } else {
            $error = "Erreur inconnue.";
        }

    } else {
        $error = "Échec de l'inscription : l'utilisateur existe déjà ! Veuillez choisir un autre identifiant.";
    }
}

?>

    <!DOCTYPE html>

    <html>


<?php
require_once "includes/head.php";
require_once "includes/header.php";
?>


        <body class="authentificationPage">
            <br>

            </div>

            </div>
            <nav>
                <div class="nav nav-tabs nav-justified" role="tablist">
                    <a class=" nav-item page-tabs nav-link <?php echo $activeTab[0] ?>" id="connexion-tab" data-toggle="tab" href="#connexion-content" role="tab"
                        aria-controls="connexion-content" aria-selected="true">Connexion</a>
                    <a class="nav-item page-tabs nav-link <?php echo $activeTab[1] ?>" id="register-tab" data-toggle="tab" href="#register-content" role="tab"
                        aria-controls="register-content" aria-selected="false">Inscription</a>
                </div>
            </nav>

            <div class="tab-content">
                <div class="tab-pane <?php echo $activeTab[0] ?>" id="connexion-content" role="tabpanel" aria-labelledby="connexion-tab">
                        <?php require_once "includes/signIn.php"?> </div>
                <div class="tab-pane <?php echo $activeTab[1] ?>" id="register-content" role="tabpanel" aria-labelledby="register-tab">
                    <?php require_once "includes/register.php"?>
                </div>
            </div>

            <br>
            <br>
            <br>
            <?php require_once "includes/dependencies.php";?>
        </body>


    </html>