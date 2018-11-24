<?php
session_start();
require_once "includes/functions.php";
needLogin();
$idUtil = $_SESSION['login'];

//Requete nécessaire à plusieurs fonctions
$insertJointure = getDb()->prepare("INSERT INTO gerer (id, idCamp, idUtilisateur) VALUES (:id, :idCamp, :idUtilisateur)");

//Modification d'une campagne
if (!empty($_GET['campToModify'])) {
    $campToModify = $_GET['campToModify'];

    // On prend soin de vérifier que l'utilisateur dispose des droits sur la campagne dont l'id est passé en paramètre dans l'url !
    if (verifRights($idUtil, $campToModify)) {
        //On modifie tous les champs renseignés par l'utilisateur
        $elementsToModify = $_POST['elementsToModify'];

        foreach ($elementsToModify as $columnName => $value) {
            if ($value != "") {
                $modify = getDb()->prepare("UPDATE campagne SET {$columnName} = ? WHERE id = ?");
                $modify->execute(array($value, $campToModify));
            }
        }

        //Modification des administrateurs
        $getActualAdminList = getDb()->prepare("SELECT idUtilisateur from gerer WHERE idCamp = ?");
        $getActualAdminList->execute([$campToModify]);

        $actualAdminList = $getActualAdminList->fetchAll(PDO::FETCH_COLUMN);
        if (!empty($_POST['adminNames'])) {
            $newAdminList = $_POST['adminNames'];

            foreach ($newAdminList as $newAdmin) {
                $adminId = $newAdmin;
                //Si un id d admin est présent dans la nouvelle liste mais pas dans la bdd, on l'ajoute
                if (in_array($adminId, $newAdminList) && !in_array($adminId, $actualAdminList)) {
                    $insertJointure->execute(array(
                        "id" => getNewId('gerer'),
                        "idCamp" => $campToModify,
                        "idUtilisateur" => $adminId,
                    ));
                }
            }
            foreach ($actualAdminList as $actualAdmin) {
                echo !in_array($adminId, $newAdminList);
                $adminId = $actualAdmin;

                //Si un id d admin est présent dans la bdd mais pas dans la nouvelle liste, on le supprime
                if (!in_array($adminId, $newAdminList) && in_array($adminId, $actualAdminList)) {
                    echo 'a';
                    supressFromDb('idutilisateur', 'gerer', $adminId);
                }

            }
        } else {
            echo 'Erreur ! Vous devez au moins possèder un administrateur de campagne !';
        }
    }
}

//Fin d'une campagne
if (!empty($_GET['campToFinish'])) {
    $campToFinish = $_GET['campToFinish'];
// On prend soin de vérifier que l'utilisateur dispose des droits sur la campagne dont l'id est passé en paramètre dans l'url !
    if (verifRights($idUtil, $campToFinish)) {
        $modifEtat = getDb()->prepare("UPDATE campagne SET etat=FALSE WHERE id=?");
        $modifEtat->execute(array($campToFinish));
    }
}

//Supression d'une campagne
if (!empty($_GET['campToSuppress'])) {
    $campToSuppress = $_GET['campToSuppress'];
// On prend soin de vérifier que l'utilisateur dispose des droits sur la campagne dont l'id est passé en paramètre dans l'url !
    if (verifRights($idUtil, $campToSuppress)) {
        //Suppression de toutes les reponses de toutes les expériences de la campagne
        $getExpIds = getDb()->prepare("SELECT id from experience WHERE idcamp=?");
        $getExpIds->execute([$campToSuppress]);
        $expIds = $getExpIds->fetchAll(PDO::FETCH_COLUMN);
        foreach ($expIds as $expId) {
            supressFromDb('idExp', 'reponse', $expId);
        }

        supressFromDb('idcamp', 'experience', $campToSuppress);
        supressFromDb('idcamp', 'gerer', $campToSuppress);
        supressFromDb('id', 'campagne', $campToSuppress);
    }
}

//Supression d'une experience
if (!empty($_GET['expToSuppress'])) {
    $expToSuppress = $_GET['expToSuppress'];
    $campExp = $_GET['expCamp'];
// On prend soin de vérifier que l'utilisateur dispose des droits sur la campagne à laquelle appartient l'expérience dont l'id est passé en paramètre dans l'url !
    if (verifRights($idUtil, $campExp)) {
        supressFromDb('idExp', 'reponse', $expToSuppress);
        supressFromDb('id', 'experience', $expToSuppress);
    }
}

