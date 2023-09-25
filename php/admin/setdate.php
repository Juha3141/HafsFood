<p style="margin:0;font-weight:bold;font-size:30px;text-align:center;">설문 날짜 수정/설문 종료날짜 수정</p>
<p>*설문 날짜 수정 : 학생들에게 설문을 받을 날짜를 바꿉니다. 메인 페이지에 나오는 메뉴의 날짜를 몇일에서 몇일까지 설문을 제공할 수 있는지를 조정할 수 있습니다.</p>

<?php

$survey_target = get_survey_target_date();
$deadline = get_survey_deadline();
?>

<form method="POST" action="modify_db.php?req=6">
    <div style="align-items:center;">
        <label for="menu_date">
            <p style="font-size: 15px;margin:0;padding:0px;">설문 날짜 : </p>
        </label>
        <input name="new_survey_start_day" type="date" value="<?php echo $survey_target[0] ?>"/>
        ~
        <input name="new_survey_end_day" type="date" value="<?php echo $survey_target[1] ?>"/>
        <input type="submit" value="수정" style="margin:10px;padding:3px;display:inline-block;"/>
    </div>
</form>

<form method="POST" action="modify_db.php?req=7">
    <div style="align-items:center;">
        <label for="menu_date">
            <p style="font-size: 15px;margin:0;padding:0px;">설문 종료 시간 : </p>
        </label>
        <input name="survey_deadline" type="datetime-local" value="<?php echo $deadline ?>"/>
        <input type="submit" value="수정" style="margin:10px;padding:3px;display:inline-block;"/>
    </div>
</form>