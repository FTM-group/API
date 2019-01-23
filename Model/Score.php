<?php

class Score
{
    function insertScore($data){
        try{
            include 'Bdd/connexion_user.php';

            $sql = $bdd->prepare("INSERT INTO score(scoring, id_user_target, id_matchmaking_archive, id_user_maker) VALUES (:scoring, :id_user_target, :id_matchmaking_archive, :id_user_maker)");
            $sql->bindParam(':name_genre', $data['scoring']);
            $sql->bindParam(':name_genre', $data['id_user_target']);
            $sql->bindParam(':name_genre', $data['id_matchmaking_archive']);
            $sql->bindParam(':name_genre', $data['id_user_maker']);
            $sql->execute();

            include 'Bdd/deconnexion.php';

            return array('status'=>'success');
        }
        catch(Exception $e){
            $error = $e->getCode();
            $errorMessage = $e->getMessage();

            if ($error == "23000"){
                if (strpos($errorMessage, 'scoring')) {
                    return array('status'=>'error', 'error' => 'scoring');
                }
                elseif (strpos($errorMessage, 'id_user_target')) {
                    return array('status'=>'error', 'error' => 'id_user_target');
                }
                elseif (strpos($errorMessage, 'id_matchmaking_archive')) {
                    return array('status'=>'error', 'error' => 'id_matchmaking_archive');
                }
                elseif (strpos($errorMessage, 'id_user_maker')) {
                    return array('status'=>'error', 'error' => 'id_user_maker');
                }
            }
        }
    }

    function getScore($id_user){
        try{
            include 'Bdd/connexion_user.php';

            $sql = $bdd->prepare("SELECT AVG(scoring) as scoring, COUNT(id_score) as nb WHERE id_user = :id_user");
            $sql->bindParam(':id_user', $id_user);
            $sql->execute();

            $result = $sql->fetch();

            include 'Bdd/deconnexion.php';

            return array('status'=>'success', 'data' => $result);
        }
        catch(Exception $e){
            $error = $e->getCode();
            $errorMessage = $e->getMessage();

            if ($error == "23000"){
                if (strpos($errorMessage, 'scoring')) {
                    return array('status'=>'error', 'error' => 'scoring');
                }
                elseif (strpos($errorMessage, 'id_user_target')) {
                    return array('status'=>'error', 'error' => 'id_user_target');
                }
                elseif (strpos($errorMessage, 'id_matchmaking_archive')) {
                    return array('status'=>'error', 'error' => 'id_matchmaking_archive');
                }
                elseif (strpos($errorMessage, 'id_user_maker')) {
                    return array('status'=>'error', 'error' => 'id_user_maker');
                }
            }
        }
    }
}