//Insertions dans la table campagne
if (!empty($_POST['newCampaignName'])) {
    $idCamp = getNewId('campagne');

    $insertCampagne = getDb()->prepare("INSERT INTO campagne (id, nom, descr, dateCreation, etat) VALUES (:id, :nom, :descr, :dateCreation, :etat)");
    $insertCampagne->execute(array(
        "id" => $idCamp,
        "nom" => $_POST['newCampaignName'],
        "descr" => $_POST['newCampaignDesc'],
        "dateCreation" => date("Y-m-d"),
        "etat" => true,
    ));

    //Insertions dans la table de la jointure

    //On ajoute le créateur de la campagne

    if (!empty($_POST['adminNames'][0]) && $_POST['adminNames'][0] != "") {
        $adminNames = $_POST['adminNames'];
        $adminNames[] = $idUtil;
    } else {
        $adminNames = [$idUtil];
    }
    foreach ($adminNames as $adminName) {
        $insertJointure->execute(array(
            "id" => getNewId('gerer'),
            "idCamp" => $idCamp,
            "idUtilisateur" => $adminName,
        ));
        if ($insertJointure->rowCount() != 1) {
            $error = "Utilisateur "+$adminName+" non reconnu";
        }
    }

    //Insertion dans la table des expériences
    $expNames = $_POST['expNames'];
    $expDescs = $_POST['expDescs'];
    $expTypes = $_POST['expTypes'];

    $insertExperience = getDb()->prepare("INSERT INTO experience (id, nom, descr, dateCreation, typeExp, idCamp) VALUES (:id, :nom, :descr, :dateCreation, :typeExp, :idCamp)");
    for ($i = 0; $i < count($expNames); $i++) {
        $expName = $expNames[$i];
        $expDesc = $expDescs[$i];
        $expType = $expTypes[$i];
        $expDate = date("Y-m-d");

        if ($expName != "") {
            $insertExperience->execute(array(
                "id" => getNewId('experience'),
                "nom" => $expName,
                "descr" => $expDesc,
                "dateCreation" => $expDate,
                "typeExp" => $expType,
                "idCamp" => $idCamp,
            ));
        }
    }
    if ($insertCampagne->rowCount() == 1 && $insertJointure->rowCount() == 1 && $insertExperience->rowCount() == 1) {
        // Insertions réussies
        redirect("campaign.php");
    }
}

//Modification d'une experience
if (!empty($_GET['expToModify'])) {
    $expToModify = $_GET['expToModify'];

    //On modifie tous les champs renseignés par l'utilisateur
    $elementsToModify = $_POST['elementsToModify'];

    foreach ($elementsToModify as $columnName => $value) {
        if ($value != "") {
            $modify = getDb()->prepare("UPDATE experience SET {$columnName} = ? WHERE id = ?");
            $modify->execute(array($value, $expToModify));
        }
    }
}

//Insertion dans la table des expériences depuis viewCampaign
if (!empty($_GET['idCampAddExp'])) {
    $idCamp = $_GET['idCampAddExp'];
    $expNames = $_POST['expNames'];
    $expDescs = $_POST['expDescs'];
    $expTypes = $_POST['expTypes'];

    $insertExperience = getDb()->prepare("INSERT INTO experience (id, nom, descr, dateCreation, typeExp, idCamp) VALUES (:id, :nom, :descr, :dateCreation, :typeExp, :idCamp)");
    for ($i = 0; $i < count($expNames); $i++) {
        $expName = $expNames[$i];
        $expDesc = $expDescs[$i];
        $expType = $expTypes[$i];
        $expDate = date("Y-m-d");

        if ($expName != "") {
            $insertExperience->execute(array(
                "id" => getNewId('experience'),
                "nom" => $expName,
                "descr" => $expDesc,
                "dateCreation" => $expDate,
                "typeExp" => $expType,
                "idCamp" => $idCamp,
            ));
        }
    }
}
?>

    <!DOCTYPE html>

    <html>

    <?php require_once "includes/head.php";?>


    <?php require_once "includes/header.php";?>

    <body class="campaign">
        <div class="container">
        <br>
            <h3 class="text-center">Mes campagnes</h3>
        </div>
        <br>

        <?php if (isset($error)) {?>
        <div class="alert alert-danger">
            <strong>Erreur ! </strong>
            <?=$error?>
        </div>
        <br>
        <?php }?>

        <nav class="nav-campaign">
            <div class="nav nav-tabs nav-justified" id="nav-tab" role="tablist">
                <a class="nav-item page-tabs nav-link active" id="nav-campaign-actual-tab" data-toggle="tab" href="#nav-campaign-actual"
                    role="tab" aria-controls="nav-campaign-actual" aria-selected="true">Campagnes en cours</a>
                <a class="nav-item page-tabs nav-link show " id="nav-campaign-ended-tab" data-toggle="tab" href="#nav-campaign-ended"
                    role="tab" aria-controls="nav-campaign-ended" aria-selected="false">Campagnes terminées</a>
                <a class="nav-item page-tabs nav-link " id="nav-campaign-create-tab" data-toggle="tab" href="#nav-campaign-create" role="tab"
                    aria-controls="nav-campaign-create" aria-selected="false">Nouvelle campagne</a>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane campaign-nav-tab active" id="nav-campaign-actual" role="tabpanel" aria-labelledby="nav-campaign-actual-tab">
                <?php require_once "includes/actualCampaigns.php"?>
            </div>
            <div class="tab-pane campaign-nav-tab" id="nav-campaign-ended" role="tabpanel" aria-labelledby="nav-campaign-ended-tab">
                <?php require_once "includes/endedCampaigns.php"?>
            </div>
            <div class="tab-pane campaign-nav-tab" id="nav-campaign-create" role="tabpanel" aria-labelledby="nav-campaign-create-tab">
                <?php require_once "includes/createCampaign.php"?>
            </div>
        </div>


    </body>



    <?php require_once "includes/dependencies.php";?>




    </html>