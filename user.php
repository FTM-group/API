<?php
header('Access-Control-Allow-Methods: GET, POST');

//retourne la moyenne et le nombre de notes d'un utilisateur
if (isset($_GET['score']) && isset($_GET['id'])){
    include_once 'Class/Score.php';
    $scoreProvider = new Score();

    echo $scoreProvider->getScore($_GET['id']);
}
elseif(isset($_POST['score']) && isset($_POST['id_target']) && isset($_POST['id_maker'])){
    include_once 'Class/Score.php';
    $scoreProvider = new Score();

    $data = array(
        'scoring' => $_POST['score'],
        'id_user_target' => $_POST['id_target'],
        'id_matchmaking_archive' => $_POST['id_matchmaking'],
        'id_user_maker' => $_POST['id_maker']
    );

    echo $scoreProvider->addScore($data);
}

?>