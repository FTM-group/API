<?php

header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET');
//header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');

if (isset($_GET['true'])){
    include_once '../Model/Game.php';
    $gameProvider = new Game();

    include_once '../Model/RequestedGame.php';
    $requestedGameProvider = new RequestedGame();

    include_once '../Model/Genre.php';
    $genreProvider = new Genre();

    include_once '../Model/Ip.php';
    $ipProvider = new Ip();

    $data = array(
        'games' => $gameProvider->getAll(),
        'requested_games' => $requestedGameProvider->getRequestedGames(),
        'genres' => $genreProvider->getAll(),
        'ips' => $ipProvider->getAll()
    );

    echo json_encode($data);
}