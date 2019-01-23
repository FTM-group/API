<?php
header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, POST');
//header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');

//retourne la moyenne et le nombre de notes d'un utilisateur
if (isset($_GET['score']) && isset($_GET['id'])){
    include_once '../Model/Score.php';
    $scoreProvider = new Score();

    echo json_encode($scoreProvider->getScore($_GET['id']));
}
elseif(isset($_POST['score']) && isset($_POST['id_target']) && isset($_POST['id_maker'])){
    include_once '../Model/Score.php';
    $scoreProvider = new Score();

    $data = array(
        'scoring' => $_POST['score'],
        'id_user_target' => $_POST['id_target'],
        'id_matchmaking_archive' => $_POST['id_matchmaking'],
        'id_user_maker' => $_POST['id_maker']
    );

    echo json_encode($scoreProvider->addScore($data));
}

?>