<?php
session_start(); // session : only for admin login

include('./php/server_communication.php');
include('./php/index_element_manage.php');
include('./php/process_submit.php');
include('./php/user_data.php');

$survey_deadline = get_survey_deadline();
$survey_deadline = str_replace("T"," ",$survey_deadline);
$survey_deadline_time = strtotime($survey_deadline);
$current_time = strtotime(date("Y-m-d H:i:s"));

if($survey_deadline_time <= $current_time) {
    include('./closed.php');
    exit();
}

if(!isset($_SESSION['connected'])) {
    increment_connect();
    $_SESSION['connected'] = 1;
}

if(!isset($_COOKIE['unique_id'])) {
    echo "Please wait..";
    $new_id = assign_new_id();
    setcookie('unique_id' , $new_id , time()+(10*365*60*60));
    insert_user_db($new_id);
    header("Refresh:0");
}

function check_cookie() {
    return isset($_COOKIE['unique_id']);
}

update_removed(); // error!!!

// Get info of the survey days
$days = get_survey_target_date();
if($days == null) {
    echo "Failed getting survey date. Please ask to the administrator. ianisnumber@gmail.com";
    exit();
}
$start_day = $days[0];
$end_day = $days[1];

// Get list of affinity, of one week of the past data
function get_affinity_list($from , $to) { // yeah figure that out
    $affinity_list = [];
    if(!check_cookie()) {
        return null;
    }
    for($day = strtotime($from); $day != strtotime("+1 day" , strtotime($to)); $day = strtotime("+1 day" , $day)) {
        $date_value = date("Y-m-d" , $day);
        $affinities = get_affinities_from_db($date_value);
        $affinity_list[] = [$date_value , $affinities];
    }
    return $affinity_list;
}

?>

