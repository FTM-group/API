<?php

class Thumbnail {
    function addThumbnail($file){
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
            include 'Bdd/connexion_user.php';

            $weight = $file['size'] / 1000;

            try{
                $sql = $bdd->prepare('INSERT INTO thumbnail (name_thumbnail, weight_thumbnail) VALUES (:name_thumbnail, :weight_thumbnail)');
                $sql->bindParam(':name_thumbnail', $name_thumbnail);
                $sql->bindParam(':weight', $weight);
                $sql->execute();
                $lastId = $bdd->lastInsertId();
                include_once 'Bdd/deconnexion.php';

                return array('status' => 'success', 'last_id' => $lastId);
            }
            catch (Exception $e){
                $error = $e->getCode();
                $errorMessage = $e->getMessage();
                include_once 'Bdd/deconnexion.php';
                if ($error == "23000"){
                    if (strpos($errorMessage, 'name_thumbnail')) {
                        return array('status'=>'error', 'error' => 'name_thumbnail');
                    }
                    else if (strpos($errorMessage, 'weight_thumbnail')) {
                        return array('status'=>'error', 'error' => 'weight_thumbnail');
                    }
                }
            }
        } else {
            return array('status'=>'error:file');
        }
    }

    function updateThumbnail($id, $file){
        include 'Bdd/connexion.php';

        try{
            $sql = $bdd->prepare('SELECT name_thumbnail FROM thumbnail WHERE id_thumbnail = :id_thumbnail');
            $sql->bindParam(':id_thumbnail', $id);
            $sql->execute();
            $result = $sql->fetch();

            include_once 'Bdd/deconnexion.php';
            if($result){
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
                    include 'Bdd/connexion_gold.php';

                    $weight = $file['size'] / 1000;

                    if (@unlink($uploaddir.$result['name_thumbnail'])){
                        try{
                            $sql = $bdd->prepare('UPDATE thumbnail SET name_thumbnail = :name_thumbnail, weight_thumbnail = :weight_thumbnail WHERE id_thumbnail = :id_thumbnail');
                            $sql->bindParam(':name_thumbnail', $name_thumbnail);
                            $sql->bindParam(':weight_thumbnail', $weight);
                            $sql->bindParam(':id_thumbnail', $id);
                            $sql->execute();

                            include_once 'Bdd/deconnexion.php';

                            return array('status' => 'success');
                        }
                        catch (Exception $e){
                            $error = $e->getCode();
                            $errorMessage = $e->getMessage();

                            if ($error == "23000"){
                                if (strpos($errorMessage, 'name_thumbnail')) {
                                    return array('status'=>'error', 'error' => 'name_thumbnail');
                                }
                                else if (strpos($errorMessage, 'weight_thumbnail')) {
                                    return array('status'=>'error', 'error' => 'weight_thumbnail');
                                }
                                else if (strpos($errorMessage, 'id_thumbnail')) {
                                    return array('status'=>'error', 'error' => 'id_thumbnail');
                                }
                            }
                        }
                    }
                    else{
                        return array('status' => 'error', 'error' => 'delete');
                    }
                } else {
                    return array('status'=>'error', 'error' => 'file');
                }
            }
            else{
                return array('status' => 'error', 'error' => 'checkExist');
            }
        }
        catch (Exception $e){
            return array('status' => 'error', 'error' => 'check');
        }
    }
}