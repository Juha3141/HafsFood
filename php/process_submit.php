<?php
function get_affinities_from_db($date) {
    $data = get_user_data($_SESSION['username']);
    $json_entire = json_decode($data['survey_info'] , true);
    $affinities = [];
    foreach($json_entire['days_voted'] as $one_day) {
        if($one_day['date'] == $date) {
            $affinities = $one_day['menu_voted'];
            break;
        }
    }
    return $affinities;
}

function get_affinities_from_post($meal_table , $meal_name , $day) {
    $menu_results = get_menus($meal_table,$day);
    $affinities = [];
    for($i = 0; $i < count($menu_results); $i++) {
        
        $var_name = "affinity_".$i."_".$meal_name;
        // For debug
        $affinities[] = [$menu_results[$i]['name'] , $_POST[$var_name]];
        // For debug
        // echo $var_name." :".$_POST[$var_name].",".$menu_results[$i]['name']."<br>";
    }
    return $affinities;
}

function add_affinity($json_entire , $affinities , $day) {
    $menu_voted  = [];
    foreach($affinities as $menus) { // $affinities = [ ["menu name" , "affinity"]  , ...]
        if($menus[1] != null) {
            // $menus[0] = menu name
            // $menus[1] = affinity
            $menu_voted[] = ["meal"=>"breakfast" , "name"=>$menus[0],"affinity"=>$menus[1]];
        }
    }
    $array = ["date"=>$day , "menu_voted"=>$menu_voted];
    $json_entire['days_voted'][] = $array;
    return $json_entire;
}

function increment_vote($date , $menu_name , $db_name , $vote_option , $vote_inc) {
    $connect = connect_server();
    $sql_req = "SELECT total_vote,good_vote,middle_vote,bad_vote FROM ".$db_name." WHERE name=\"".$menu_name."\" AND date=\"".$date."\";";
    $result = mysqli_query($connect , $sql_req);
    if($result == null) {
        return;
    }
    $data = mysqli_fetch_assoc($result);
    $data[$vote_option] += $vote_enc;
    $sql_req = "UPDATE "
}
?>