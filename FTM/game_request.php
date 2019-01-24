<?php

header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: POST');
//header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');


if (isset($_POST['new']) && isset($_POST['name_game']) && isset($_POST['id_user'])){
    include_once '../Model/RequestedGame.php';
    $requestedGameProvider = new RequestedGame();
    $data = array('name_game' => $_POST['name_game'], 'id_user' => $_POST['id_user']);

    echo json_encode($requestedGameProvider->addRequestedGames($data));
}