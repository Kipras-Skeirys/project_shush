<?php

    session_start();

    $lorem_ipsum ="Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi ac lacus leo. Phasellus augue turpis, blandit nec venenatis eget, molestie efficitur nunc. In consectetur elementum lacus, id euismod lorem tincidunt vel. Praesent ac imperdiet tortor. Praesent sodales elit arcu, ac consequat sapien vestibulum a. Praesent vitae massa nibh. Proin laoreet laoreet condimentum. Suspendisse ullamcorper tincidunt luctus. Nullam placerat nulla id ultricies molestie. Vivamus vitae ullamcorper eros. Suspendisse commodo elementum mi eget ultrices. Integer cursus tempor metus eleifend varius. Cras tempus ullamcorper mauris efficitur vulputate. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi ac lacus leo. Phasellus augue turpis, blandit nec venenatis eget, molestie efficitur nunc. In consectetur elementum lacus, id euismod lorem tincidunt vel. Praesent ac imperdiet tortor. Praesent sodales elit arcu, ac consequat sapien vestibulum a. Praesent vitae massa nibh. Proin laoreet laoreet condimentum. Suspendisse ullamcorper tincidunt luctus. Nullam placerat nulla id ultricies molestie. Vivamus vitae ullamcorper eros. Suspendisse commodo elementum mi eget ultrices. Integer cursus tempor metus eleifend varius. Cras tempus ullamcorper mauris efficitur vulputate. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi ac lacus leo. Phasellus augue turpis, blandit nec venenatis eget, molestie efficitur nunc. In consectetur elementum lacus, id euismod lorem tincidunt vel. Praesent ac imperdiet tortor. Praesent sodales elit arcu, ac consequat sapien vestibulum a. Praesent vitae massa nibh. Proin laoreet laoreet condimentum. Suspendisse ullamcorper tincidunt luctus. Nullam placerat nulla id ultricies molestie. Vivamus vitae ullamcorper eros. Suspendisse commodo elementum mi eget ultrices. Integer cursus tempor metus eleifend varius. Cras tempus ullamcorper mauris efficitur vulputate.";
    
    // OPTIONS
    $strict_session = false;
    $auto_show = true;
    $burner_messages = true;
    
    define("DB_ADDRESS","localhost");
    define("DB_USER","root");
    define("DB_PASS","mysql");
    define("DB_NAME","chat");
    

    //FORM CREATE
    if(!empty($_POST['create'])){
        $json = json_decode($_POST['create'], true);
        
        //RANDOM STRING
        $ran_session_id = bin2hex(openssl_random_pseudo_bytes(10, $cstrong));
        $unique_session = False;

        $mysqli = new mysqli(DB_ADDRESS, DB_USER, DB_PASS, DB_NAME);
        
        if ($mysqli->connect_error) {
            echo $mysqli->connect_error;
            exit;
        }else{
            while(!$unique_session){
                
                // DOES RANDOM STRING ALREADY EXIST
                $sql = 'SELECT session_id FROM sessions WHERE session_id = "'.$ran_session_id.'" LIMIT 1';
                if($mysqli->query($sql)->num_rows){
                    
                    // GENRATING NEW ONE
                    $ran_session_id = bin2hex(openssl_random_pseudo_bytes(10, $cstrong));
                }else{
                    
                    // SESSION OR MESSAGE
                    if($json['selector'] == 'session'){
                        $alias = preg_replace( '/\s*/m', '', (htmlentities($json['alias'], ENT_QUOTES, 'utf-8')));

                        if(empty($alias)){
                            $alias = 'Anonymous';
                        }

                        // INSERTING user_alias, session_id and user_id
                        $mysqli->query('INSERT INTO users SET user_alias = "'.$alias.'"');
                        $user_id = $mysqli->insert_id;
                        $mysqli->query('INSERT INTO sessions SET session_id = "'.$ran_session_id.'", user_id = "'.$user_id.'"');


                        $_SESSION["user_id"] = $user_id;
                        $_SESSION["session_id"] = $ran_session_id;
                        $_SESSION['last_msg'] = 0;


                        echo $ran_session_id;

                        // No point in having this at all, but I'll keep it for now
                        $unique_session = true;
                    }
                    if($json['selector'] == 'message'){
                        echo "message link...";
                    }
                    
                }
            }
        }
    }

    
    // GETTING REAL/FAKE MESSAGE FROM DB
    if(isset($_POST['get'])){
        $mysqli = new mysqli(DB_ADDRESS, DB_USER, DB_PASS, DB_NAME);

        if($mysqli->connect_error){
            echo $mysqli->connect_error;
            exit;
        }else{
            
            $result = $mysqli->query('SELECT user_id, message, time, id FROM messages WHERE session_id = "'.$_SESSION['session_id'].'" AND time > "'.$_SESSION['last_msg'].'"');
            if($result->num_rows){
                while($row = $result->fetch_assoc()){
                    
                    $row['message'] = html_entity_decode($row['message'], ENT_QUOTES, 'UTF-8');
                    
                    // MESSAGE FROM FRIEND
                    if($row['user_id'] != $_SESSION['user_id']){
                        
                        // FRIENDS ALIAS
                        $alias_result = $mysqli->query('SELECT user_alias FROM users WHERE user_id = "'.$row['user_id'].'" LIMIT 1');
                        if($alias_result->num_rows){
                            $row['friend_alias'] = html_entity_decode($alias_result->fetch_assoc()['user_alias'], ENT_QUOTES, 'UTF-8');
                        }
                        
                        // TURNING MESSAGE TO LOREM IPSUM
                        if(!$auto_show){
                            $row['message'] = substr($lorem_ipsum,0,strlen($row['message']));
                        }
                    }
                    
                    // AUTO-SHOW
                    if ($auto_show){
                        $row['auto_show'] = true;
                    }else{
                        $row['auto_show'] = false;
                    }
                    
                    // ADDING FINAL OBJECTS
                    $row['local_user'] = $_SESSION['user_id'];
                    if(!isset($row['friend_alias'])){$row['friend_alias'] = 'You';}
                    $new_messages[] = $row;
                }
                
                // UPDASTING $_SESSION['last_msg']
                $_SESSION['last_msg'] = $new_messages[sizeof($new_messages) - 1]['time'];
                
            }
            
            // RETURNING CALLBACK / USER STATUS
            $stat = status();
            if ($_SESSION['friend_status'] != $stat){
                $new_messages['status'] = $stat;
                echo json_encode($new_messages);
                $_SESSION['friend_status'] = $stat;
            }else if(!empty($new_messages)){
                echo json_encode($new_messages);
            }
            
        } 
    }

    
    // MESSAGE UPLOADING TO DB - INJECTION SECURED WITH htmlentities
    if(!empty($_POST["msg"])){
        $msg = htmlentities($_POST["msg"], ENT_QUOTES, "utf-8");
    
        $mysqli = new mysqli(DB_ADDRESS, DB_USER, DB_PASS, DB_NAME);
        if($mysqli->connect_error){
            echo $mysqli->connect_error;
            exit;
        }else{
            $mysqli->query('INSERT INTO messages SET user_id = "'.$_SESSION['user_id'].'", session_id = "'.$_SESSION['session_id'].'", message = "'.$msg.'", time = "'.microtime(true).'"');
        }
    }
    

    // REVEALING MESSAGE
    if(!empty($_POST['reveal_id'])){
        
        // After reveal remove message from db
        $mysqli = new mysqli(DB_ADDRESS, DB_USER, DB_PASS, DB_NAME);
        $result = $mysqli->query('SELECT user_id, message, time, id FROM messages WHERE session_id ="'.$_SESSION['session_id'].'" AND id = "'.$_POST['reveal_id'].'"');
        // Checking if match was found
        if($result->num_rows){
            // Fetching all new rows to $new_messages array
            while($row = $result->fetch_assoc()){
                $row['message'] = html_entity_decode($row['message'], ENT_QUOTES, 'UTF-8');
                $new_messages[] = $row;
            }
            // Encoding array to json and echo'ing
            echo json_encode($new_messages);
        }
    }


    // STATUS
    if(isset($_POST['status']) && $_POST['status'] == '0' || $_POST['status'] == '1'){
        
        $mysqli = new mysqli(DB_ADDRESS, DB_USER, DB_PASS, DB_NAME);
        if($mysqli->connect_error){
            echo $mysqli->connect_error;
            exit;
        }else{
            $mysqli->query('UPDATE users SET status ="'.$_POST['status'].'" WHERE user_id ="'.$_SESSION['user_id'].'"');
            $_SESSION['friend_status'] = status();
            echo $_SESSION['friend_status'];
        }
    }
    

    function status(){
        $mysqli = new mysqli(DB_ADDRESS, DB_USER, DB_PASS, DB_NAME);
        $result = $mysqli->query('SELECT user_id FROM sessions WHERE session_id ="'.$_SESSION['session_id'].'" AND user_id != "'.$_SESSION['user_id'].'"');
        if($result->num_rows){
            $user = $result->fetch_assoc();
            $result = $mysqli->query('SELECT status FROM users WHERE user_id = "'.$user['user_id'].'"');
            if($result->num_rows){
                // FRIEND FOUND ON DB
                return $result->fetch_assoc()['status'];
            }
        }else{
            // FRIEND COULDN'T BE FOUND ON DB
            return '3';
        }
    }


    //file_put_contents('logs.txt', print_r($_POST, true), FILE_APPEND);
    
?>