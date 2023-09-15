<p style="margin:0;font-weight:bold;font-size:30px;text-align:center;">통계</p>

<?php
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
    $day = date("Y-m-d");
    if(isset($_GET['stat_date'])) {
        $day = $_GET['stat_date'];
    }
?>

<p style="font-size: 15px;">전체 통계</p>

<!-- print progress bar -->
<?php 
if($total_vote == 0) {
    echo "투표한 데이터가 없습니다!";
    ?>
<?php
}
else {
?>
<div class="progressbar_outer">
    <div id="progbar1" class="progressbar_mid">
        <div class="progressbar_inner_left" style="background-color:#8AA8CE;width:<?php echo $good_p ?>%;">
            좋음(<?php echo round($good_p) ?>%)
        </div>
        <div class="progressbar_inner_mid" style="background-color:#5A8DCF;width:<?php echo $middle_p ?>%;">
            보통(<?php echo round($middle_p) ?>%)
        </div>
        <div class="progressbar_inner_right" style="background-color:#2670CF;width:<?php echo $bad_p ?>%;">
            싫음(<?php echo (int)$bad_p ?>%)
        </div>
    </div>
</div>
<?php
}
?>

<?php echo '<p style="font-size:10px;margin:0;padding:0px;">투표수:총 '.$total_vote.'회,좋음:'.$good_vote.',보통:'.$middle_vote.',싫음:'.$bad_vote.'</p>'; ?>
<!-- just move it -->
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