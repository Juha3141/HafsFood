<?php
/*table_name : menu_list_*, meal_name : 아침, 점심, 저녁*/

session_start();

function print_admin_login() {
    echo "
    <a href=\"login_admin.php\" id=\"login_btn\" class=\"href\">관리자 로그인</a>
    ";
}

function print_my_info() {
    echo "
    <a href=\"logout.php\" id=\"logout_btn\" class=\"href\">로그아웃</a>
    <a href=\"admin.php\" id=\"info_btn\" class=\"href\">관리자 페이지</a>
    ";
}

// disclaimer : menu_name is number
function print_survey($menu_name , $meal_name , $initial_value , $is_unwritable , $k) {
    echo "<div style=\"display:flex;justify-content:center;\">";
    $text = ["좋음" , "보통" , "싫음"];
    $value = ["good" , "middle" , "bad"];
    $menu_name = str_replace(' ' , '-' , $menu_name);
    for($i = 0; $i < 3; $i++) {
        $checked = "";
        $readonly = "";
        if($value[$i] == $initial_value) {
            $checked = " checked";
        }
        if($is_unwritable) {
            $readonly = " onclick=\"return false;\"";
        }
        else {
            $readonly = " onclick=\"handle_radios(this);\"";
        }
        echo "<div style=\"padding:10px;display:flex;align-items:center;\">
            <label>".$text[$i]."</label>
            <input id=\"radio_".$meal_name."_$k\" class=\"radio\" name=\"affinity_".$menu_name."_".$meal_name."\" type=\"radio\" value=\"".$value[$i]."\"".$checked."".$readonly."/>
            <script>set_radio(document.getElementById(\"radio_".$meal_name."_$k\"),$initial_value);</script>
            </div>";
        $k++;
    }
    echo "</div>";
    echo "<br>";
    return $k;
}

function get_menu_count($date) {
    $date = block_sql_injection($date);
    $menu_tables = ["menu_list_breakfast" , "menu_list_lunch" , "menu_list_dinner"];
    $connect = connect_server();

    $menu_count = 0;
    foreach($menu_tables as $table) {
        $sql_req = "SELECT COUNT(*) FROM ".$table." WHERE date=\"".$date."\";";
        $result = mysqli_query($connect , $sql_req);
        $menu_count += mysqli_fetch_assoc($result)['COUNT(*)'];
    }
    mysqli_close($connect);
    return $menu_count;
}

function get_menus($table_name , $requested_day) {
    $connect = connect_server();
    $sql_req = "SELECT * FROM ".$table_name;
    $result = mysqli_query($connect , $sql_req);
    $valid_results = [];

    while($row = mysqli_fetch_assoc($result)) {
        if($row['date'] == $requested_day) {
            $valid_results[] = $row;
        }
    }
    mysqli_close($connect);
    return $valid_results;
}

function print_menus($table_name , $meal_name , $requested_day , $affinity_list , $is_unwritable) {
    $today_affinities = null;
    $meal_affinities = [];
    if($affinity_list != null) { 
        foreach($affinity_list as $aff_per_day) {
            // $aff_per_day[0] : day
            if($aff_per_day[0] == $requested_day) {
                $today_affinities = $aff_per_day[1];
            }
        }
        for($i = 0; $i < count($today_affinities); $i++) {
            if($today_affinities[$i]['meal'] == $meal_name) {
                $meal_affinities[] = $today_affinities[$i];
            }
        }
    }
    $connect = connect_server();
    $sql_req = "SELECT * FROM ".$table_name;
    $result = mysqli_query($connect , $sql_req);
    $valid_results = [];
    while($row = mysqli_fetch_assoc($result)) {
        if($row['date'] == $requested_day) {
            $valid_results[] = $row;
        }
    }
    if($valid_results == []) {
        echo "<div class=\"menu_selector\"><br>데이터가 없습니다!<br><br></div>";
        return;
    }
    $k = 0;
    for($i = 0; $i < count($valid_results); $i++) {
        $row = $valid_results[$i];
        echo "<div class=\"menu_selector\"><br><p class=\"menu_name\">".$row['name']."</p>";
        $initial_value = "";
        foreach($meal_affinities as $info) {
            if($info['id'] == $row['id']) { // now use id to discriminate menu
                $initial_value = $info['affinity'];
            }
        }
        $k = print_survey($i , $meal_name , $initial_value , $is_unwritable , $k);
        echo "</div>";
    }
}

function print_special_menu() {
    $connect = connect_server();        
    $sql_req = "SELECT * FROM special_food WHERE month=".(int)date("m").";";
    $result = mysqli_query($connect , $sql_req);
    if($result == null) {
        mysqli_close($connect);
        return;
    }
    // search from ID
    $menuname = mysqli_fetch_assoc($result)['name'];
    $specialmenu_string = (int)date("m")."월 특식 : ".$menuname;

    echo "<div id=\"special_main\" onclick=\"goto_special()\">".$specialmenu_string."</div>";
    mysqli_close($connect);
}

?>