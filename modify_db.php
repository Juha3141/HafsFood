<?php
include('./php/server_communication.php');

function do_modify() {
    $new_value = urldecode(base64_decode($_GET['val'] , false));
    $order = urldecode(base64_decode($_GET['o'] , false));
    if($new_value != block_sql_injection($new_value)
    ||$order != block_sql_injection($order)
    ||$_GET['date'] != block_sql_injection($_GET['date'])
    ||$_GET['meal'] != block_sql_injection($_GET['meal'])) {
        echo "please try again.";
        exit();
    }

    $connect = connect_server();
    $sql_req = "UPDATE menu_list_".$_GET['meal']." SET name=\"".$new_value."\" WHERE date=\"".$_GET['date']."\" AND name=\"".$order."\";";
    $result = mysqli_query($connect , $sql_req);
    mysqli_close($connect);
    if(!$result) {
        echo '<script>alert("error : what the fuck");</script>';
        echo '<script>history.back();</script>';
    }
    echo '<script>alert("변경되었습니다!");</script>';
    echo '<script>history.back();</script>';
}

function do_remove() {
    $order = urldecode(base64_decode($_GET['o'] , false));
    if($order != block_sql_injection($order)
    ||$_GET['date'] != block_sql_injection($_GET['date'])
    ||$_GET['meal'] != block_sql_injection($_GET['meal'])) {
        echo "please try again.";
        exit();
    }

    $connect = connect_server();
    $sql_req = "DELETE FROM menu_list_".$_GET['meal']." WHERE date=\"".$_GET['date']."\" AND name=\"".$order."\";";
    $result = mysqli_query($connect , $sql_req);
    mysqli_close($connect);
    if(!$result) {
        echo '<script>alert("error : what the fuck");</script>';
        echo '<script>history.back();</script>';
    }

    echo '<script>history.back();</script>';
}

function do_create() {
    $new_value = urldecode(base64_decode($_GET['new'] , false));
    if($new_value != block_sql_injection($new_value)
    ||$_GET['date'] != block_sql_injection($_GET['date'])
    ||$_GET['meal'] != block_sql_injection($_GET['meal'])) {
        echo "please try again.";
        exit();
    }

    $connect = connect_server();
    $sql_req = "INSERT INTO menu_list_".$_GET['meal']." (date,name) VALUE(\"".$_GET['date']."\" , \"".$new_value."\");";
    $result = mysqli_query($connect , $sql_req);
    mysqli_close($connect);
    if(!$result) {
        echo '<script>alert("error : what the fuck");</script>';
        echo '<script>history.back();</script>';
    }

    echo '<script>alert("추가되었습니다!");</script>';
    echo '<script>history.back();</script>';
}

function do_special() {
    $new_value = urldecode(base64_decode($_GET['val'] , false));
    if($new_value != block_sql_injection($new_value)
    ||$_GET['mon'] != block_sql_injection($_GET['mon'])) {
        echo "please try again.";
        exit();
    }
    if((int)$_GET['mon'] > 12||(int)$_GET['mon'] < 1) {
        echo "wrong month.";
        exit();
    }
    
    $year = date("Y");
    $connect = connect_server();
    $sql_req = "SELECT name FROM special_food WHERE month=".$_GET['mon'].";";
    $result = mysqli_query($connect , $sql_req);
    if(!mysqli_fetch_assoc($result)) {
        $sql_req = "INSERT INTO special_food (year,month,name) VALUE(".$year.",".$_GET['mon'].",\"".$new_value."\");";
    }
    else {
        $sql_req = "UPDATE special_food SET name=\"".$new_value."\" WHERE month=".$_GET['mon'].";";
    }
    
    mysqli_query($connect , $sql_req);
    mysqli_close($connect);

    echo '<script>history.back();</script>';
}

function do_autoadd() {
    $new_count = 0;
    $queries = [];
    for($i = 0; $i < (int)$_POST['autoadd_item_cnt']; $i++) {
        if(isset($_POST['autoadd_chk_'.$i])) {
            $parsed_post = explode("|" , $_POST['autoadd_chk_'.$i]);
            $queries[] = "INSERT INTO menu_list_".$parsed_post[1]." (date,name) VALUE(\"".$parsed_post[0]."\",\"".$parsed_post[2]."\");";
            echo $queries[$new_count]."<br>";
            $new_count++;
        }
    }
    $connect = connect_server();
    foreach($queries as $sql_req) {
        if(mysqli_query($connect , $sql_req) == false) {
            echo '<script>alert("error : what the fuck");</script>';
            echo '<script>history.back();</script>';
        }
    }
    mysqli_close($connect);
    echo '<script>alert("성공적으로 입력되었습니다!");</script>';
    echo '<script>location.href = "admin.php";</script>';
}

switch($_GET['req']) {
    case 1: do_modify(); break;
    case 2: do_remove(); break;
    case 3: do_create(); break;
    case 4: do_special(); break;
    case 5: do_autoadd(); break;
}

?>