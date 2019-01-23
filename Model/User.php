<?php

class User{
    function login($login, $password){
        $password = hash('sha512', $password);

        try{
            include 'Bdd/connexion.php';

            $sql = $bdd->prepare("SELECT login_user, email_user FROM user WHERE login_user = :login AND password_user = :password_user");
            $sql->bindParam(':login', $login);
            $sql->bindParam(':password_user', $password);
            $sql->execute();

            $result = $sql->fetch();
            include 'Bdd/deconnexion.php';

            if ($result){
                return json_encode(array('status'=>'success', 'user'=> array('login'=>$result['login_user'], 'email'=>$result['email_user'])));
            }
            else{
                return json_encode(array('status'=>'empty'));
            }
        }
        catch(Exception $e){
            return json_encode(array('status'=>'error'));
        }
    }

    function checkLogin($login){
        try{
            include 'Bdd/connexion.php';

            $sql = $bdd->prepare("SELECT login_user FROM user WHERE login_user = :login");
            $sql->bindParam(':login', $login);
            $sql->execute();

            $result = $sql->fetch();
            include 'Bdd/deconnexion.php';

            if ($result){
                return json_encode(array('status'=>'exist'));
            }
            else{
                return json_encode(array('status'=>'available'));
            }
        }
        catch(Exception $e){
            return json_encode(array('status'=>'error'));
        }

    }

    function checkEmail($email){
        try{
            include 'Bdd/connexion.php';

            $sql = $bdd->prepare("SELECT email_user FROM user WHERE email_user = :email");
            $sql->bindParam(':email', $email);
            $sql->execute();

            $result = $sql->fetch();
            include 'Bdd/deconnexion.php';

            if ($result){
                return json_encode(array('status'=>'exist'));
            }
            else{
                return json_encode(array('status'=>'available'));
            }
        }
        catch(Exception $e){
            return json_encode(array('status'=>'error'));
        }

    }

    function addUser($login, $password, $email){
        $hashed = hash('sha512', $password);

        try{
            include 'connexion_user.php';

            $sql = $bdd->prepare("INSERT INTO user (login_user, password_user, email_user) VALUES (:login, :hashed, :email)");
            $sql->bindParam(':login', $login);
            $sql->bindParam(':hashed', $hashed);
            $sql->bindParam(':email', $email);
            $sql->execute();
            include 'Bdd/deconnexion.php';
            return json_encode(array('status'=>'success'));
        }
        catch (Exception $e){
            $error = $e->getCode();
            $errorMessage = $e->getMessage();

            if ($error == "23000"){
                if (strpos($errorMessage, 'login_user')) {
                    return json_encode(array('status'=>'error', 'error' => 'login'));
                }
                else if (strpos($errorMessage, 'email_user')) {
                    return json_encode(array('status'=>'error', 'error' => 'email'));
                }
            }
        }
    }

    function forgottenPassword($email){
        if  ($this->getUserByEmail($email) != null){
            include_once 'Email.php';
            $emailProvider = new Email();
            $status = $emailProvider->sendEmail($email, 2);
            var_dump($status);
            return $status;
        }
        else{
            return false;
        }
    }

    function getUserByEmail($email){
        include 'Bdd/connexion.php';

        $sql = $bdd->prepare("SELECT id_user FROM user WHERE email_user = :email_user");
        $sql->bindParam(':email_user', $email);
        $sql->execute();

        $result = $sql->fetch();
        include 'Bdd/deconnexion.php';

        if ($result){
            return $result['id_user'];
        }
        else{
            return null;
        }
    }
}