<?php

class Game{
    function getOne($idGame){
        try{
            include 'Bdd/connexion.php';

            $sql = $bdd->prepare("SELECT G.id_game, name_game, date_add_game, headline_game, on_off_game, G.id_thumbnail, name_thumbnail, 
                              (SELECT GEN.id_genre, name_genre FROM genre GEN JOIN game_genre GG ON GEN.id_genre = GG.id_genre WHERE GG.id_game = G.id_game) as genres
                              FROM game G
                              JOIN thumbnail T ON G.id_thumbnail = T.id_thumbnail
                              WHERE G.id_game=:id_game");
            $sql->bindParam(':id_game', $idGame);
            $sql->execute();

            $result = $sql->fetch();
            include 'Bdd/deconnexion.php';

            if ($result){
                try{
                    $return = array();
                    include('Bdd/connexion.php');

                    $sql = $bdd->prepare("SELECT GEN.id_genre, name_genre 
                                      FROM genre GEN 
                                      JOIN game_genre GG ON GEN.id_genre = GG.id_genre 
                                      WHERE GG.id_game = :id_game");
                    $sql->bindParam(':id_game', $result['id_game']);
                    $sql->execute();

                    $genresTab = $sql->fetchAll();
                    include 'Bdd/deconnexion.php';

                    if ($genresTab){
                        $genres = array();
                        foreach ($genresTab as $value){
                            $genres[] = array(
                                'id_genre' => $value['id_genre'],
                                'name_genre' => $value['name_genre']
                            );
                        }
                    }
                    else{
                        return json_encode(array('status'=>'empty_genres'));
                    }

                    include('Bdd/connexion.php');

                    $sql = $bdd->prepare("SELECT game_nb_max_players
                                          FROM game_nb_max_players 
                                          WHERE id_game = :id_game");
                    $sql->bindParam(':id_game', $result['id_game']);
                    $sql->execute();

                    $nbPlayersTab = $sql->fetchAll();
                    include 'Bdd/deconnexion.php';

                    if ($nbPlayersTab){
                        $nbPlayers = array();
                        foreach ($nbPlayersTab as $value){
                            $nbPlayers[] = array(
                                'nb_players' => $value['game_nb_max_players']
                            );
                        }
                    }
                    else{
                        return json_encode(array('status'=>'empty_nb_max_players'));
                    }

                    $return[] = array(
                        'id_game' => $result['id_game'],
                        'name_game' => $result['name_game'],
                        'date_add_game' => $result['date_add_game'],
                        'headline_game' => $result['headline_game'],
                        'on_off_game' => $result['on_off_game'],
                        'id_thumbnail' => $result['id_thumbnail'],
                        'name_thumbnail' => $result['name_thumbnail'],
                        'genres' => $genres,
                        'nb_players' => $nbPlayers,
                    );

                    return json_encode(array('status'=>'success', 'games'=> $return));

                }
                catch(Exception $e){
                    return json_encode(array('status'=>'error_genres'));
                }
            }
            else{
                return json_encode(array('status'=>'empty'));
            }
        }
        catch(Exception $e){
            return json_encode(array('status'=>'error'));
        }

    }

    function getTop(){
        try{
            include('Bdd/connexion.php');

            $sql = $bdd->prepare("SELECT G.id_game, name_game, date_add_game, headline_game, on_off_game, G.id_thumbnail, name_thumbnail
                              FROM game G
                              JOIN thumbnail T ON G.id_thumbnail=T.id_thumbnail
                              JOIN matchmaking_archive ma ON G.id_game = ma.id_game 
                              ORDER BY name_game 
                              LIMIT 10");
            $sql->execute();

            $results = $sql->fetchAll();
            include 'Bdd/deconnexion.php';

            if ($results){
                try{
                    $return = array();
                    foreach ($results as $result){
                        include('Bdd/connexion.php');

                        $sql = $bdd->prepare("SELECT GEN.id_genre, name_genre 
                                          FROM genre GEN 
                                          JOIN game_genre GG ON GEN.id_genre = GG.id_genre 
                                          WHERE GG.id_game = :id_game");
                        $sql->bindParam(':id_game', $result['id_game']);
                        $sql->execute();

                        $genresTab = $sql->fetchAll();
                        include 'Bdd/deconnexion.php';

                        if ($genresTab){
                            $genres = array();
                            foreach ($genresTab as $value){
                                $genres[] = array(
                                    'id_genre' => $value['id_genre'],
                                    'name_genre' => $value['name_genre']
                                );
                            }
                        }
                        else{
                            return json_encode(array('status'=>'empty_genres'));
                        }

                        include('Bdd/connexion.php');

                        $sql = $bdd->prepare("SELECT game_nb_max_players
                                          FROM game_nb_max_players 
                                          WHERE id_game = :id_game");
                        $sql->bindParam(':id_game', $result['id_game']);
                        $sql->execute();

                        $nbPlayersTab = $sql->fetchAll();
                        include 'Bdd/deconnexion.php';

                        if ($nbPlayersTab){
                            $nbPlayers = array();
                            foreach ($nbPlayersTab as $value){
                                $nbPlayers[] = array(
                                    'nb_players' => $value['game_nb_max_players']
                                );
                            }
                        }
                        else{
                            return json_encode(array('status'=>'empty_nb_max_players'));
                        }

                        $return[] = array(
                            'id_game' => $result['id_game'],
                            'name_game' => $result['name_game'],
                            'date_add_game' => $result['date_add_game'],
                            'headline_game' => $result['headline_game'],
                            'on_off_game' => $result['on_off_game'],
                            'id_thumbnail' => $result['id_thumbnail'],
                            'name_thumbnail' => $result['name_thumbnail'],
                            'genres' => $genres,
                            'nb_players' => $nbPlayers,
                        );
                    }
                    return json_encode(array('status'=>'success', 'games'=> $return));

                }
                catch(Exception $e){
                    return json_encode(array('status'=>'error_genres'));
                }
            }
            else{
                return json_encode(array('status'=>'empty'));
            }
        }
        catch(Exception $e){
            return json_encode(array('status'=>'error'));
        }
    }

    function getLast(){
        try{
            include('Bdd/connexion.php');

            $sql = $bdd->prepare("SELECT id_game, name_game, date_add_game, headline_game, on_off_game, G.id_thumbnail, name_thumbnail
                              FROM game G
                              JOIN thumbnail T ON G.id_thumbnail = T.id_thumbnail
                              ORDER BY date_add_game DESC 
                              LIMIT 10");
            $sql->execute();

            $results = $sql->fetchAll();
            include 'Bdd/deconnexion.php';

            if ($results){
                try{
                    $return = array();
                    foreach ($results as $result){
                        include('Bdd/connexion.php');

                        $sql = $bdd->prepare("SELECT GEN.id_genre, name_genre 
                                          FROM genre GEN 
                                          JOIN game_genre GG ON GEN.id_genre = GG.id_genre 
                                          WHERE GG.id_game = :id_game");
                        $sql->bindParam(':id_game', $result['id_game']);
                        $sql->execute();

                        $genresTab = $sql->fetchAll();
                        include 'Bdd/deconnexion.php';

                        if ($genresTab){
                            $genres = array();
                            foreach ($genresTab as $value){
                                $genres[] = array(
                                    'id_genre' => $value['id_genre'],
                                    'name_genre' => $value['name_genre']
                                );
                            }
                        }
                        else{
                            return json_encode(array('status'=>'empty_genres'));
                        }

                        include('Bdd/connexion.php');

                        $sql = $bdd->prepare("SELECT game_nb_max_players
                                          FROM game_nb_max_players 
                                          WHERE id_game = :id_game");
                        $sql->bindParam(':id_game', $result['id_game']);
                        $sql->execute();

                        $nbPlayersTab = $sql->fetchAll();
                        include 'Bdd/deconnexion.php';

                        if ($nbPlayersTab){
                            $nbPlayers = array();
                            foreach ($nbPlayersTab as $value){
                                $nbPlayers[] = array(
                                    'nb_players' => $value['game_nb_max_players']
                                );
                            }
                        }
                        else{
                            return json_encode(array('status'=>'empty_nb_max_players'));
                        }

                        $return[] = array(
                            'id_game' => $result['id_game'],
                            'name_game' => $result['name_game'],
                            'date_add_game' => $result['date_add_game'],
                            'headline_game' => $result['headline_game'],
                            'on_off_game' => $result['on_off_game'],
                            'id_thumbnail' => $result['id_thumbnail'],
                            'name_thumbnail' => $result['name_thumbnail'],
                            'genres' => $genres,
                            'nb_players' => $nbPlayers,
                        );
                    }
                    return json_encode(array('status'=>'success', 'games'=>$return));

                }
                catch(Exception $e){
                    return json_encode(array('status'=>'error_genres'));
                }
            }
            else{
                return json_encode(array('status'=>'empty'));
            }
        }
        catch(Exception $e){
            var_dump($e);
            return json_encode(array('status'=>'error'));
        }
    }

    function getHeadline(){
        try{
            include('Bdd/connexion.php');

            $sql = $bdd->prepare("SELECT id_game, name_game, date_add_game, headline_game, on_off_game, G.id_thumbnail, name_thumbnail
                              FROM game G
                              JOIN thumbnail T ON G.id_thumbnail=T.id_thumbnail
                              WHERE headline_game = 1 
                              ORDER BY date_add_game DESC 
                              LIMIT 10");
            $sql->execute();

            $results = $sql->fetchAll();
            include 'Bdd/deconnexion.php';

            if ($results){
                try{
                    $return = array();
                    foreach ($results as $result){
                        include('Bdd/connexion.php');

                        $sql = $bdd->prepare("SELECT GEN.id_genre, name_genre 
                                          FROM genre GEN 
                                          JOIN game_genre GG ON GEN.id_genre = GG.id_genre 
                                          WHERE GG.id_game = :id_game");
                        $sql->bindParam(':id_game', $result['id_game']);
                        $sql->execute();

                        $genresTab = $sql->fetchAll();
                        include 'Bdd/deconnexion.php';

                        if ($genresTab){
                            $genres = array();
                            foreach ($genresTab as $value){
                                $genres[] = array(
                                    'id_genre' => $value['id_genre'],
                                    'name_genre' => $value['name_genre']
                                );
                            }
                        }
                        else{
                            return json_encode(array('status'=>'empty_genres'));
                        }

                        include('Bdd/connexion.php');

                        $sql = $bdd->prepare("SELECT game_nb_max_players
                                          FROM game_nb_max_players 
                                          WHERE id_game = :id_game");
                        $sql->bindParam(':id_game', $result['id_game']);
                        $sql->execute();

                        $nbPlayersTab = $sql->fetchAll();
                        include 'Bdd/deconnexion.php';

                        if ($nbPlayersTab){
                            $nbPlayers = array();
                            foreach ($nbPlayersTab as $value){
                                $nbPlayers[] = array(
                                    'nb_players' => $value['game_nb_max_players']
                                );
                            }
                        }
                        else{
                            return json_encode(array('status'=>'empty_nb_max_players'));
                        }

                        $return[] = array(
                            'id_game' => $result['id_game'],
                            'name_game' => $result['name_game'],
                            'date_add_game' => $result['date_add_game'],
                            'headline_game' => $result['headline_game'],
                            'on_off_game' => $result['on_off_game'],
                            'id_thumbnail' => $result['id_thumbnail'],
                            'name_thumbnail' => $result['name_thumbnail'],
                            'genres' => $genres,
                            'nb_players' => $nbPlayers,
                        );
                    }
                    return json_encode(array('status'=>'success', 'games'=> $return));

                }
                catch(Exception $e){
                    return json_encode(array('status'=>'error_genres'));
                }
            }
            else{
                return json_encode(array('status'=>'empty'));
            }
        }
        catch(Exception $e){
            return json_encode(array('status'=>'error'));
        }
    }

    function insertGame($data, $file){

        include_once 'Model/Thumbnail.php';
        $thumbnailProvider = new Thumbnail();
        $thumbnail = $thumbnailProvider->addThumbnail($file);

        if ($thumbnail['status'] == 'success'){
            try{
                include 'Bdd/connexion_user.php';

                $sql = $bdd->prepare('INSERT INTO game (name_game, headline_game, on_off_game, id_thumbnail) VALUES (:name_game, :headline_game, :on_off_game, :id_thumbnail)');
                $sql->bindParam(':name_game', $data['name_game']);
                $sql->bindParam(':headline_game', $data['headline_game']);
                $sql->bindParam(':on_off_game', $data['on_off_game']);
                $sql->bindParam(':id_thumbnail', $thumbnail['last_id']);
                $sql->execute();
                $lastId = $bdd->lastInsertId();

                include 'Bdd/deconnexion.php';

                try{
                    $sql_insert = "";
                    foreach ($data['genres_game'] as $key => $value){
                        $sql_insert .= "(:id_genre".$key.", :id_game".$key.")";
                    }

                    include 'Bdd/connexion_user.php';

                    $sql = $bdd->prepare('INSERT INTO game_genre (id_genre, id_game) VALUES '.$sql_insert);
                    foreach ($data['genres_game'] as $key => $value){
                        $sql->bindParam(':id_genre'.$key, $value);
                        $sql->bindParam(':id_game'.$key, $lastId);
                    }
                    $sql->execute();

                    include 'Bdd/deconnexion.php';

                    try{
                        $sql_insert = "";
                        foreach ($data['nb_max_players_game'] as $key => $value){
                            $sql_insert .= "(:game_nb_max_players".$key.", :id_game".$key.")";
                        }

                        include 'Bdd/connexion_user.php';

                        $sql = $bdd->prepare('INSERT INTO game_nb_max_players (game_nb_max_players, id_game) VALUES '.$sql_insert);
                        foreach ($data['nb_max_players_game'] as $key => $value){
                            $sql->bindParam(':game_nb_max_players'.$key, $value);
                            $sql->bindParam(':id_game'.$key, $lastId);
                        }
                        $sql->execute();

                        include 'Bdd/deconnexion.php';

                        return json_encode(array('status'=>'success'));
                    }
                    catch (Exception $e){
                        $error = $e->getCode();
                        $errorMessage = $e->getMessage();
                        include 'Bdd/deconnexion.php';
                        return json_encode(array('status'=>'error', 'error' => 'nb_max_players'));
                    }
                }
                catch (Exception $e){
                    $error = $e->getCode();
                    $errorMessage = $e->getMessage();
                    include 'Bdd/deconnexion.php';
                    return json_encode(array('status'=>'error', 'error' => 'genres'));
                }
            }
            catch (Exception $e){
                $error = $e->getCode();
                $errorMessage = $e->getMessage();
                include 'Bdd/deconnexion.php';
                if ($error == "23000"){
                    if (strpos($errorMessage, 'name_game')) {
                        return json_encode(array('status'=>'error', 'error' => 'name_game'));
                    }
                    else if (strpos($errorMessage, 'headline_game')) {
                        return json_encode(array('status'=>'error', 'error' => 'headline_game'));
                    }
                    else if (strpos($errorMessage, 'id_thumbnail')) {
                        return json_encode(array('status'=>'error', 'error' => 'id_thumbnail'));
                    }
                }
            }
        }
        else{
            return json_encode($thumbnail);
        }
    }

    function updateGame($data, $file = false){
        try{
            include 'Bdd/connexion_white.php';

            $sql = $bdd->prepare('UPDATE game SET name_game = :name_game, headline_game = :headline_game, on_off_game = :on_off_game WHERE id_game = :id_game');
            $sql->bindParam(':name_game', $data['name_game']);
            $sql->bindParam(':headline_game', $data['headline_game']);
            $sql->bindParam(':on_off_game', $data['on_off_game']);
            $sql->bindParam(':id_game', $data['id_game']);
            $sql->execute();


            include_once 'Bdd/deconnexion.php';

            try{
                $toDelete = array();

                include 'Bdd/connexion.php';

                $sql = $bdd->prepare('SELECT id_genre WHERE id_game = :id_game');
                $sql->bindParam(':id_game', $data['id_game']);
                $sql->execute();
                $results = $sql->fetchAll();

                foreach ($results as $value){
                    if (!in_array($value, $data['genres_game'])){
                        $toDelete[] = $value;
                    }
                }
                foreach ($data['genres'] as $value){
                    if(!in_array($value, $results)){
                        $toAdd[] = $value;
                    }
                }

                include_once 'Bdd/deconnexion.php';

                try{
                    $sql_delete = "";
                    $nbToDelete = count($toDelete);
                    $loop = 1;

                    include 'Bdd/connexion.php';

                    foreach ($toDelete as $key => $value){
                        $sql_delete .= '(id_game = :id_game'.$key.' AND id_genre = :id_genre'.$key.')';
                        $loop++;
                        if ($loop != $nbToDelete){
                            $sql_delete .= " OR ";
                        }
                    }

                    $sql = $bdd->prepare('DELETE FROM game_genre WHERE '.$sql_delete);
                    foreach ($toDelete as $key => $value) {
                        $sql->bindParam(':id_game'.$key, $data['id_game']);
                        $sql->bindParam(':id_genre'.$key, $value);
                    }
                    $sql->execute();

                    include_once 'Bdd/deconnexion.php';

                    try{
                        $sql_add = "";
                        $nbToAdd = count($toAdd);
                        $loop = 1;

                        include 'Bdd/connexion.php';

                        foreach ($toAdd as $key => $value){
                            $sql_add .= '(:id_game'.$key.', :id_genre'.$key.')';
                            $loop++;
                            if ($loop != $nbToAdd){
                                $sql_add .= ", ";
                            }
                        }

                        $sql = $bdd->prepare('INSERT INTO game_genre(id_game, id_genre) VALUES '.$sql_add);
                        foreach ($toAdd as $key => $value) {
                            $sql->bindParam(':id_game'.$key, $data['id_game']);
                            $sql->bindParam(':id_genre'.$key, $value);
                        }
                        $sql->execute();

                        include_once 'Bdd/deconnexion.php';

                        if ($file){
                            include_once 'Model/Thumbnail.php';
                            $thumbnailProvider = new Thumbnail();
                            $thumbnail = $thumbnailProvider->updateThumbnail($data['id_thumbnail'], $file);

                            return json_encode($thumbnail);
                        }

                        return json_encode(array('status'=>'success'));
                    }
                    catch(Exception $e){
                        $error = $e->getCode();
                        $errorMessage = $e->getMessage();
                        return json_encode(array('status'=>'error', 'error' => 'add_genres'));
                    }
                }
                catch(Exception $e){
                    $error = $e->getCode();
                    $errorMessage = $e->getMessage();
                    return json_encode(array('status'=>'error', 'error' => 'delete_genres'));
                }
            }
            catch(Exception $e){
                $error = $e->getCode();
                $errorMessage = $e->getMessage();
                if ($error == "23000"){
                    if (strpos($errorMessage, 'id_game')) {
                        return json_encode(array('status'=>'error', 'error' => 'id_game'));
                    }
                    else if (strpos($errorMessage, 'headline_game')) {
                        return json_encode(array('status'=>'error', 'error' => 'headline_game'));
                    }
                    else if (strpos($errorMessage, 'id_thumbnail')) {
                        return json_encode(array('status'=>'error', 'error' => 'id_thumbnail'));
                    }
                }
            }
        }
        catch(Exception $e){
            $error = $e->getCode();
            $errorMessage = $e->getMessage();
            if ($error == "23000"){
                if (strpos($errorMessage, 'name_game')) {
                    return json_encode(array('status'=>'error', 'error' => 'name_game'));
                }
                else if (strpos($errorMessage, 'headline_game')) {
                    return json_encode(array('status'=>'error', 'error' => 'headline_game'));
                }
                else if (strpos($errorMessage, 'on_off_game')) {
                    return json_encode(array('status'=>'error', 'error' => 'on_off_game'));
                }
                else if (strpos($errorMessage, 'id_game')) {
                    return json_encode(array('status'=>'error', 'error' => 'id_game'));
                }
            }
        }
    }

    function onOffGame($idGame){
        try{
            include 'Bdd/connexion.php';

            $sql = $bdd->prepare('SELECT on_off_game FROM game WHERE id_game = :id_game');
            $sql->bindParam(':id_game', $idGame);
            $sql->execute();
            $result = $sql->fetch();

            include_once 'Bdd/deconnexion.php';

            if ($result['on_off_game'] == 1){
                $onOffGame = 0;
            }
            else{
                $onOffGame = 1;
            }

            include 'Bdd/connexion_white.php';

            try{
                $sql = $bdd->prepare('UPDATE game SET on_off_game = :on_off_game WHERE id_game = :id_game');
                $sql->bindParam(':on_off_game', $onOffGame);
                $sql->bindParam(':id_game', $idGame);
                $sql->execute();

                include_once 'Bdd/deconnexion.php';

                return json_encode(array('status' => 'success'));
            }
            catch(Exception $e){
                $error = $e->getCode();
                $errorMessage = $e->getMessage();

                if ($error == "23000"){
                    if (strpos($errorMessage, 'id_game')) {
                        return json_encode(array('status'=>'error', 'error' => 'id_game'));
                    }
                }
            }
        }
        catch(Exception $e){
            $error = $e->getCode();
            $errorMessage = $e->getMessage();

            if ($error == "23000"){
                if (strpos($errorMessage, 'id_game')) {
                    return json_encode(array('status'=>'error', 'error' => 'id_game'));
                }
            }
        }
    }

    function headlineGame($idGame){
        try{
            include 'Bdd/connexion.php';

            $sql = $bdd->prepare('SELECT headline_game FROM game WHERE id_game = :id_game');
            $sql->bindParam(':id_game', $idGame);
            $sql->execute();
            $result = $sql->fetch();

            include_once 'Bdd/deconnexion.php';

            if ($result['headline_game'] == 1){
                $headlineGame = 0;
            }
            else{
                $headlineGame = 1;
            }

            include 'Bdd/connexion_white.php';

            try{
                $sql = $bdd->prepare('UPDATE game SET headline_game = :headline_game WHERE id_game = :id_game');
                $sql->bindParam(':headline_game', $headlineGame);
                $sql->bindParam(':id_game', $idGame);
                $sql->execute();

                include_once 'Bdd/deconnexion.php';

                return json_encode(array('status' => 'success'));
            }
            catch(Exception $e){
                $error = $e->getCode();
                $errorMessage = $e->getMessage();

                if ($error == "23000"){
                    if (strpos($errorMessage, 'id_game')) {
                        return json_encode(array('status'=>'error', 'error' => 'id_game'));
                    }
                }
            }
        }
        catch(Exception $e){
            $error = $e->getCode();
            $errorMessage = $e->getMessage();

            if ($error == "23000"){
                if (strpos($errorMessage, 'id_game')) {
                    return json_encode(array('status'=>'error', 'error' => 'id_game'));
                }
            }
        }
    }

    function deleteGame($idGame){
        try{
            include 'Bdd/connexion_gold.php';

            $sql = $bdd->prepare("DELETE FROM game_genre WHERE id_game = :id_game");
            $sql->bindParam(':id_game', $idGame);
            $sql->execute();

            include 'Bdd/deconnexion.php';

            try{
                include 'Bdd/connexion_gold.php';

                $sql = $bdd->prepare("DELETE FROM game_nb_max_players WHERE id_game = :id_game");
                $sql->bindParam(':id_game', $idGame);
                $sql->execute();

                include 'Bdd/deconnexion.php';

                try{
                    include 'Bdd/connexion_gold.php';

                    $sql = $bdd->prepare("DELETE FROM game WHERE id_game = :id_game");
                    $sql->bindParam(':id_game', $idGame);
                    $sql->execute();

                    include 'Bdd/deconnexion.php';

                    return array('status'=>'success');
                }
                catch(Exception $e){
                    $error = $e->getCode();
                    $errorMessage = $e->getMessage();

                    if ($error == "23000"){
                        if (strpos($errorMessage, 'id_game')) {
                            return array('status'=>'error', 'error' => 'id_game');
                        }
                    }
                }
            }
            catch(Exception $e){
                $error = $e->getCode();
                $errorMessage = $e->getMessage();

                if ($error == "23000"){
                    if (strpos($errorMessage, 'id_game')) {
                        return array('status'=>'error', 'error' => 'id_game');
                    }
                }
            }
        }
        catch(Exception $e){
            $error = $e->getCode();
            $errorMessage = $e->getMessage();

            if ($error == "23000"){
                if (strpos($errorMessage, 'id_game')) {
                    return array('status'=>'error', 'error' => 'id_game');
                }
            }
        }
    }

    function getLastGamesWithNicknames($id_user){
        try{
            include('Bdd/connexion.php');

            $sql = $bdd->prepare("SELECT DISTINCT G.id_game, name_game, date_add_game, headline_game, on_off_game, G.id_thumbnail, name_thumbnail, nickname
                              FROM game G
                              JOIN thumbnail T ON G.id_thumbnail = T.id_thumbnail
                              JOIN nickname_user_game NUG ON NUG.id_game = G.id_game
                              JOIN matchmaking_archive MA ON MA.id_game = G.id_game
                              JOIN matchmaking_players_archive MPA ON MPA.id_matchmaking_archive = MA.id_matchmaking_archive
                              WHERE MPA.id_user = :id_user 
                              ORDER BY MA.date_archive DESC 
                              LIMIT 10");
            $sql->bindParam(':id_user', $id_user);
            $sql->execute();

            $results = $sql->fetchAll();
            include 'Bdd/deconnexion.php';

            if ($results){
                return json_encode(array('status'=>'success', 'data' => $results));
            }
            else{
                return json_encode(array('status'=>'empty'));
            }
        }
        catch(Exception $e){
            return json_encode(array('status'=>'error'));
        }
    }

    function getGamesWithNicknames($id_user){
        try{
            include('Bdd/connexion.php');

            $sql = $bdd->prepare("SELECT DISTINCT G.id_game, name_game, date_add_game, headline_game, on_off_game, G.id_thumbnail, name_thumbnail, nickname
                              FROM game G
                              JOIN thumbnail T ON G.id_thumbnail = T.id_thumbnail
                              JOIN nickname_user_game NUG ON NUG.id_game = G.id_game
                              WHERE NUG.id_user = :id_user 
                              ORDER BY name_game DESC 
                              ");
            $sql->bindParam(':id_user', $id_user);
            $sql->execute();

            $results = $sql->fetchAll();
            include 'Bdd/deconnexion.php';

            if ($results){
                return json_encode(array('status'=>'success', 'data' => $results));
            }
            else{
                return json_encode(array('status'=>'empty'));
            }
        }
        catch(Exception $e){
            return json_encode(array('status'=>'error'));
        }
    }
}