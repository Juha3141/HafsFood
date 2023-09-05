<?php
session_start();

include('./php/server_communication.php');
include('./php/index_element_manage.php');
include('./php/process_submit.php');

// Get list of affinity, of one week of the past data
function get_affinity_list() {
    $affinity_list = [];
    if(!isset($_SESSION['username'])) {
        return null;
    }
    for($i = -6; $i <= 0; $i++) {
        $day = date("d" , strtotime("+".$i." day"));
        $week = date("w" , strtotime("+".$i." day"));
        
        $date_value = date("Y-m-d" , strtotime("+".$i." day"));
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
        <title>HAFS 급식 선호도 조사</title>
        <link rel="stylesheet" type="text/css" href="css/main.css"></style>
        <script src="script/index_acts.js"></script>
    </head>
    <body>
        <div id="top">
            <div id="today_show" style="float: center;"></div>
                <?php
                if(!isset($_SESSION['username'])) {
                    print_login_button();
                }
                else {
                    print_my_info();
                }
                print_special_menu();
                
                if(isset($_SESSION['username']) && $_SESSION['account_type'] == "user") {
                    $total_menu_count = 0;
                    $total_voted_count = 0;
                    for($i = -6; $i <= 0; $i++) {
                        $date_value = date("Y-m-d" , strtotime("+".$i." day"));
                        
                        $total_menu_count += get_menu_count($date_value);
                        $total_voted_count += count(get_affinities_from_db($date_value));
                    }

                    if(!isset($_GET['day_selector'])) {
                        $date_value = date("Y-m-d" , strtotime("0 day"));
                    }
                    else {
                        $date_value = $_GET['day_selector'];
                    }
                    $menu_count = get_menu_count($date_value);
                    $affinities = get_affinities_from_db($date_value);
                    $voted_count = count($affinities);
                    // show the percentage of survey
                    $percentage = (($voted_count/$menu_count)*100);
                    $total_percentage = (($total_voted_count/$total_menu_count)*100);
                    echo '
                    <div style="display: flex; justify-content: space-between;">
                        <span>하루 설문 진행도</span>
                        <span><?php echo $voted_count."/".$menu_count; ?></span>
                    </div>
                    
                    <div id="prog_bar_body_1" class="progressbar_outer">
                        <div id="prog_bar_1" class="progressbar_inner">0%</div>
                    </div>
                    <br>
                    
                    <div style="display: flex; justify-content: space-between;">
                        <span>전체 설문 진행도</span>
                        <span><?php echo $voted_count."/".$menu_count; ?></span>    
                    </div>
                    
                    <div id="prog_bar_body_2" class="progressbar_outer">
                        <div id="prog_bar_2" class="progressbar_inner">0%</div>
                    </div>
                    ';
                }
                ?>
            </div>
        </div>
        <?php echo "<script>var percentage_local = ".$percentage."</script>"; ?>
        <?php echo "<script>var percentage_total = ".$total_percentage."</script>"; ?>
        <script>
            function start_progressbar() {
                var prog_bars = document.getElementsByClassName("progressbar_inner");
                let id_list = [];
                let width = [0 , 0];
                let vel = [1.6 , 1.6];
                let acc = [-0.001 , -0.001];
                let percentages = [percentage_local , percentage_total];
                for(var i = 0; i < prog_bars.length; i++) {
                    prog_bars.item(i).style.width = "0%";
                    var id = setInterval(move_progbar, 5 , prog_bars.item(i) , i , percentages[i]);
                    id_list = id_list.push(id);
                }
                function move_progbar(prog_bar , i , percentage) {
                    if(width[i] >= percentage) {
                        prog_bar.style.width = Math.trunc(percentage)+"%";
                        prog_bar.innerHTML = Math.trunc(percentage)+"%";
                        clearInterval(id_list[i]);
                    }
                    else {
                        prog_bar.style.width = Math.trunc(width[i])+"%";
                        prog_bar.innerHTML = Math.trunc(width[i])+"%";
                        width[i] += vel[i];
                        vel[i] += acc[i];
                    }
                }
            }
            start_progressbar();
        </script>
        <br>
        <script>
            update_date();
        </script>

        <form method="GET" action="./index.php">
            <div id="days_count">
            <?php
            $week_list = ["일","월","화","수","목","금","토"];
            // Print the day selector
            for($i = -6; $i <= 0; $i++) {
                $day = date("d" , strtotime("+".$i." day"));
                $week = date("w" , strtotime("+".$i." day"));
                $date_value = date("Y-m-d" , strtotime("+".$i." day"));
                $did = "";
                if(isset($_SESSION['username']) && $_SESSION['account_type'] == "user") {
                    $affinities = get_affinities_from_db($date_value);
                    $menu_count = get_menu_count($date_value);
                    // Compare the number of submission and total menus
                    // If they are same -> done submitting
                    if(count($affinities) != $menu_count) {
                        $did = "not_done";
                    }
                    $total_did_votes += count($affinities);
                    $total_required_votes += $menu_count;
                }
                $classes = "def week ".$did;
                if($i == 0) {
                    $classes .= " today";
                }
                echo "<div class=\"current_day\"><button class=\"".$classes."\" name=\"day_selector\" type=\"submit\" value=\"".$date_value."\" ?>".$week_list[$week]."</button><div class=\"def\">".$day."</div></div>";
            }
            ?>
            </div> 
        </form>

        <hr style="border: none; color: #000000; background-color: #000000; height: 5px;">
        <div style="padding: 10px; font-size: 30px; font-weight: bold;">
            <?php
            // Print name of the selected week
            $week_list = ["일요일","월요일","화요일","수요일","목요일","금요일","토요일"];
            $week = date("w" , strtotime("0 day"));
            if($_GET['day_selector'] != "") {
                $week = date("w" , strtotime($_GET['day_selector']));
            }
            echo $week_list[$week];
            ?>
        </div>
        <form method="POST" action="submit.php">
            <div id="days_show">
                <?php
                $meals = [["menu_list_breakfast","아침","breakfast"] , ["menu_list_lunch","점심","lunch"] , ["menu_list_dinner","저녁","dinner"]];
                foreach($meals as $meal_name_n_db) {
                    echo "<div class=\"one_eating\">
                    <p style=\"font-size:23px;\"><h3>".$meal_name_n_db[1]."</h3></p>";
                    $requested_day = $_GET['day_selector'];

                    if(!isset($_GET['day_selector'])) {
                        $requested_day = date("Y-m-d");
                    }
                    echo "<input type=\"hidden\" name=\"requested_day\" value=\"".$requested_day."\">";
                    // print menus and the radios 
                    $affinity_list = get_affinity_list();
                    print_menus($meal_name_n_db[0],$meal_name_n_db[2],$requested_day,$affinity_list);
                    echo "</div>";
                }
                ?>
            </div>
            <?php
            if(isset($_SESSION['username']) && $_SESSION['account_type'] == "user") {
                echo "<div id=\"submit_final\"><button class=\"button\" type=\"submit\">설문 보내기</button></div>";
            }
            ?>
        </form>
    </body>
</html>