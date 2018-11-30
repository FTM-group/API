<?php
    header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: POST');
//header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');
$data = json_decode( file_get_contents( 'php://input' ), true );

//var_dump($data);

if ($data['login'] && $data['password']){

    include_once 'Class/User.php';
    $userProvider = new User();
    echo $userProvider->login($data['login'], $data['password']);

}


?>