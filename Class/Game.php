<?php

class Game{
    function getAll(){
        include 'Bdd/connexion.php';

        $sql = $bdd->prepare("SELECT * FROM game ORDER BY name_game");
        $sql->execute();

        $results = $sql->fetchAll();
        include 'Bdd/deconnexion.php';

        if ($results){
            return json_encode(array('status'=>'success', 'games'=> $results));
        }
        else{
            return json_encode(array('status'=>'error'));
        }
    }

    function getTop(){
        include('Bdd/connexion.php');

        $sql = $bdd->prepare("SELECT * FROM game g JOIN matchmaking_archive ma ON g.id_game = ma.id_game ORDER BY name_game LIMIT 10");
        $sql->execute();

        $results = $sql->fetchAll();
        include 'Bdd/deconnexion.php';

        if ($results){
            return json_encode(array('status'=>'success', 'games'=> $results));
        }
        else{
            return json_encode(array('status'=>'error'));
        }
    }

    function insertGame($name, $file, $genre, $nbPlayers, $headline){

        include_once 'Class/Thumbnail.php';
        $thumbnailProvider = new Thumbnail();
        $thumbnail = $thumbnailProvider->addThumbnail($file);

        if ($thumbnail['status'] == 'success'){
            include 'Bdd/connexion_user.php';

            try{
                $sql = $bdd->prepare('INSERT INTO game (name_game, genre_game, number_players_game, headline_game, id_thumbnail) VALUES (:name_game, :genre_game, :number_players_game, :headline_game, :id_thumbnail)');
                $sql->bindParam(':name_game', $name);
                $sql->bindParam(':genre_game', $genre);
                $sql->bindParam(':number_players_game', $nbPlayers);
                $sql->bindParam(':headline_game', $headline);
                $sql->bindParam(':id_thumbnail', $thumbnail['last_id']);
                $sql->execute();
                include 'Bdd/deconnexion.php';
                return json_encode(array('status'=>'success'));
            }
            catch (Exception $e){
                $error = $e->getCode();
                $errorMessage = $e->getMessage();
                include 'Bdd/deconnexion.php';
                if ($error == "23000"){
                    if (strpos($errorMessage, 'name_game')) {
                        return json_encode(array('status'=>'error', 'error' => 'name_game'));
                    }
                    else if (strpos($errorMessage, 'genre_game')) {
                        return json_encode(array('status'=>'error', 'error' => 'genre_game'));
                    }
                    else if (strpos($errorMessage, 'number_players_game')) {
                        return json_encode(array('status'=>'error', 'error' => 'number_players_game'));
                    }
                    else if (strpos($errorMessage, 'headline_game')) {
                        return json_encode(array('status'=>'error', 'error' => 'headline_game'));
                    }
                    else if (strpos($errorMessage, 'id_thumbnail')) {
                        return json_encode(array('status'=>'error', 'error' => 'id_thumbnail'));
                    }
                }
            }
        }
        else{
            return json_encode($thumbnail);
        }
    }

    function updateGame($data, $file = false){
        include 'Bdd/connexion_gold.php';

        try{
            $sql = $bdd->prepare('UPDATE game SET name_game = :name_game, genre_game = :genre_game, number_players_game = :number_players_game, headline_game = :headline_game, on_off_game = :on_off_game WHERE id_game = :id_game');
            $sql->bindParam(':name_game', $data['name_game']);
            $sql->bindParam(':genre_game', $data['genre_name']);
            $sql->bindParam(':number_players_game', $data['number_players_game']);
            $sql->bindParam(':headline_game', $data['headline_game']);
            $sql->bindParam(':on_off_game', $data['on_off_game']);
            $sql->bindParam(':id_game', $data['id_game']);
            $sql->execute();


            include_once 'Bdd/deconnexion.php';

            if ($file){
                include_once 'Class/Thumbnail.php';
                $thumbnailProvider = new Thumbnail();
                $thumbnail = $thumbnailProvider->updateThumbnail($data['id_thumbnail'], $file);

                return json_encode($thumbnail);
            }
        }
        catch(Exception $e){
            $error = $e->getCode();
            $errorMessage = $e->getMessage();
            include 'Bdd/deconnexion.php';

            if ($error == "23000"){
                if (strpos($errorMessage, 'name_game')) {
                    return json_encode(array('status'=>'error', 'error' => 'name_game'));
                }
                else if (strpos($errorMessage, 'genre_game')) {
                    return json_encode(array('status'=>'error', 'error' => 'genre_game'));
                }
                else if (strpos($errorMessage, 'number_players_game')) {
                    return json_encode(array('status'=>'error', 'error' => 'number_players_game'));
                }
                else if (strpos($errorMessage, 'headline_game')) {
                    return json_encode(array('status'=>'error', 'error' => 'headline_game'));
                }
                else if (strpos($errorMessage, 'on_off_game')) {
                    return json_encode(array('status'=>'error', 'error' => 'on_off_game'));
                }
                else if (strpos($errorMessage, 'id_game')) {
                    return json_encode(array('status'=>'error', 'error' => 'id_game'));
                }
            }
        }
    }
}