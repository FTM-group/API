<?php

header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET');
//header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');

if (isset($_GET['list'])){
    include_once '../Model/RequestedGame.php';
    $requestedGameProvider = new RequestedGame();

    $data = array(
        'requested_games' => $requestedGameProvider->getRequestedGames()
    );

    var_dump($data);

}