<?php
function process_submit($id , $pw , $database) {
    $id = block_sql_injection($id);
    // connect to server
    $pw_enc = hash("sha256" , $pw);
    $connect = connect_server();
    $sql_req = "SELECT * FROM ".$database;
    $result = mysqli_query($connect , $sql_req);
    $user_info = 0;
    // search from ID
    if($result == false) {
        return -1;
    }
    while($row = mysqli_fetch_assoc($result)) {
        if($row['id'] == $id) {
            $user_info = $row;
            break;
        }
    }
    mysqli_close($connect);
    if(!$user_info) {
        return -1;
    }
    if($user_info['pw_enc'] == $pw_enc) {
        return 0;
    }
    return -2;
}

function id_exist($id , $database) {
    $id = block_sql_injection($id);
    // connect to server
    $connect = connect_server();
    $sql_req = "SELECT * FROM ".$database;
    $result = mysqli_query($connect , $sql_req);
    $user_info = 0;
    // search from ID
    if($result == false) {
        return -1;
    }
    while($row = mysqli_fetch_assoc($result)) {
        if($row['id'] == $id) {
            $user_info = $row;
            break;
        }
    }
    mysqli_close($connect);
    if(!$user_info) {
        return false;
    }
    return true;
}

function check_valid_id($id) {
    if(preg_match("/[\xA1-\xFE][\xA1-\xFE]/" , $id)) {
        return false;
    }
    if(preg_match("/[ #\&\+%@=\/\\\:;,\.'\"\^`~\!\?\*$#<>()\[\]\{\}]/i" , $id)) {
        return false;
    }
    return true;
}

function register_user($id , $pw) {
    $pw_enc = hash("sha256" , $pw);
    $connect = connect_server();
    $sql_req = "INSERT INTO user_list (id,pw_enc,join_date,survey_info) VALUE(\"$id\",\"$pw_enc\",now(),'{\"days_voted\":[]}');";
    $result = mysqli_query($connect , $sql_req);
    return !($result == false);
}

?>