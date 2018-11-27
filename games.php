<?php

header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: POST');
//header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');

    include_once('connexion.php');

    if(isset($_GET['all'])){
        $sql = $bdd->prepare("SELECT * FROM game ORDER BY name_game");
        $sql->execute();

        $results = $sql->fetchAll();

//    var_dump($sql);
//    var_dump($results);

        if ($results){
            echo json_encode(array('status'=>'success', 'games'=> $results));
        }
        else{
            echo json_encode(array('status'=>'error'));
        }
    }

?>