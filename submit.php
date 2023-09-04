<?php 
include('./php/server_communication.php');
include('./php/index_element_manage.php');
include('./php/process_submit.php');

session_start();

echo $_POST['requested_day'];
$menu_count = get_menu_count($_POST['requested_day']);

$submitted_count = 0;
$json_entire = ['days_voted'=>[]];

// Get submitted count
$submitted_list = ['breakfast'=>[] , 'lunch'=>[] , 'dinner'=>[]];
foreach([["breakfast"] , ["lunch"] , ["dinner"]] as $menu_n_db) {
    $list = get_affinities_from_post("menu_list_".$menu_n_db[0] , $menu_n_db[0] , $_POST['requested_day']);
    $newlist = [];
    // parse only submitted data
    foreach($list as $menus) {
        if($menus[1] != null) {
            $submitted_count++;
            $newlist[] = $menus;
        }
    }
    $submitted_list[$menu_n_db[0]] = $newlist;
}

echo "-----------<br>";
var_dump($submitted_list);
echo "<br>";

if($submitted_count == 0) {
    echo "<script>alert(\"설문을 하지 않으셨습니다!\"); history.back();</script>";    
    exit();
}

// Get json data from the database
$data = get_user_data($_SESSION['username']);
$json_entire = json_decode($data['survey_info'] , true);
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
        $one_menu_voted['name'] = $one_menu[0];
        $one_menu_voted['affinity'] = $one_menu[1];

        $menu_voted[] = $one_menu_voted;
    }
}
// var_dump($menu_voted);

if($day_info == null) {
    // add date, this is a new date
    $json_entire['days_voted'][] = ['date'=>$_POST['requested_day'] , 'menu_voted'=>$menu_voted];
    foreach($menu_voted as $one_voted_menu) {
        increment_vote($_POST['requested_day'] , "menu_list_".$one_voted_menu['meal'] , $one_voted_menu['name'] , "total_vote" , 1);
        increment_vote($_POST['requested_day'] , "menu_list_".$one_voted_menu['meal'] , $one_voted_menu['name'] , $one_voted_menu['affinity']."_vote" , 1);
    }
    exit();
}
else {
    // it's being modified
    for($i = 0; $i < count($json_entire['days_voted']); $i++) {
        if($json_entire['days_voted'][$i]['date'] == $_POST['requested_day']) {
            break;
        }
    }
    echo "=======<br>";
    var_dump($menu_voted);
    echo "<br>";
    $json_entire['days_voted'][$i]['menu_voted'] = $menu_voted;
}
/*
$json_updated = json_encode($json_entire , JSON_UNESCAPED_UNICODE);
echo $json_updated;
// Update database(change json data)
$connect = connect_server();
$sql_req = "UPDATE user_list SET survey_info='".$json_updated."' WHERE id='".$_SESSION['username']."';";
$result = mysqli_query($connect , $sql_req);
mysqli_close($connect);

echo "<script>
location.href = \"index.php?day_selector=".$_POST['requested_day']."\";
</script>";
*/
?>