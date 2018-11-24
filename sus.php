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
        for ($j = 1; $j <= 10; $j++) {
            if ($j % 2 == 0) {
                $score += (5 - $_POST["$j"]);
            } else {
                $score += ($_POST["$j"] - 1);
            }
        }

        $getCampaigns = getDb()->prepare('select idCamp from experience WHERE id=?');
        $getCampaigns->execute(array($idExp));
        $idC = intval($getCampaigns->fetch());

        $id = getNewId('reponse');
        if ($id == null) {
            $id = 0;
        }
        $ajout = getDb()->prepare('insert into reponse(id,idExp,typeTest,idUtilisateur,score) values (?,?,?,?,?)');
        $ajout->execute(array($id, $idExp, 'SUS', $iduser, $score));

        if ($ajout->rowCount() == 1) {
            echo "Votre réponse a bien été enregistrée. Merci de votre participation.";
        }
    } else {

        $getExperience = getDb()->prepare("SELECT nom, descr from experience WHERE id=?");
        $getExperience->execute([$idExp]);
        $experience = $getExperience->fetchAll();
        $expName = $experience[0][0];
        $expDesc = $experience[0][1];

        ?>


                <div class="container">
                    <br>
                    <h2 class="text-center">SUS (System Usability Scale) Test</h2>
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

                        <div class="row agreeDisagree">
                            <div class="col-5 offset-4">Pas du tout d'accord</div>
                            <div class="col-2">D'accord</div>
                        </div>
                        <br>

                        <form class="form" role="form" method="post" action="sus.php?status=completed&idExp=<?php echo $_GET['idExp'] ?>" accept-charset="UTF-8"
                            id="<?php echo " sus " ?>">

                            <?php
for ($j = 1; $j <= 10; $j++) {?>

                                <div class="form-group" id="<?php echo " sus$j " ?>">
                                    <div class="custom-radios row">
                                        <?php $rqst = getDb()->prepare('select question from sus where id=?');
            $rqst->execute(array($j));?>
                                        <h6 class="col-4">
                                            <?php echo "$j. " ?>
                                            <?php echo $rqst->fetchColumn() ?>
                                        </h6>
                                        <br>

                                        <span class="col-1"></span>

                                        <?php
for ($i = 1; $i <= 5; $i++) {?>

                                        <div class="col-1">
                                            <input type="radio" class="<?php echo " color-$i " ?>" id="<?php echo $i, $j ?>" name="<?php echo $j ?>" value="<?php echo $i ?>"
                                                required>
                                            <label for="<?php echo $i, $j ?>">
                                                <span>
                                                    <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/242518/check-icn.svg" alt="Checked Icon" />
                                                </span>
                                            </label>
                                            <span class="col-1 susValue">
                                                <?php echo $i ?>
                                            </span>
                                        </div>

                                        <?php
}?>

                                    </div>
                                </div>

                                <?php
}?>


                                    <br>
                                    <div class="text-right">
                                        <button type="submit" class="btn btn-secondary">Valider</button>
                                    </div>

                        </form>
                </div>

                </br>
                <?php }
}?>
    </body>

    <?php include "includes/dependencies.php";?>

    </html>