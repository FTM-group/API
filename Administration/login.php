<?php
    header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: POST');
//header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');

if (isset($_POST['login']) && isset($_POST['password'])){
    include_once '../Model/Admin.php';
    $adminProvider = new Admin();

    echo json_encode($adminProvider->login($data['login'], $data['password']));
}


?>