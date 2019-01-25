<?php

class User{
    function login($login, $password){
        $password = hash('sha512', $password);

        try{
            include '../Bdd/connexion.php';

            $sql = $bdd->prepare("SELECT login_user, email_user, id_user FROM user WHERE login_user = :login AND password_user = :password_user");
            $sql->bindParam(':login', $login);
            $sql->bindParam(':password_user', $password);
            $sql->execute();

            $result = $sql->fetch();
            include '../Bdd/deconnexion.php';

            if ($result){
                return array('status'=>'success', 'user'=> array('login'=>$result['login_user'], 'email'=>$result['email_user']), 'id_user' => $result['id_user']);
            }
            else{
                return array('status'=>'empty');
            }
        }
        catch(Exception $e){
            return array('status'=>'error');
        }
    }

    function checkLogin($login){
        try{
            include '../Bdd/connexion.php';

            $sql = $bdd->prepare("SELECT login_user FROM user WHERE login_user = :login");
            $sql->bindParam(':login', $login);
            $sql->execute();

            $result = $sql->fetch();
            include '../Bdd/deconnexion.php';

            if ($result){
                return array('status'=>'exist');
            }
            else{
                return array('status'=>'available');
            }
        }
        catch(Exception $e){
            return array('status'=>'error');
        }

    }

    function checkEmail($email){
        try{
            include '../Bdd/connexion.php';

            $sql = $bdd->prepare("SELECT email_user FROM user WHERE email_user = :email");
            $sql->bindParam(':email', $email);
            $sql->execute();

            $result = $sql->fetch();
            include '../Bdd/deconnexion.php';

            if ($result){
                return array('status'=>'exist');
            }
            else{
                return array('status'=>'available');
            }
        }
        catch(Exception $e){
            return array('status'=>'error');
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
            include '../Bdd/deconnexion.php';
            return array('status'=>'success');
        }
        catch (Exception $e){
            $error = $e->getCode();
            $errorMessage = $e->getMessage();

            if ($error == "23000"){
                if (strpos($errorMessage, 'login_user')) {
                    return array('status'=>'error', 'error' => 'login');
                }
                else if (strpos($errorMessage, 'email_user')) {
                    return array('status'=>'error', 'error' => 'email');
                }
            }
        }
    }

    function forgottenPassword($email){
        if  ($this->getUserByEmail($email) != null){
            include_once 'Email.php';
            $emailProvider = new Email();
            $status = $emailProvider->sendEmail($email, 2);

            return $status;
        }
        else{
            return false;
        }
    }

    function getUserByEmail($email){
        include '../Bdd/connexion.php';

        $sql = $bdd->prepare("SELECT id_user FROM user WHERE email_user = :email_user");
        $sql->bindParam(':email_user', $email);
        $sql->execute();

        $result = $sql->fetch();
        include '../Bdd/deconnexion.php';

        if ($result){
            return array('status' => 'success');
        }
        else{
            return array('status' => 'emtpy');
        }
    }

    function addNickname($data){
        try{
            include 'connexion_user.php';

            $sql = $bdd->prepare("INSERT INTO nickname_user_game (id_user, id_game, nickname) VALUES (:id_user, :id_game, :nickname)");
            $sql->bindParam(':id_user', $data['id_user']);
            $sql->bindParam(':id_game', $data['id_game']);
            $sql->bindParam(':nickname', $data['nickname']);
            $sql->execute();
            include '../Bdd/deconnexion.php';
            return array('status'=>'success');
        }
        catch (Exception $e){
            $error = $e->getCode();
            $errorMessage = $e->getMessage();

            if ($error == "23000"){
                if (strpos($errorMessage, 'id_user')) {
                    return array('status'=>'error', 'error' => 'id_user');
                }
                elseif (strpos($errorMessage, 'id_game')) {
                    return array('status'=>'error', 'error' => 'id_game');
                }
                else if (strpos($errorMessage, 'nickname')) {
                    return array('status'=>'error', 'error' => 'nickname');
                }
            }
        }
    }

    function updateNickname($data){
        try{
            include 'connexion_white.php';

            $sql = $bdd->prepare("UPDATE nickname_user_game SET nickname = :nickname WHERE id_user = :id_user AND id_game = :id_game");
            $sql->bindParam(':id_user', $data['id_user']);
            $sql->bindParam(':id_game', $data['id_game']);
            $sql->bindParam(':nickname', $data['nickname']);
            $sql->execute();
            include '../Bdd/deconnexion.php';
            return array('status'=>'success');
        }
        catch (Exception $e){
            $error = $e->getCode();
            $errorMessage = $e->getMessage();

            if ($error == "23000"){
                if (strpos($errorMessage, 'id_user')) {
                    return array('status'=>'error', 'error' => 'id_user');
                }
                elseif (strpos($errorMessage, 'id_game')) {
                    return array('status'=>'error', 'error' => 'id_game');
                }
                else if (strpos($errorMessage, 'nickname')) {
                    return array('status'=>'error', 'error' => 'nickname');
                }
            }
        }
    }
}