<?php
session_start();
require_once "functions.php";
needLogin();

//Permet de télécharger une expérience au format CSV

$idUtil = $_SESSION['login'];
if (!empty($_GET['idExp']) && verifRights($idUtil, $_GET['idExp'])) {
    $expToDownload = $_GET['idExp'];

    $getExp = getDb()->prepare("SELECT nom, idCamp FROM experience where id=?");
    $getExp->execute([$expToDownload]);
    $exp = $getExp->fetchAll();

    $expName = $exp[0][0];
    $idCamp = $exp[0][1];
    $fileName = $expName . '-' . date("d-m-Y") . '.csv';

    //Gestion du téléchargement
    header('Content-type: text/csv');
    header("Content-Disposition: attachment; filename=\"$fileName\" ");
    ob_clean(); // discard any data in the output buffer (if possible)
    flush(); // flush headers (if possible)

    $fp = fopen('php://output', 'w');

    fputcsv($fp, ['id', 'idCamp', 'idExp', 'typeTest', 'idUtilisateur', 'score'], ';');

    $j = 0;

    $info = array();
    $req = "SELECT id,	idExp,	typeTest, idUtilisateur, score  FROM reponse where idExp='$expToDownload'";
    foreach (getDb()->query($req) as $row) {
        $info[] = $row['id'];
        $info[] = $idCamp;
        $info[] = $row['idExp'];
        $info[] = $row['typeTest'];
        $info[] = $row['idUtilisateur'];
        $info[] = $row['score'];

        $tranche = array_slice($info, $j, $j + 6);

        fputcsv($fp, $tranche, ';');

        $j += 6;
    }

    fclose($fp);
} else {
    echo "Vous ne pouvez pas accèder à cette expérience.";
}
