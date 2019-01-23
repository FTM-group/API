<?php

class Ip{
    function get_ip() {
        // IP si internet partagé
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        }
        // IP derrière un proxy
        elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        // Sinon : IP normale
        else {
            return (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '');
        }
    }

    function getAll(){
        try{
            include '../Bdd/connexion.php';

            $sql = $bdd->prepare("SELECT id_tracking_ip, adress_tracking_ip, date_tracking_ip, id_user 
                                  FROM tracking_ip 
                                  WHERE adress_tracking_ip 
                                  IN (
                                      SELECT adress_tracking_ip 
                                      FROM tracking_ip 
                                      GROUP BY adress_tracking_ip 
                                      HAVING count(id_user) > 1
                                  ) 
                                  GROUP BY id_user, adress_tracking_ip 
                                  ORDER BY adress_tracking_ip, date_tracking_ip");
            $sql->execute();

            $results = $sql->fetchAll();
            include '../Bdd/deconnexion.php';

            if ($results){
                return array('status'=>'success', 'data' => $results);
            }
            else{
                return array('status'=>'empty');
            }
        }
        catch(Exception $e){
            $error = $e->getCode();
            $errorMessage = $e->getMessage();

            return array('status'=>'error');
        }
    }
}