<?php

class RequestedGame
{
    function addRequestedGames($data){
        try{
            include 'Bdd/connexion_user.php';

            $sql = $bdd->prepare("INSERT INTO game_request(name_game_request, id_user) VALUES (:name_game, :id_user)");
            $sql->bindParam(':name_game', $data['name_game']);
            $sql->bindParam(':id_user', $data['id_user']);
            $sql->execute();

            include 'Bdd/deconnexion.php';

            return json_encode(array('status'=>'success'));
        }
        catch(Exception $e){
            $error = $e->getCode();
            $errorMessage = $e->getMessage();

            if ($error == "23000"){
                if (strpos($errorMessage, 'name_game')) {
                    return json_encode(array('status'=>'error', 'error' => 'name_game'));
                }
                else if (strpos($errorMessage, 'id_user')) {
                    return json_encode(array('status'=>'error', 'error' => 'id_user'));
                }
            }

            return array('status'=>'error');
        }
    }

    function getRequestedGames(){
        try{
            include 'Bdd/connexion.php';

            $sql = $bdd->prepare("SELECT id_game_request, name_game_request, date_game_request, id_user FROM game_request");
            $sql->execute();

            $results = $sql->fetchAll();
            include 'Bdd/deconnexion.php';

            if ($results){
                return json_encode(array('status'=>'success', 'data' => $results));
            }
            else{
                return json_encode(array('status'=>'empty'));
            }
        }
        catch(Exception $e){
            $error = $e->getCode();
            $errorMessage = $e->getMessage();

            return array('status'=>'error');
        }
    }

    function deleteRequestedGame($id_game_request){
        try{
            include 'Bdd/connexion_gold.php';

            $sql = $bdd->prepare("DELETE FROM game_request WHERE id_game_request = :id_game_request");
            $sql->bindParam(':id_game_request', $id_game_request);
            $sql->execute();

            include 'Bdd/deconnexion.php';

            return json_encode(array('status'=>'success'));
        }
        catch(Exception $e){
            $error = $e->getCode();
            $errorMessage = $e->getMessage();

            if ($error == "23000"){
                if (strpos($errorMessage, 'id_game_request')) {
                    return json_encode(array('status'=>'error', 'error' => 'id_game_request'));
                }
            }

            return array('status'=>'error');
        }
    }
}