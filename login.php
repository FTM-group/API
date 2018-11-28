<?php
    header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: POST');
//header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');
$data = json_decode( file_get_contents( 'php://input' ), true );

//var_dump($data);

if ($data['login'] && $data['password']){

    $password = hash('sha512', $data['password']);

    include_once 'connexion.php';

    $sql = $bdd->prepare("SELECT login_user, email_user FROM user WHERE login_user ='".$data['login']."' AND password_user = '".$password."'");
    $sql->execute();

    $result = $sql->fetch();

//    var_dump($sql);
//    var_dump($result);

    if ($result){
        echo json_encode(array('status'=>'success', 'user'=> array('login'=>$result['login_user'], 'email'=>$result['email_user'])));
    }
    else{
        echo json_encode(array('status'=>'error'));
    }

}


?>