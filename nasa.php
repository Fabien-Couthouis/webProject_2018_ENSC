<?php
session_start();
require_once "includes/functions.php";
needLogin();
?>
    <!DOCTYPE html>

    <html>

    <?php include "includes/head.php";?>
    <?php include "includes/header.php";?>

    <body>

        <?php

$idExp = $_GET['idExp'];
$iduser = $_SESSION['login'];

//On vérifie que la campagne n'est pas terminée
$getState = getDb()->prepare('select etat from campagne WHERE id= (SELECT idCamp from experience WHERE id = ?)');
$getState->execute([$idExp]);
$state = $getState->fetchAll(PDO::FETCH_COLUMN)[0];

//On vérifie que l'utilisateur n'ai pas déjà répondu
$getUsers = getDb()->prepare('select idUtilisateur from reponse WHERE idExp = ?');
$getUsers->execute([$idExp]);
$users = $getUsers->fetchAll(PDO::FETCH_COLUMN);

if ($state == false) {
    echo "Cette campagne est terminée";
} else if (in_array($iduser, $users)) {
    echo "Vous avez déjà répondu à cette expérience.";
} else {
    if (!empty($_GET['status'])) {
        $score = 0;
        for ($j = 0; $j < 6; $j++) {
            $score += $_POST["$j"];
        }
        $score = $score / 6;

        $getCampaigns = getDb()->prepare('select idCamp from experience WHERE id=?');
        $getCampaigns->execute(array($idExp));
        $idC = intval($getCampaigns->fetch());

        $id = getNewId('reponse');
        $ajout = getDb()->prepare('insert into reponse(id,idExp,typeTest,idUtilisateur,score) values (?,?,?,?,?)');
        $ajout->execute(array($id, $idExp, 'NASA-TLX', $iduser, $score));

        if ($ajout->rowCount() == 1) {
            echo "Votre réponse a bien été enregistrée. Merci de votre participation.";
        }
    } else {
        $getExperience = getDb()->prepare("SELECT nom, descr from experience WHERE id=?");
        $getExperience->execute([$idExp]);
        $experience = $getExperience->fetchAll();

        $expName = $experience[0][0];
        $expDesc = $experience[0][1];

        $getQuestionInfo = getDb()->prepare('select question, indic from nasa');
        $getQuestionInfo->execute();
        $info = $getQuestionInfo->fetchAll();

        ?>
            <div class="container">
                <br>
                <h2 class="text-center">NASA-TLX</h2>
                <br>
                <br>
                <h4>
                    <u>Nom de l'expérience </u>:
                    <?php echo $expName ?>
                </h4>
                <br>
                <br>
                <h5>
                    <u>Description </u>:
                    <?php echo $expDesc ?>
                    </h4>
                    <br>
                    <br>
                    <br>



                        <form class="form" role="form" method="post" action="nasa.php?status=completed&idExp=<?php echo $_GET['idExp'] ?>" accept-charset="UTF-8"
                            id="nasa">
                            <br>

                            <?php
                            //Génération de chaque question
                            for ($j = 0; $j < 6; $j++) {
                                        $question = $info[$j][0];
                                        $indic = $info[$j][1];?>

                                <div class="form-group" id="<?php echo " nasa$j " ?>">
                                <label class=" align-self-start agreeDisagree"> <?php echo $j + 1 . ". " . $indic; ?> </label>
                                    <div class="slidecontainer row text-justify">

                                        <span class="col-5">
                                            <?php echo $question;?>
                                        </span>



                                        <input type="range" min="1" max="20" value="10" name="<?php echo $j ?>" class="offset-1 slider col align-self-center" required>
                                        </div>
                                        <div class="row align-self-center agreeDisagree">
                                            <span class="offset-6 col-5">Bas</span>
                                            <span class="">Haut</span>
                                            <br>
                                        </div>
                                    </div>
                                    <br>
                                    <br>


                                <?php
                                }?>

                                    <br>
                                    <div class="text-right">
                                        <button type="submit" class="btn btn-secondary">Valider</button>
                                    </div>
                        </form>
                        <br>



            </div>
            <br>

            <?php }
}?>
    </body>

    <?php include "includes/dependencies.php";?>

    </html>