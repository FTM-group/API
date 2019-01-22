<?php
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Methods: GET');

//retourne la moyenne et le nombre de notes d'un utilisateur
if (isset($_GET['score']) && isset($_GET['id'])){
    include_once 'Class/User.php';
    $scoreProvider = new Score();

    echo $scoreProvider->getScore($_GET['id']);
}
elseif(isset($_POST['score']) && isset($_POST['id']) && isset($_POST['id_maker'])){

}

?>