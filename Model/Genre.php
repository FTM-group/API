<?php

class Genre{
    function getOne($idGenre){
        try{
            include '../Bdd/connexion.php';

            $sql = $bdd->prepare("SELECT id_genre, name_genre FROM genre WHERE id_genre = :id_genre");
            $sql->bindParam(':id_genre', $idGenre);
            $sql->execute();

            $results = $sql->fetch();
            include '../Bdd/deconnexion.php';

            if ($results){
                $return = array();
                foreach ($results as $value){
                    $return[] = array(
                        'id_genre' => $value['id_genre'],
                        'name_genre' => $value['name_genre'],
                    );
                }

                return array('status'=>'success', 'data' => $return);
            }
            else{
                return array('status'=>'empty');
            }
        }
        catch(Exception $e){
            $error = $e->getCode();
            $errorMessage = $e->getMessage();

            if ($error == "23000"){
                if (strpos($errorMessage, 'id_genre')) {
                    return array('status'=>'error', 'error' => 'id_genre');
                }
                else if (strpos($errorMessage, 'name_genre')) {
                    return array('status'=>'error', 'error' => 'name_genre');
                }
            }
        }
    }

    function getAll() {
        try{
            include '../Bdd/connexion.php';

            $sql = $bdd->prepare("SELECT id_genre, name_genre FROM genre");
            $sql->execute();

            $results = $sql->fetchAll();
            include '../Bdd/deconnexion.php';

            if ($results){
                $return = array();
                foreach ($results as $value){
                    $return[] = array(
                        'id_genre' => $value['id_genre'],
                        'name_genre' => $value['name_genre'],
                    );
                }

                return array('status'=>'success', 'data' => $return);
            }
            else{
                return array('status'=>'empty');
            }
        }
        catch(Exception $e){
            $error = $e->getCode();
            $errorMessage = $e->getMessage();

            if ($error == "23000"){
                if (strpos($errorMessage, 'id_genre')) {
                    return array('status'=>'error', 'error' => 'id_genre');
                }
                else if (strpos($errorMessage, 'name_genre')) {
                    return array('status'=>'error', 'error' => 'name_genre');
                }
            }
        }
    }

    function addGenre($data){
        try{
            include '../Bdd/connexion_user.php';

            $sql = $bdd->prepare("INSERT INTO genre(name_genre) VALUES (:name_genre)");
            $sql->bindParam(':name_genre', $data['name_genre']);
            $sql->execute();

            include '../Bdd/deconnexion.php';

            return array('status'=>'success');
        }
        catch(Exception $e){
            $error = $e->getCode();
            $errorMessage = $e->getMessage();

            if ($error == "23000"){
                if (strpos($errorMessage, 'name_genre')) {
                    return array('status'=>'error', 'error' => 'name_genre');
                }
            }
        }
    }

    function updateGenre($idGenre, $data){
        try{
            include '../Bdd/connexion_white.php';

            $sql = $bdd->prepare("UPDATE genre SET name_genre = :name_genre WHERE id_genre = :id_genre");
            $sql->bindParam(':name_genre', $data['name_genre']);
            $sql->bindParam(':id_genre', $idGenre);
            $sql->execute();

            include '../Bdd/deconnexion.php';

            return array('status'=>'success');
        }
        catch(Exception $e){
            $error = $e->getCode();
            $errorMessage = $e->getMessage();

            if ($error == "23000"){
                if (strpos($errorMessage, 'id_genre')) {
                    return array('status'=>'error', 'error' => 'id_genre');
                }
                else if (strpos($errorMessage, 'name_genre')) {
                    return array('status'=>'error', 'error' => 'name_genre');
                }
            }
        }
    }

    function deleteGenre($idGenre){
        try{
            include '../Bdd/connexion_gold.php';

            $sql = $bdd->prepare("DELETE FROM genre WHERE id_genre = :id_genre");
            $sql->bindParam(':id_genre', $idGenre);
            $sql->execute();

            include '../Bdd/deconnexion.php';

            return array('status'=>'success');
        }
        catch(Exception $e){
            $error = $e->getCode();
            $errorMessage = $e->getMessage();

            if ($error == "23000"){
                if (strpos($errorMessage, 'id_genre')) {
                    return array('status'=>'error', 'error' => 'id_genre');
                }
                else if (strpos($errorMessage, 'name_genre')) {
                    return array('status'=>'error', 'error' => 'name_genre');
                }
            }
        }
    }
}