<?php

header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, POST');
//header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');

//on off game
if (isset($_POST['switch']) && isset($_POST['id'])){
    include_once '../Model/Game.php';
    $gameProvider = new Game();

    echo json_encode($gameProvider->onOffGame($_POST['id']));
}

// get oneGame (modal)
elseif(isset($_GET['update']) && isset($_GET['id'])){
    include_once '../Model/Game.php';
    $gameProvider = new Game();

    echo json_encode($gameProvider->getOne($_GET['id']));
}

//update game
elseif(isset($_POST['update']) && isset($_POST['id'])){
    include_once '../Model/Game.php';
    $gameProvider = new Game();
    
    $data = array(
        'id_game' => $_POST['id'],
        'name_game' => $_POST['name'],
        'genres_game' => $_POST['genres'],
        'nb_max_players_game' => $_POST['nbMaxPlayers'],
        'headline_game' => $_POST['headline'],
        'on_off_game' => $_POST['onOff'],
        'id_thumbnail' => $_POST['idThumbnail']
    );
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] != 4){
        $file = $_FILES['thumbnail'];
    }
    else{
        $file = false;
    }

    echo json_encode($gameProvider->updateGame($data, $file));
}

//insert game
elseif(isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] != 4){
    include_once '../Model/Game.php';
    $gameProvider = new Game();

    $data = array(
        'id_game' => $_POST['id'],
        'name_game' => $_POST['name'],
        'genres_game' => $_POST['genres'],
        'nb_max_players_game' => $_POST['nbMaxPlayers'],
        'headline_game' => $_POST['headline'],
        'on_off_game' => $_POST['onOff'],
    );

    echo json_encode($gameProvider->insertGame($data, $_FILES['thumbnail']));
}