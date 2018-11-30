<?php

class User{
    function login($login, $password){
        $password = hash('sha512', $password);

        include_once 'Bdd/connexion.php';

        $sql = $bdd->prepare("SELECT login_user, email_user FROM user WHERE login_user = :login AND password_user = :password_user");
        $sql->bindParam(':login', $login);
        $sql->bindParam(':password_user', $password);
        $sql->execute();

        $result = $sql->fetch();
        include_once 'Bdd/deconnexion.php';

        if ($result){
            return json_encode(array('status'=>'success', 'user'=> array('login'=>$result['login_user'], 'email'=>$result['email_user'])));
        }
        else{
            return json_encode(array('status'=>'error'));
        }
    }

    function checkLogin($login){
        include_once 'Bdd/connexion.php';

        $sql = $bdd->prepare("SELECT login_user FROM user WHERE login_user = :login");
        $sql->bindParam(':login', $login);
        $sql->execute();

        $result = $sql->fetch();
        include_once 'Bdd/deconnexion.php';

        if ($result){
            return json_encode(array('status'=>'exist'));
        }
        else{
            return json_encode(array('status'=>'available'));
        }
    }

    function checkEmail($email){
        include_once 'Bdd/connexion.php';

        $sql = $bdd->prepare("SELECT email_user FROM user WHERE email_user = :email");
        $sql->bindParam(':email', $email);
        $sql->execute();

        $result = $sql->fetch();
        include_once 'Bdd/deconnexion.php';

        if ($result){
            return json_encode(array('status'=>'exist'));
        }
        else{
            return json_encode(array('status'=>'available'));
        }
    }

    function insertUser($login, $password, $email){
        include_once 'connexion_user.php';

        $hashed = hash('sha512', $password);

        try{
            $sql = $bdd->prepare("INSERT INTO user (login_user, password_user, email_user) VALUES (:login, :hashed, :email)");
            $sql->bindParam(':login', $login);
            $sql->bindParam(':hashed', $hashed);
            $sql->bindParam(':email', $email);
            $sql->execute();
            include_once 'Bdd/deconnexion.php';
            return json_encode(array('status'=>'success'));
        }
        catch (Exception $e){
            $error = $e->getCode();
            $errorMessage = $e->getMessage();
            include_once 'Bdd/deconnexion.php';
            if ($error == "23000"){
                if (strpos($errorMessage, 'login_user')) {
                    return json_encode(array('status'=>'error:login'));
                }
                else if (strpos($errorMessage, 'email_user')) {
                    return json_encode(array('status'=>'error:email'));
                }
            }
        }
    }
}