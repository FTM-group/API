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

                    $sql = $bdd->prepare("SELECT game_nb_players
                                          FROM game_nb_players 
                                          WHERE id_game = :id_game");
                    $sql->bindParam(':id_game', $result['id_game']);
                    $sql->execute();

                    $nbPlayersTab = $sql->fetchAll();
                    include 'Bdd/deconnexion.php';

                    if ($nbPlayersTab){
                        $nbPlayers = array();
                        foreach ($nbPlayersTab as $value){
                            $nbPlayers[] = array(
                                'nb_players' => $value['game_nb_players']
                            );
                        }
                    }
                    else{
                        return json_encode(array('status'=>'empty_nb_players'));
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

                        $sql = $bdd->prepare("SELECT game_nb_players
                                          FROM game_nb_players 
                                          WHERE id_game = :id_game");
                        $sql->bindParam(':id_game', $result['id_game']);
                        $sql->execute();

                        $nbPlayersTab = $sql->fetchAll();
                        include 'Bdd/deconnexion.php';

                        if ($nbPlayersTab){
                            $nbPlayers = array();
                            foreach ($nbPlayersTab as $value){
                                $nbPlayers[] = array(
                                    'nb_players' => $value['game_nb_players']
                                );
                            }
                        }
                        else{
                            return json_encode(array('status'=>'empty_nb_players'));
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

                        $sql = $bdd->prepare("SELECT game_nb_players
                                          FROM game_nb_players 
                                          WHERE id_game = :id_game");
                        $sql->bindParam(':id_game', $result['id_game']);
                        $sql->execute();

                        $nbPlayersTab = $sql->fetchAll();
                        include 'Bdd/deconnexion.php';

                        if ($nbPlayersTab){
                            $nbPlayers = array();
                            foreach ($nbPlayersTab as $value){
                                $nbPlayers[] = array(
                                    'nb_players' => $value['game_nb_players']
                                );
                            }
                        }
                        else{
                            return json_encode(array('status'=>'empty_nb_players'));
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

                        $sql = $bdd->prepare("SELECT game_nb_players
                                          FROM game_nb_players 
                                          WHERE id_game = :id_game");
                        $sql->bindParam(':id_game', $result['id_game']);
                        $sql->execute();

                        $nbPlayersTab = $sql->fetchAll();
                        include 'Bdd/deconnexion.php';

                        if ($nbPlayersTab){
                            $nbPlayers = array();
                            foreach ($nbPlayersTab as $value){
                                $nbPlayers[] = array(
                                    'nb_players' => $value['game_nb_players']
                                );
                            }
                        }
                        else{
                            return json_encode(array('status'=>'empty_nb_players'));
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

    function insertGame($name, $file, $genre, $nbPlayers, $headline){

        include_once 'Model/Thumbnail.php';
        $thumbnailProvider = new Thumbnail();
        $thumbnail = $thumbnailProvider->addThumbnail($file);

        if ($thumbnail['status'] == 'success'){
            include 'Bdd/connexion_user.php';

            try{
                $sql = $bdd->prepare('INSERT INTO game (name_game, number_players_game, headline_game, id_thumbnail) VALUES (:name_game, :genre_game, :number_players_game, :headline_game, :id_thumbnail)');
                $sql->bindParam(':name_game', $name);
                $sql->bindParam(':genre_game', $genre);
                $sql->bindParam(':number_players_game', $nbPlayers);
                $sql->bindParam(':headline_game', $headline);
                $sql->bindParam(':id_thumbnail', $thumbnail['last_id']);
                $sql->execute();
                include 'Bdd/deconnexion.php';
                return json_encode(array('status'=>'success'));
            }
            catch (Exception $e){
                $error = $e->getCode();
                $errorMessage = $e->getMessage();
                include 'Bdd/deconnexion.php';
                if ($error == "23000"){
                    if (strpos($errorMessage, 'name_game')) {
                        return json_encode(array('status'=>'error', 'error' => 'name_game'));
                    }
                    else if (strpos($errorMessage, 'genre_game')) {
                        return json_encode(array('status'=>'error', 'error' => 'genre_game'));
                    }
                    else if (strpos($errorMessage, 'number_players_game')) {
                        return json_encode(array('status'=>'error', 'error' => 'number_players_game'));
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

            $sql = $bdd->prepare('UPDATE game SET name_game = :name_game, number_players_game = :number_players_game, headline_game = :headline_game, on_off_game = :on_off_game WHERE id_game = :id_game');
            $sql->bindParam(':name_game', $data['name_game']);
            $sql->bindParam(':number_players_game', $data['number_players_game']);
            $sql->bindParam(':headline_game', $data['headline_game']);
            $sql->bindParam(':on_off_game', $data['on_off_game']);
            $sql->bindParam(':id_game', $data['id_game']);
            $sql->execute();


            include_once 'Bdd/deconnexion.php';

            if ($file){
                include_once 'Model/Thumbnail.php';
                $thumbnailProvider = new Thumbnail();
                $thumbnail = $thumbnailProvider->updateThumbnail($data['id_thumbnail'], $file);

                return json_encode($thumbnail);
            }
        }
        catch(Exception $e){
            $error = $e->getCode();
            $errorMessage = $e->getMessage();
            include 'Bdd/deconnexion.php';

            if ($error == "23000"){
                if (strpos($errorMessage, 'name_game')) {
                    return json_encode(array('status'=>'error', 'error' => 'name_game'));
                }
                else if (strpos($errorMessage, 'number_players_game')) {
                    return json_encode(array('status'=>'error', 'error' => 'number_players_game'));
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

    function onOffGame($id){
        try{
            include 'Bdd/connexion.php';

            $sql = $bdd->prepare('SELECT on_off_game FROM game WHERE id_game = :id_game');
            $sql->bindParam(':id_game', $id);
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
                $sql->bindParam(':id_game', $id);
                $sql->execute();

                include_once 'Bdd/deconnexion.php';

                return json_encode(array('status' => 'success'));
            }
            catch(Exception $e){
                $error = $e->getCode();
                $errorMessage = $e->getMessage();
                include 'Bdd/deconnexion.php';

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
            include 'Bdd/deconnexion.php';

            if ($error == "23000"){
                if (strpos($errorMessage, 'id_game')) {
                    return json_encode(array('status'=>'error', 'error' => 'id_game'));
                }
            }
        }
    }

    function headlineGame($id){
        try{
            include 'Bdd/connexion.php';

            $sql = $bdd->prepare('SELECT headline_game FROM game WHERE id_game = :id_game');
            $sql->bindParam(':id_game', $id);
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
                $sql->bindParam(':id_game', $id);
                $sql->execute();

                include_once 'Bdd/deconnexion.php';

                return json_encode(array('status' => 'success'));
            }
            catch(Exception $e){
                $error = $e->getCode();
                $errorMessage = $e->getMessage();
                include 'Bdd/deconnexion.php';

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
            include 'Bdd/deconnexion.php';

            if ($error == "23000"){
                if (strpos($errorMessage, 'id_game')) {
                    return json_encode(array('status'=>'error', 'error' => 'id_game'));
                }
            }
        }
    }

    function deleteGame($id){
        try{
            include 'Bdd/connexion_gold.php';

            $sql = $bdd->prepare("DELETE FROM game WHERE id_game = :id_game");
            $sql->bindParam(':id_genre', $id);
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