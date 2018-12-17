<?php

header('Access-Control-Allow-Methods: POST');

//on off game
if (isset($_POST['switch']) && isset($_POST['id'])){
    include_once 'Class/Game.php';
    $gameProvider = new Game();

    echo $gameProvider->onOffGame($_POST['id']);
}

// get oneGame (modal)
elseif(isset($_GET['update']) && isset($_GET['id'])){
    include_once 'Class/Game.php';
    $gameProvider = new Game();

    echo $gameProvider->getOne($_GET['id']);    
}

//update game
elseif(isset($_POST['update']) && isset($_POST['id'])){
    include_once 'Class/Game.php';
    $gameProvider = new Game();
    
    $data = array(
        'id_game' => $_POST['id'],
        'name_game' => $_POST['name'],
        'genre_game' => $_POST['genre'],
        'number_players_game' => $_POST['numberPlayers'],
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

    echo $gameProvider->updateGame($data, $file);
}

//insert game
elseif(isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] != 4){
    include_once 'Class/Game.php';
    $gameProvider = new Game();

    echo $gameProvider->insertGame($_POST['name'], $_FILES['thumbnail'], "1", "2", "1");
}

//
//include 'Class/Game.php';
//    $test = new Game();
//    $result = $test->getLast();
//    var_dump($result);