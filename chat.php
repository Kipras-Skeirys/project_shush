<?php
    session_start();

    // INCLUDINg process.php SO THAT MYSQL INFORMATION STAYS IN ONE PLACE
    include 'process.php';
    
    $enable_chat = true;

    $mysqli = new mysqli(DB_ADDRESS, DB_USER, DB_PASS, DB_NAME);

    if ($mysqli->connect_error) {
        echo $mysqli->connect_error;
        exit;
    }
    
    $url = htmlentities($_GET['q'], ENT_QUOTES, 'UTF-8');

    $result = $mysqli->query('SELECT session_id FROM sessions WHERE session_id = "'.$url.'"');
    if ($result->num_rows){
        if($_SESSION['session_id'] == $url){
            // -REGISTERED USER-
            echo file_get_contents("chat.html");
            exit;
        }else if($result->num_rows >= 2){
            // -QUICK JOIN USER-
            header('Location: create.php');
            exit;
            // CURRENTLY NOT SUPPORTING "Quick join" SO RE-DIRECTING TO create.php
        }else{
            // NEW USER
            echo file_get_contents("alias.html");
            if(!empty($_POST["join"])){
                // ALIAS HANDLER
                $alias = preg_replace( '/\s*/m', '', (htmlentities($_POST["alias"], ENT_QUOTES, 'utf-8')));
                if(empty($alias)){
                    $alias = 'Anonymous';
                }
                // INSERTING user_alias, session_id and user_id
                $mysqli->query('INSERT INTO users SET user_alias = "'.$alias.'"');
                $user_id = $mysqli->insert_id;
                $mysqli->query('INSERT INTO sessions SET session_id = "'.$url.'", user_id = "'.$user_id.'"');
                // SETINGUP $_SESSION VARIABLES
                $_SESSION["user_id"] = $user_id;
                $_SESSION["session_id"] = $url;
                $_SESSION['last_msg'] = 0;
                header('Location: chat.php?q='.$url);
                exit;
            }
        }
    }else{
        // IF URL DID NOT MATCH
        header('Location: create.php');
    }
?>
