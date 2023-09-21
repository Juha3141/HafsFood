<?php

function json_remove_menu($json_string , $meal , $date , $id) {
    $json_entire = json_decode($json_string , true);
    $menu_voted = [];
    $d_index = 0;
    for($d = 0; $d < count($json_entire['days_voted']); $d++) {
        if($json_entire['days_voted'][$d]['date'] == $date) {
            $menu_voted = $json_entire['days_voted'][$d]['menu_voted'];
            $d_index = $d;
            break;
        }
    }
    if($menu_voted == []) {
        return "";
    }
    for($i = 0; $i < count($menu_voted); $i++) {
        if($menu_voted[$i]['meal'] == $meal && $menu_voted[$i]['id'] == $id) {
            array_splice($json_entire['days_voted'][$d_index]['menu_voted'] , $i , 1);
        }
    }
    $json_updated = json_encode($json_entire , JSON_UNESCAPED_UNICODE);
    return $json_updated;
}

function json_modify_menu($json_string , $meal , $date , $name , $new_name) {
    $json_entire = json_decode($json_string , true);
    $menu_voted = [];
    $d_index = 0;
    for($d = 0; $d < count($json_entire['days_voted']); $d++) {
        if($json_entire['days_voted'][$d]['date'] == $date) {
            $menu_voted = $json_entire['days_voted'][$d]['menu_voted'];
            $d_index = $d;
            break;
        }
    }
    if($menu_voted == []) {
        return "";
    }
    for($i = 0; $i < count($menu_voted); $i++) {
        if($menu_voted[$i]['meal'] == $meal && $menu_voted[$i]['name'] == $name) {
            $json_entire['days_voted'][$d_index]['menu_voted'][$i]['name'] = $new_name;
        }
    }
    $json_updated = json_encode($json_entire , JSON_UNESCAPED_UNICODE);
    return $json_updated;
}


function assign_new_id() {
    $string_list = "1234567890qwertyuiopasdfghjklzxcvnmQWERTYUIOPASDFGHJKLZXCVBNM";
    $rand_string = "";
    for($i = 0; $i < 50; $i++) {
        $rand_string .= $string_list[rand(0,strlen($string_list))];
    }
    return $rand_string;
}

function insert_user_db($unique_id) {
    $connect = connect_server();
    $sql_req = "INSERT INTO user_list (unique_id,survey_info) VALUE('$unique_id' , '{ \"days_voted\":[] }');";
    if(!mysqli_query($connect , $sql_req)) {
        mysqli_close($connect);
        return false;
    }
    mysqli_close($connect);
    return true;
}

function get_survey_data($unique_id) {
    $connect = connect_server();
    $sql_req = "SELECT survey_info FROM user_list WHERE unique_id=\"$unique_id\";";
    $result = mysqli_query($connect , $sql_req);
    if(!$result) {
        mysqli_close($connect);
        return null;
    }
    $row = mysqli_fetch_assoc($result);
    mysqli_close($connect);
    if(!$row) return null;
    return $row['survey_info'];
}

function rewrite_survey_data($unique_id , $new_info) {
    $connect = connect_server();
    $sql_req = "UPDATE user_list SET survey_info='$new_info' WHERE unique_id=\"$unique_id\"";
    $result = mysqli_query($connect , $sql_req);
    mysqli_close($connect);
    if(!$result) {
        return false;
    }
    return true;
}

function get_menu_id_list($date) {
    $connect = connect_server();
    
    $data = ["breakfast"=>[] , "lunch"=>[] , "dinner"=>[]];
    foreach(["breakfast","lunch","dinner"] as $table) {
        $sql_req = "SELECT * FROM menu_list_".$table." WHERE date=\"".$date."\";";
        $result = mysqli_query($connect , $sql_req);
        while($row = mysqli_fetch_assoc($result)) {
            $data[$table][] = $row['id'];
        }
    }
    mysqli_close($connect);
    return $data;
}

// local function
function does_exist($menu , $menu_id_list) {
    foreach($menu_id_list[$menu['meal']] as $ids) {
        if($menu['id'] == $ids) {
            return true;
        }
    }
    return false;
}

function update_removed() {
    $json_string = get_survey_data($_COOKIE['unique_id']);
    $json_entire = json_decode($json_string , true);
    for($d = 0; $d < count($json_entire['days_voted']); $d++) {
        echo $json_entire[$d]['date']."<br>";
        $menu_id_list = get_menu_id_list($json_entire['days_voted'][$d]['date'] , $table);
        for($m = 0; $m < count($json_entire['days_voted'][$d]['menu_voted']); $m++) {
            if(!does_exist($json_entire['days_voted'][$d]['menu_voted'][$m] , $menu_id_list)) {
                array_splice($json_entire['days_voted'][$d]['menu_voted'] , $m , 1);
            }
        }
    }
    $json_updated = json_encode($json_entire , JSON_UNESCAPED_UNICODE);
    rewrite_survey_data($_COOKIE['unique_id'] , $json_updated);
}

?>