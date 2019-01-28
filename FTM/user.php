<?php

header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, POST');
//header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');

if (isset($_POST['add_nickname']) && isset($_POST['id_user']) && isset($_POST['id_game']) && isset($_POST['nickname'])){
    include_once '../Model/User.php';
    $userProvider = new User();

    $data = array(
        'id_user' => $_POST['id_user'],
        'id_game' => $_POST['id_game'],
        'nickname' => $_POST['nickname']
    );

    echo json_encode($userProvider->addNickname($data));
}
elseif(isset($_POST['update_nickname']) && isset($_POST['id_user']) && isset($_POST['id_game']) && isset($_POST['nickname'])){
    include_once '../Model/User.php';
    $userProvider = new User();

    $data = array(
        'id_user' => $_POST['id_user'],
        'id_game' => $_POST['id_game'],
        'nickname' => $_POST['nickname']
    );

    echo json_encode($userProvider->updateNickname($data));
}
elseif(isset($_GET['user'])){
    include_once '../Model/User.php';
    $userProvider = new User();

    echo json_encode($userProvider->getUserByEmail($_GET['user']));
}