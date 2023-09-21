<p style="margin:0;font-weight:bold;font-size:30px;text-align:center;">설문 날짜 수정</p>
<p>*설문 날짜 수정 : 학생들에게 설문을 받을 날짜를 바꿉니다. 메인 페이지에 나오는 날짜를 몇일에서 몇일까지 설문을 제공할 수 있는지를 조정할 수 있습니다.</p>

<?php

$connect = connect_server();
$sql_req = "SELECT * FROM day_selector";
$result = mysqli_query($connect , $sql_req);
$row = mysqli_fetch_assoc($result);
$start_day = $row['start_day'];
$end_day = $row['end_day'];
mysqli_close($connect);

?>

<form method="POST" action="modify_db.php?req=6">
    <div style="display:flex;flex-direction:row;align-items:center;">
        <label for="menu_date">
            <p style="font-size: 15px;">설문을 받을 날짜 :
                <input name="new_survey_start_day" type="date" value="<?php echo $start_day ?>"/>
                ~
                <input name="new_survey_end_day" type="date" value="<?php echo $end_day ?>"/>
            </p>
        </label>
        <input type="submit" value="수정" style="margin:10px;padding:3px;display:inline-block;"/>
    </div>
</form>
