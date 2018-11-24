<?php
// Connect to the database. Returns a PDO object
function getDb()
{
    try {
        return new PDO("mysql:host=localhost; dbname=myforms; charset=utf8", "myforms_user", "mdp",
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    } catch (Exception $e) {
        die("Erreur d'accès à la base de données : " . $e->getMessage());
    }
}

// Check if a user is connected
function isUserConnected()
{
    return isset($_SESSION['login']);
}

// Redirect to a URL
function redirect($url)
{
    header("Location: $url");
    exit;
}

// Escape a value to prevent XSS attacks
function escape($value)
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8', false);
}

//Get new id (max(id) + 1) of a table assuming the id is named 'id' in the table
function getNewId($table)
{
    $getMaxId = getDb()->prepare("SELECT MAX(id) FROM $table");
    $getMaxId->execute();
    $maxId = $getMaxId->fetch();
    return $maxId[0] + 1;
}

function supressFromDb($col, $table, $colElement)
{
    $supress = getDb()->prepare("DELETE FROM $table WHERE $col=?");
    $supress->execute([$colElement]);
}

//Redirect to login page (with re-redirection after login), if user is not connected
function needLogin()
{
    $pageUrl = $_SERVER['REQUEST_URI'];
    if (isUserConnected()) {
        return;
    } else {
        return redirect("login.php?redirect=" . $pageUrl);
    }
}

//Verify if user have rights on a campaign
function verifRights($user, $campaign)
{
    $verifRights = getDb()->prepare("SELECT id FROM gerer WHERE idUtilisateur = ? AND idCamp = ?");
    if ($verifRights->execute([$user, $campaign])) {
        return true;
    }
}

function getAllCampaigns($user)
{
    $getCampaignId = getDb()->prepare('select idCamp from gerer WHERE idUtilisateur=?');
    $getCampaignId->execute(array($user));
    $campaignIdList = $getCampaignId->fetchAll(PDO::FETCH_COLUMN);

    $campaigns = array();

    $i = 0;
    for ($i = 0; $i < count($campaignIdList); $i++) {
        $campaignInfo = getCampaignInfo($campaignIdList[$i]);
        $campaigns[$i] = $campaignInfo;
    }
    return $campaigns;
}

//Renvoi un tableau associatif des informations relatives à une campagne donnée en paramètre
function getCampaignInfo($campaignId)
{
    //Infos table campagne
    $getCampaignInfo = getDb()->prepare('select * from campagne WHERE id=?');
    $getCampaignInfo->execute(array($campaignId));
    $campaignInfo = $getCampaignInfo->fetchAll()[0];

    //Noms des admins
    $getAdmins = getDb()->prepare('select idUtilisateur from gerer WHERE idCamp=?');
    $getAdmins->execute(array($campaignId));
    $campaignAdmins = $getAdmins->fetchAll(PDO::FETCH_COLUMN);

    //Ajout des noms des admins (tableau) aux informations de la campagne
    $campaignInfo['admins'] = $campaignAdmins;

    return $campaignInfo;
}
