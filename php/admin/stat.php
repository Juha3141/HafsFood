<p style="margin:0;font-weight:bold;font-size:30px;text-align:center;">통계</p>

<?php

include_once('./php/server_communication.php');

$total_vote = get_total_vote("total_vote");
$good_vote = get_total_vote("good_vote");
$middle_vote = get_total_vote("middle_vote");
$bad_vote = get_total_vote("bad_vote");
if($total_vote == 0) {
    $good_p = 0;
    $middle_p = 0;
    $bad_p = 0;
}
else {
    $good_p = $good_vote/$total_vote*100;
    $middle_p = $middle_vote/$total_vote*100;
    $bad_p = $bad_vote/$total_vote*100;
}
echo "<script> var total=$total_vote; </script>";
echo "<script> var good_p=$good_p; </script>";
echo "<script> var mid_p=$middle_p; </script>";
echo "<script> var bad_p=$bad_p; </script>";
// get visited counts
$day_count = [0,31,28,31,30,31,30,31,31,30,31,30,31];
$cur_day_count = $day_count[(int)date("m")];

$connect = connect_server();
$sql_req = "SELECT day,num FROM connected_number WHERE year=".(int)date("Y")." AND month=".(int)date("m").";";
$month_visits = [];
for($d = 1; $d <= $cur_day_count; $d++) {
    $month_visits[$d] = 0;
}
$result = mysqli_query($connect , $sql_req);
if($result) {
    while($row = mysqli_fetch_assoc($result)) {
        $month_visits[(int)$row['day']] = (int)$row['num'];
    }
}
mysqli_close($connect);

$survey_target = get_survey_target_date();
$day = $survey_target[0];
if(isset($_GET['stat_date'])) {
    $day = $_GET['stat_date'];
}

?>

<p style="font-size: 15px;">전체 통계</p>

<!-- print progress bar -->
<?php 
if($total_vote == 0) {
    echo "투표한 데이터가 없습니다!";
}
else {
    print_progbar_n("progbar1" , "3" , ["좋음(".round($good_p)."%)" , "보통(".round($middle_p)."%)" , "싫음(".round($bad_p)."%)"] , ["#8AA8CE","#5A8DCF","#2670CF"] , [$good_p , $middle_p , $bad_p]);
}
?>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/series-label.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>

<?php echo '<p style="font-size:10px;margin:0;padding:0px;">투표수:총 '.$total_vote.'회,좋음:'.$good_vote.',보통:'.$middle_vote.',싫음:'.$bad_vote.'</p>'; ?>
<!-- just move it -->
<br>
<div style="display:flex;flex-direction:row">
<div id="graph-container-visit" style="width:50%;height:10%;border-radius:5px;margin-right:10px;margin-left:10px;"></div>
<div id="graph-container-stat" style="width:50%;height:10%;border-radius:5px;margin-right:10px;margin-left:10px;"></div>
</div>

<script>
    Highcharts.chart('graph-container-visit', {
        chart:{ type:'line' },
        title:{ text:'이번달 방문자수' },
        xAxis:{ title:{ text:'날짜' } , categories:[<?php
    for($d=1;$d<=$cur_day_count;$d++) {
        echo "'$d'";
        if($d!=$cur_day_count) echo ",";
    }
        ?>]},
        yAxis:[{ title:{ text:'방문자수' } }],
        legend:{ layout:'vertical',align:'right',verticalAlign:'middle' },
        series:[{name:'방문자수',data:[<?php
        for($d=1;$d<=$cur_day_count;$d++) {
            echo $month_visits[$d];
            if($d!=$cur_day_count) echo ",";
        }?>]}]
    });
    Highcharts.chart('graph-container-stat', {
        chart:{ type:'line' },
        title:{ text:'설문 투표수(<?php 
        $month1 = date("m월 d일" , strtotime($survey_target[0]));
        $month2 = date("m월 d일" , strtotime($survey_target[1]));
        echo $month1."~".$month2;
        ?>)' },
        xAxis:{ title:{ text:'날짜' } , categories:[<?php
    for($d=strtotime($survey_target[0]);$d!=strtotime("+1 day",strtotime($survey_target[1]));$d=strtotime("+1 day",$d)) {
        echo date("d",$d);
        echo ",";
    }
        ?>]},
        yAxis:[{ title:{ text:'투표수' } },],
        legend:{ layout:'vertical',align:'right',verticalAlign:'middle' },
        series:[{ name:'투표수',data:[<?php
    for($d=strtotime($survey_target[0]);$d!=strtotime("+1 day",strtotime($survey_target[1]));$d=strtotime("+1 day",$d)) {
        $total = get_voted_count_day(date("Y",$d),date("m",$d),date("d",$d));/*
        $mcount = get_menu_count(date("Y-m-d" , $d));
        $ppl = 0;
        if($mcount != 0) $ppl = round($total/$mcount);*/
        echo $total;
        echo ",";
    }?>]}]
    });
</script>

<form method="GET" action="admin.php#statistics">
    <div style="display:flex;flex-direction:row;align-items:center;">
        <label for="stat_date">
            <p style="font-size: 15px;">조회할 날짜 :
                <input name="stat_date" type="date" value="<?php echo $day ?>"/>
            </p>
        </label>
        <input type="submit" value="조회" style="margin:10px;padding:3px;display:inline-block;"/>
    </div>
</form>
<div class="viewer">
    <div style="display:flex;flex-direction:row;">
        <?php 
        $menu_data = get_menu_list($day);
        $meal_index = 0;
        foreach($menu_data as $one_meal) {
        ?>
        <span class="one_seg_show">
            <?php
            $meal_to_kr = ["아침","점심","저녁"];
            // print meal name
            echo '<p style="font-size:25px;margin:0;padding:5px;">'.$meal_to_kr[$meal_index++].'</p>';
            
            // 
            if($one_meal == []) {
                echo '데이터베이스에 등록되지 않음';
            }
            foreach($one_meal as $one_menu) {
                echo '<p style="font-size:20px;margin:0;padding:5px;">'.$one_menu['name'].'</p>';
                if($one_menu['total_vote']) {
                    $total_vote = $one_menu['total_vote'];
                    if($total_vote == 0) {
                        echo "투표한 데이터가 없습니다!";
                    }
                    else {
                        $good_p = $one_menu['good_vote']/$total_vote*100;
                        $middle_p = $one_menu['middle_vote']/$total_vote*100;
                        $bad_p = $one_menu['bad_vote']/$total_vote*100;
                    }
                    print_progbar_n("progbar1" , 3 , 
                    ["좋음(".round($good_p)."%)","보통(".round($middle_p)."%)","싫음(".round($bad_p)."%)"] , 
                    ["#8AA8CE","#5A8DCF","#2670CF"] , 
                    [round($good_p),round($middle_p),round($bad_p)]);
                }
                else {
                    $total_vote = 0;
                    echo '<div class="progressbar_outer"><div id='.$id.' class="progressbar_mid">';
                    echo '<p style="padding:10px;margin:0; font-size:12px;">(데이터 없음)</p>';
                    echo '</div></div>';
                }
                echo '<p style="font-size:10px;margin:0;padding:0px;">투표수:총 '.$total_vote.'회,좋음:'.$one_menu['good_vote'].',보통:'.$one_menu['middle_vote'].',싫음:'.$one_menu['bad_vote'].'</p>';
            }
            ?>
        </span>
        <?php
        }
        ?>
    </div>
</div>