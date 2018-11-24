<?php
require_once "functions.php";

$getUsers = getDb()->prepare('select id from utilisateur');
$getUsers->execute();
$users = $getUsers->fetchAll(PDO::FETCH_COLUMN, 0);

//Encode les résultats sous format Json pour les récupérer après en javascript sous forme de tableau
header('Content-Type: application/json');
echo json_encode($users);
