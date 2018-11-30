<?php

header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: POST');
//header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');


    if(isset($_GET['all'])){
        include_once 'Class/Game.php';
        $gameProvider = new Game();
        echo $gameProvider->getAll();
    }
    elseif (isset($_GET['top'])){
        include_once 'Class/Game.php';
        $gameProvider = new Game();
        echo $gameProvider->getTop();
    }
    elseif (isset($_GET['last'])){
        include_once 'Class/Game.php';
        $gameProvider = new Game();
        echo $gameProvider->getLast();
    }
    elseif (isset($_GET['headline'])){

    }
    elseif(isset($_FILES['file'])){
        include_once 'Class/Game.php';
        $gameProvider = new Game();
        echo $gameProvider->insertGame($_POST['nameGame'], $_FILES['file'], "1", "2", "1");
    }

?>