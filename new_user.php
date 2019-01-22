<?php
header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, POST');
//header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');

if (isset($_GET) && !empty($_GET)){
    if (isset($_GET['login'])){
        include_once 'Model/User.php';
        $userProvider = new User();
        echo $userProvider->checkLogin($_GET['login']);
    }

    if (isset($_GET['email'])){
        include_once 'Model/User.php';
        $userProvider = new User();
        echo $userProvider->checkEmail($_GET['email']);
    }
}
elseif(!empty(json_decode( file_get_contents( 'php://input' ), true ))){
    $data = json_decode( file_get_contents( 'php://input' ), true );

    $login = $data['login'];
    $password = $data['password'];
    $email = $data['email'];

    include_once 'Model/User.php';
    $userProvider = new User();
    echo $userProvider->insertUser($login, $password, $email);

}

?>