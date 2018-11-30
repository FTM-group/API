<?php

class Game{
    function getAll(){
        include_once('Bdd/connexion.php');

        $sql = $bdd->prepare("SELECT * FROM game ORDER BY name_game");
        $sql->execute();

        $results = $sql->fetchAll();
        include_once 'Bdd/deconnexion.php';

        if ($results){
            return json_encode(array('status'=>'success', 'games'=> $results));
        }
        else{
            return json_encode(array('status'=>'error'));
        }
    }

    function getTop(){
        include_once('Bdd/connexion.php');

        $sql = $bdd->prepare("SELECT * FROM game g JOIN matchmaking_archive ma ON g.id_game = ma.id_game ORDER BY name_game LIMIT 10");
        $sql->execute();

        $results = $sql->fetchAll();
        include_once 'Bdd/deconnexion.php';

        if ($results){
            return json_encode(array('status'=>'success', 'games'=> $results));
        }
        else{
            return json_encode(array('status'=>'error'));
        }
    }

    function insertGame($name, $file, $genre, $nbPlayers, $headline){

        $uploaddir = 'Thumbnails/';
        $extension = explode('.', $file['name'])[1];

        $key = '';
        $keys = array_merge(range(0, 9), range('a', 'z'));

        for ($i = 0; $i < 28; $i++) {
            $key .= $keys[array_rand($keys)];
        }

        $name_thumbnail = $key.".".$extension;

        $uploadfile = $uploaddir . $name_thumbnail;

        if (move_uploaded_file($file['tmp_name'], $uploadfile)) {
            include_once 'Bdd/connexion_user.php';

            $weight = $file['size'];

            try{
                $sql = $bdd->prepare('INSERT INTO thumbnail (name_thumbnail, weight_thumbnail) VALUES (:name_thumbnail, :weight)');
                $sql->bindParam(':name_thumbnail', $name_thumbnail);
                $sql->bindParam(':weight', $weight);
                $sql->execute();
                $lastId = $bdd->lastInsertId();
                try{
                    $sql = $bdd->prepare('INSERT INTO game (name_game, genre_game, number_players_game, headline_game, id_thumbnail) VALUES (:name_game, :genre, :number_players, :headline, :id_thumbnail)');
                    $sql->bindParam(':name_game', $name);
                    $sql->bindParam(':genre', $genre);
                    $sql->bindParam(':number_players', $nbPlayers);
                    $sql->bindParam(':headline', $headline);
                    $sql->bindParam(':id_thumbnail', $lastId);
                    $sql->execute();
                    include_once 'Bdd/deconnexion.php';
                    return json_encode(array('status'=>'success'));
                }
                catch (Exception $e){
                    $error = $e->getCode();
                    $errorMessage = $e->getMessage();
                    include_once 'Bdd/deconnexion.php';
                    if ($error == "23000"){
                        if (strpos($errorMessage, 'name_game')) {
                            return json_encode(array('status'=>'error:name_game'));
                        }
                        else if (strpos($errorMessage, 'genre_game')) {
                            return json_encode(array('status'=>'error:genre_game'));
                        }
                        else if (strpos($errorMessage, 'number_players_game')) {
                            return json_encode(array('status'=>'error:number_players_game'));
                        }
                        else if (strpos($errorMessage, 'headline_game')) {
                            return json_encode(array('status'=>'error:headline_game'));
                        }
                        else if (strpos($errorMessage, 'id_thumbnail')) {
                            return json_encode(array('status'=>'error:id_thumbnail'));
                        }
                    }
                }
            }
            catch (Exception $e){
                $error = $e->getCode();
                $errorMessage = $e->getMessage();
                include_once 'Bdd/deconnexion.php';
                if ($error == "23000"){
                    if (strpos($errorMessage, 'name_thumbnail')) {
                        return json_encode(array('status'=>'error:name_thumbnail'));
                    }
                    else if (strpos($errorMessage, 'weight_thumbnail')) {
                        return json_encode(array('status'=>'error:weight_thumbnail'));
                    }
                }
            }
        } else {
            return json_encode(array('status'=>'error:file'));
        }
    }
}