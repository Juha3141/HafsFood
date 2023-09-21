<?php 
include('./php/server_communication.php');
include('./php/index_element_manage.php');
include('./php/user_data.php');
include('./php/process_submit.php');

session_start();

// echo $_POST['requested_day'];
$menu_count = get_menu_count($_POST['requested_day']);

$submitted_count = 0;
$json_entire = ['days_voted'=>[]];

// Get submitted count
$submitted_list = ['breakfast'=>[] , 'lunch'=>[] , 'dinner'=>[]];
foreach(["breakfast","lunch","dinner"] as $table_name) {
    $list = get_affinities_from_post("menu_list_".$table_name , $table_name , $_POST['requested_day']);
    $newlist = [];
    // parse only submitted data
    foreach($list as $menus) {
        if($menus[1] != null) {
            $submitted_count++;
            $newlist[] = $menus;
        }
    }
    $submitted_list[$table_name] = $newlist;
}

if($submitted_count == 0) {
    echo "<script>alert(\"설문을 하지 않으셨습니다!\"); history.back();</script>";    
    exit();
}

// Get json data from the database
$json_entire = json_decode($_COOKIE['survey_info'] , true);
$day_info = null;
foreach($json_entire['days_voted'] as $one_day) {
    if($one_day['date'] == $_POST['requested_day']) {
        $day_info = $one_day;
    }
}

// create the json data
$menu_voted = [];
foreach(["breakfast" , "lunch" , "dinner"] as $meal) {
    foreach($submitted_list[$meal] as $one_menu) {
        $one_menu_voted = [];
        $one_menu_voted['meal'] = $meal;
        $one_menu_voted['id'] = $one_menu[0];
        $one_menu_voted['affinity'] = $one_menu[1];

        $menu_voted[] = $one_menu_voted;
    }
}
// var_dump($menu_voted);

if($day_info == null) {
    // add date, this is a new date
    $json_entire['days_voted'][] = ['date'=>$_POST['requested_day'] , 'menu_voted'=>$menu_voted];
}
else {
    // it's being modified
    // echo "<br>data modified";
    for($i = 0; $i < count($json_entire['days_voted']); $i++) {
        if($json_entire['days_voted'][$i]['date'] == $_POST['requested_day']) {
            break;
        }
    }
    // echo "<br>==================<br>";

    // modify number of votes of menu
    /*
    // remove user part
    user_increment_vote("total_vote" , $_SESSION['username'] , -count($json_entire['days_voted'][$i]['menu_voted']));
    foreach($json_entire['days_voted'][$i]['menu_voted'] as $one_voted_menu) {
        user_increment_vote($one_voted_menu['affinity']."_vote" , $_SESSION['username'] , -1);
    }
    */

    // discard previously voted info
    foreach($json_entire['days_voted'][$i]['menu_voted'] as $one_voted_menu) {
        increment_vote_by_id($_POST['requested_day'] , "menu_list_".$one_voted_menu['meal'] , $one_voted_menu['id'] , "total_vote" , -1);
        increment_vote_by_id($_POST['requested_day'] , "menu_list_".$one_voted_menu['meal'] , $one_voted_menu['id'] , $one_voted_menu['affinity']."_vote" , -1);
    }
    $json_entire['days_voted'][$i]['menu_voted'] = $menu_voted;
    // echo "<br>==================<br>";
    // var_dump($menu_voted);
    // echo "<br>==================<br>";
}

foreach($menu_voted as $one_voted_menu) {
    increment_vote_by_id($_POST['requested_day'] , "menu_list_".$one_voted_menu['meal'] , $one_voted_menu['id'] , "total_vote" , 1);
    increment_vote_by_id($_POST['requested_day'] , "menu_list_".$one_voted_menu['meal'] , $one_voted_menu['id'] , $one_voted_menu['affinity']."_vote" , 1);
}
// modify number of votes of menu
// remove user part
/*
user_increment_vote("total_vote" , $_SESSION['username'] , count($menu_voted));
// modify number of votes of user
foreach($menu_voted as $one_voted_menu) {
    user_increment_vote($one_voted_menu['affinity']."_vote" , $_SESSION['username'] , 1);
}
*/

// apply changes
var_dump($json_entire);
$json_updated = json_encode($json_entire , JSON_UNESCAPED_UNICODE);
// echo $json_updated;
// Update database(change json data)
/*
$connect = connect_server();
$sql_req = "UPDATE user_list SET survey_info='".$json_updated."' WHERE id='".$_SESSION['username']."';";
$result = mysqli_query($connect , $sql_req);
mysqli_close($connect);
*/
//
rewrite_survey_data($_COOKIE['unique_id'] , $json_updated);

echo "<script>
location.href = \"index.php?day_selector=".$_POST['requested_day']."\";
</script>";
?>