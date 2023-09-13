<?php

function get_parsed_span($span) {
    $span_string = (string)$span;
    $span_string = str_replace("<span>" , "" , $span_string);
    $span_string = str_replace("</span>" , "" , $span_string);
    return explode("<br />  " , $span_string);
}

function get_menu_list_hafs($date) {
    $html = file_get_html('http://hafs.hs.kr/?act=lunch.main2&month='.str_replace("-" , "." , $date));
    $menu_list = ['date'=>$date];
    // template : ["breakfast"=>["date"=>"date","names"=>[]] , ]
    $meal_name = ["breakfast","lunch","dinner"];
    for($i = 0; $i < 3; $i++) {
        $div_name = ["morning","lunch","dinner"][$i];
        $meal_div = $html->find("div[id=$div_name]");
        if(!$meal_div) {
            return [];
        }
        $span_list = $meal_div[0]->find("div[class=objContent]")[0]->find("div")[0]->find("span");
        foreach($span_list as $span) {
            $menu_list[$meal_name[$i]] = get_parsed_span($span);
        }
    }
    return $menu_list;
}

// Automatically add menu to system
function add_menu_auto_hafs($date) {
    $connect = connect_server();
    $menu_list = get_menu_list_hafs($date);
    if($menu_list == []) {
        return;
    }
    foreach(["breakfast","lunch","dinner"] as $meal) {
        foreach($menu_list[$meal] as $meal_name) {
            $sql_req = "INSERT INTO menu_list_".$meal." (date,name) VALUE(\"".$date."\" , \"".$meal_name."\");";
            if(!mysqli_query($connect , $sql_req)) {
                break;
            }
        }
    }
    mysqli_close($connect);
}

?>