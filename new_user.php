<?php
header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Methods: POST');
//header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');

if (isset($_GET) && !empty($_GET)){
    include_once 'connexion.php';

    if (isset($_GET['login'])){
        $sql = $bdd->prepare("SELECT login_user FROM user WHERE login_user ='".$_GET['login']."'");
        $sql->execute();

        $result = $sql->fetch();

        if ($result){
            echo json_encode(array('status'=>'exist'));
        }
        else{
            echo json_encode(array('status'=>'available'));
        }
    }

    if (isset($_GET['email'])){
        $sql = $bdd->prepare("SELECT email_user FROM user WHERE email_user ='".$_GET['email']."'");
        $sql->execute();

        $result = $sql->fetch();

//    var_dump($sql);
//    var_dump($result);

        if ($result){
            echo json_encode(array('status'=>'exist'));
        }
        else{
            echo json_encode(array('status'=>'available'));
        }
    }
}
elseif(!empty(json_decode( file_get_contents( 'php://input' ), true ))){

    $data = json_decode( file_get_contents( 'php://input' ), true );

//var_dump($data);

    include_once 'connexion_user.php';

    $login = $data['login'];
    $password = $data['password'];
    $hashed = hash('sha512', $password);
    $email = $data['email'];
//    $token = hash('sha512', $login.$email);

//    $sql = "INSERT INTO user (login_user, password_user, email_user, token_user) VALUES ('".$login."', '".$hashed."', '".$email."', '".$token."')";
    $sql = "INSERT INTO user (login_user, password_user, email_user) VALUES ('".$login."', '".$hashed."', '".$email."')";

    try{
        $stmt = $bdd->prepare($sql);
        $stmt->execute();
        echo json_encode(array('status'=>'success'));
    }
    catch (Exception $e){
//        var_dump($e);
        $error = $e->getCode();
        $errorMessage = $e->getMessage();
        if ($error == "23000"){
            if (strpos($errorMessage, 'login_user')) {
                echo json_encode(array('status'=>'error:login'));
            }
            else if (strpos($errorMessage, 'email_user')) {
                echo json_encode(array('status'=>'error:email'));
            }
//            else if (strpos($errorMessage, 'token_user')) {
//                echo json_encode(array('status'=>'error:token_user'));
//            }
        }
    }
}

?>