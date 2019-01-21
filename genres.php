<?php

header("Access-Control-Allow-Origin: *");
//header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Methods: POST');
//header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');

if(isset($_GET['all'])){
    include_once 'Model/Genre.php';
    $genreProvider = new Genre();
    echo $genreProvider->getAll();
}
elseif (isset($_GET['one'])){
    include_once 'Model/Genre.php';
    $genreProvider = new Genre();
    echo $genreProvider->getOne($_GET['one']);
}
elseif (isset($_POST['delete'])){
    include_once 'Model/Genre.php';
    $genreProvider = new Genre();
    echo $genreProvider->deleteGenre($_POST['delete']);
}
elseif (isset($_POST['update']) && isset($_POST['data'])){
    include_once 'Model/Genre.php';
    $genreProvider = new Genre();
    echo $genreProvider->updateGenre($_POST['update'], $_POST['data']);
}