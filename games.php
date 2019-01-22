<?php

header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET');
//header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');

    if (isset($_GET['top'])){
        include_once 'Model/Game.php';
        $gameProvider = new Game();
        echo $gameProvider->getTop();
    }
    elseif (isset($_GET['last'])){
        include_once 'Model/Game.php';
        $gameProvider = new Game();
        echo $gameProvider->getLast();
    }
    elseif (isset($_GET['headline'])){
        include_once 'Model/Game.php';
        $gameProvider = new Game();
        echo $gameProvider->getHeadline();
    }
?>