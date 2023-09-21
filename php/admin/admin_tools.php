<?php

function get_total_vote($col) {
    $connect = connect_server();
    $total_value = 0;
    foreach(["breakfast","lunch","dinner"] as $table) {
        $sql_req = "SELECT SUM($col) FROM menu_list_$table";
        $result = mysqli_query($connect , $sql_req);
        $total_value += mysqli_fetch_assoc($result)['SUM('.$col.')'];
    }
    mysqli_close($connect);
    return $total_value;
}

function print_progbar_n($id , $count , $innerhtmls , $colors , $percentages) {
    $classes = ["progressbar_inner_left" , "progressbar_inner_mid" , "progressbar_inner_right"];
    echo '<div class="progressbar_outer"><div id='.$id.' class="progressbar_mid">';
    for($i = 0; $i < $count; $i++) {
        if($percentages[$i] == 0) {
            array_splice($percentages , $i , 1);
            array_splice($innerhtmls , $i , 1);
            array_splice($colors , $i , 1);
            $count--;
            $i--;
        }
    }

    for($i = 0; $i < $count; $i++) {
        if($i == 0) $class = $classes[0];
        else if($i == $count-1) $class = $classes[2];
        else $class = $classes[1];
        if($count == 1) {
            $class = "progressbar_inner_both";
        }
        
        echo
        '<div class="'.$class.'" style="background-color:'.$colors[$i].';width:'.$percentages[$i].'%;">
        <span style="font-size:12px;padding:0px;">'.$innerhtmls[$i].'</span>
        </div>';
    }

    echo '</div></div>';
    return;
}

function get_menu_list($date) {
    $connect = connect_server();
    $table_name = ["breakfast" , "lunch" , "dinner"];
    
    $data = ["breakfast"=>[] , "lunch"=>[] , "dinner"=>[]];
    
    foreach($table_name as $table) {
        $sql_req = "SELECT * FROM menu_list_".$table." WHERE date=\"".$date."\";";
        $result = mysqli_query($connect , $sql_req);
        while($row = mysqli_fetch_assoc($result)) {
            unset($row['date']);
            $data[$table][] = $row;
        }
    }
    mysqli_close($connect);
    return $data;
}

function is_menu_exist($date,$meal,$name) {
    $connect = connect_server();
    $sql_req = "SELECT * FROM menu_list_".$meal." WHERE date=\"".$date."\" AND $name=\"".$name."\";";
    $result = mysqli_query($connect , $sql_req);
    $row = mysqli_fetch_assoc($result);
    mysqli_close($connect);
    return ($row != []);
}

function get_special_menu($month , $year) {
    $connect = connect_server();        
    $sql_req = "SELECT * FROM special_food WHERE month=".$month." AND year=".$year.";";
    $result = mysqli_query($connect , $sql_req);
    if($result == null) {
        mysqli_close($connect);
        return;
    }
    // search from ID
    $menuname = mysqli_fetch_assoc($result)['name'];
    mysqli_close($connect);
    return $menuname;
}

function get_voted_count_day($year,$month,$date) {
    $connect = connect_server();
    $total_vote = 0;
    $table_name = ["breakfast" , "lunch" , "dinner"];
    foreach($table_name as $table) {
        $sql_req = "SELECT SUM(total_vote) FROM menu_list_$table WHERE date=\"$year-$month-$date\";";
        $result = mysqli_query($connect , $sql_req);
        if(!$result) continue;
        $row = mysqli_fetch_assoc($result);
        if($row == null) continue;
        $total_vote += $row['SUM(total_vote)'];
    }

    mysqli_close($connect);
    return $total_vote;
}

?>