<html>
    <a name="top"></a>
    <meta charset="utf-8"/>
    <head>
        <title>savemate</title>
        <link rel="stylesheet" type="text/css" href="css/main.css"></style>
        <link rel="stylesheet" type="text/css" href="css/progbar.css"></style>
        <script src="script/index_acts.js"></script>
        <script src="script/progbar.js"></script>
    </head>
    <body>
        <a href="index.php">
            <img src="img/logo.png" style="width:150px;position:absolute;top:0px;left:0px;">
        </a>
        <div id="top">
            <div id="today_show" style="float: center;">
                <?php
                    echo date("Y. m. d." , strtotime($start_day));
                ?>
            </div>
            <div id="deadline_show" style="float: center;"></div>
            <script>start_clock('<?php echo str_replace("-","/",$survey_deadline).":00"; ?>');</script>
                <?php
                if(isset($_SESSION['username'])) {
                    print_my_info();
                }
                else {
                    print_admin_login();
                }
                if(!isset($_GET['day_selector'])) {
                    $date_value = $start_day;
                }
                else {
                    $date_value = $_GET['day_selector'];
                }
                print_special_menu($date_value);
                
                $total_menu_count = 0;
                $total_voted_count = 0;

                for($one_day = strtotime($start_day); $one_day != strtotime("+1 day" , strtotime($end_day)); $one_day = strtotime("+1 day" , $one_day)) {
                    $total_menu_count += get_menu_count(date("Y-m-d" , $one_day));
                    $total_voted_count += count(get_affinities_from_db(date("Y-m-d" , $one_day)));
                }
                $menu_count = get_menu_count($date_value);
                $affinities = get_affinities_from_db($date_value);
                $voted_count = count($affinities);
                // show the percentage of survey
                if($menu_count != 0) {
                    echo '
                    <div style="display: flex; justify-content: space-between;">
                        <span>하루 설문 진행도</span>
                        <span><?php echo $voted_count."/".$menu_count; ?></span>
                    </div>
                    
                    <div id="prog_bar_body_1" class="progressbar_outer">
                        <div id="prog_bar_1" class="progressbar_inner">0%</div>
                    </div>
                    <br>
                    ';
                }
                if($total_menu_count != 0) {
                    echo '
                    <div style="display: flex; justify-content: space-between;">
                        <span>전체 설문 진행도</span>
                        <span><?php echo $voted_count."/".$menu_count; ?></span>    
                    </div>
                    
                    <div id="prog_bar_body_2" class="progressbar_outer">
                        <div id="prog_bar_2" class="progressbar_inner">0%</div>
                    </div>
                    ';
                }
                $percentage = 0;
                $total_percentage = 0;
                if($menu_count != 0) {
                    $percentage = (($voted_count/$menu_count)*100);
                }
                if($total_menu_count != 0) {
                    $total_percentage = (($total_voted_count/$total_menu_count)*100);
                }
                ?>
            </div>
        </div>
        <?php echo "<script>var percentage_local = ".$percentage."</script>"; ?>
        <?php echo "<script>var percentage_total = ".$total_percentage."</script>"; ?>
        <script> start_progressbar("progressbar_inner" , [percentage_local , percentage_total]); </script>
        <br>
        <p>요일을 눌러 다른 날짜의 메뉴를 설문할 수 있습니다!</p>
        <form method="GET" action="./index.php">
            <div id="days_count">
            <?php
            $week_list = ["일","월","화","수","목","금","토"];
            // Print the day selector
            for($one_day = strtotime($start_day); $one_day != strtotime("+1 day" , strtotime($end_day)); $one_day = strtotime("+1 day" , $one_day)) {
                $month = date("m" , $one_day);
                $day = date("d" , $one_day);
                $week = date("w" , $one_day);
                $date_value = date("Y-m-d" , $one_day);
                $did = "";
                $affinities = get_affinities_from_db($date_value);
                $menu_count = get_menu_count($date_value);
                // Compare the number of submission and total menus
                // If they are same -> done submitting
                if(count($affinities) != $menu_count) {
                    $did = "not_done";
                }
                $total_did_votes += count($affinities);
                $total_required_votes += $menu_count;
                $classes = "def week ".$did;
                echo "<div class=\"current_day\"><button class=\"".$classes."\" name=\"day_selector\" type=\"submit\" value=\"".$date_value."\" ?>".$week_list[$week]."</button><div class=\"def\">".$month."/".$day."</div></div>";
            }
            ?>
            </div>
        </form>

        <hr style="border: none; color: #000000; background-color: #000000; height: 5px;">
        <div style="padding: 10px; font-size: 30px; font-weight: bold;">
            <?php
            // Print name of the selected week
            $week_list = ["일요일","월요일","화요일","수요일","목요일","금요일","토요일"];
            $month = (int)date("m" , strtotime($start_day));
            $day = (int)date("d" , strtotime($start_day));
            $week = (int)date("w" , strtotime($start_day));
            if($_GET['day_selector'] != "") {
                $month = (int)date("m" , strtotime($_GET['day_selector']));
                $day = (int)date("d" , strtotime($_GET['day_selector']));
                $week = (int)date("w" , strtotime($_GET['day_selector']));
            }
            echo $week_list[$week];
            ?>
        </div>
        <?php 
            if(!isset($_GET['day_selector'])) {
                $date_value = $start_day;
            }
            else {
                $date_value = $_GET['day_selector'];
            }
            $menu_count = get_menu_count($date_value);
            $voted_count = count(get_affinities_from_db($date_value));
            if($voted_count == $menu_count && $menu_count != 0) {
                echo "설문이 완료되었습니다.";
            }
        ?>
        <script>
        const radio_prev_map = new Map();
        function set_radio(radio,init) {
            radio_prev_map.set(radio,init);
        }
        function handle_radios(radio) {/*
            if(radio.value == radio_prev_map.get(radio)) {
                radio.checked = false;
                alert("same,"+radio_prev_map.get(radio)+"->"+radio.value);
                radio_prev_map.set(radio , "");
            }
            else {
                alert("not same,"+radio_prev_map.get(radio)+"->"+radio.value);
                radio_prev_map.set(radio , radio.value);
            }*/ // just forget about it
        }
        </script>
        <form method="POST" action="submit.php">
            <div id="days_show">
                <?php
                $meals = [["menu_list_breakfast","아침","breakfast"] , ["menu_list_lunch","점심","lunch"] , ["menu_list_dinner","저녁","dinner"]];
                foreach($meals as $meal_name_n_db) {
                    echo "<div class=\"one_eating\">
                    <p style=\"font-size:23px;\"><h3>".$meal_name_n_db[1]."</h3></p>";
                    $requested_day = $_GET['day_selector'];

                    if(!isset($_GET['day_selector'])) {
                        $requested_day = $start_day;
                    }
                    echo "<input type=\"hidden\" name=\"requested_day\" value=\"".$requested_day."\">";
                    // print menus and the radios
                    $affinity_list = get_affinity_list($start_day , $end_day);
                    print_menus($meal_name_n_db[0],$meal_name_n_db[2],$requested_day,$affinity_list,$menu_count == $voted_count);
                    echo "</div>";
                }
                ?>
            </div>
            <?php
            if(($menu_count != $voted_count)) {
                if($menu_count != 0) {
                   echo "<div id=\"submit_final\"><button class=\"button\" type=\"submit\">설문 보내기</button></div>";
                }
            }
            ?>
        </form>
    </body>
</html>