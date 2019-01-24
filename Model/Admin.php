<?php

class Admin{
    function login($login, $password){
        $password = hash('sha512', $password);

        try{
            include '../Bdd/connexion.php';

            $sql = $bdd->prepare("SELECT login_admin FROM user WHERE login_admin = :login AND password_admin = :password_admin");
            $sql->bindParam(':login_admin', $login);
            $sql->bindParam(':password_admin', $password);
            $sql->execute();

            $result = $sql->fetch();
            include '../Bdd/deconnexion.php';

            if ($result){
                return array('status'=>'success', 'user'=> array('login'=>$result['login_admin']));
            }
            else{
                return array('status'=>'empty');
            }
        }
        catch(Exception $e){
            return array('status'=>'error');
        }
    }
}