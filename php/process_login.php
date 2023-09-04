<?php
function process_submit($id , $pw , $database) {
    if(block_sql_injection($id) == 1) {
        echo "<br>No SQL Injection haha";
        return -69420;
    }
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

?>