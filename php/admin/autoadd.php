<p style="margin:0;font-weight:bold;font-size:30px;text-align:center;">자동 메뉴 추가</p>
<?php 

include_once('simplehtmldom/simple_html_dom.php');
include_once('php/auto_scrap.php');

$survey_target = get_survey_target_date();
$date_from = $survey_target[0];
$date_to = $survey_target[1];
if(isset($_POST['autoadd_date_from'])) {
    $date_from = $_POST['autoadd_date_from'];
}
if(isset($_POST['autoadd_date_to'])) {
    $date_to = $_POST['autoadd_date_to'];
}
?>

<p style="font-size:15px;">
*자동 메뉴 추가 : hafs.hs.kr에서 올린 급식 데이터를 자동으로 웹사이트 데이터베이스에 입력해주는 시스템 입니다.
</p>
<p style="font-weight:bold">
체크표시된 메뉴는 현재 추가되어 있지 않은 메뉴 입니다. 메뉴를 체크해제하면 메뉴가 추가되지 않습니다. (체크표시되어있지 않은 메뉴를 체크표시하면 메뉴가 다시 등록됩니다. 메뉴 중복 방지를 위해 체크표시 되지 않은 메뉴는 체크표시하면 안됩니다.)
</p>

<form method="POST" action="admin.php#autoadd">
    <div style="display:flex;flex-direction:row;align-items:center;">
        <label for="menu_date">
            <p style="font-size: 15px;">입력할 날짜 :
                <input name="autoadd_date_from" type="date" value="<?php echo $date_from ?>"/>
                ~
                <input name="autoadd_date_to" type="date" value="<?php echo $date_to ?>"/>
            </p>
        </label>
        <input type="submit" value="조회" style="margin:10px;padding:3px;display:inline-block;"/>
    </div>
</form>

<div class="viewer" style="display:flex;flex-direction:column;align-items:center;">
    <form method="POST" action="modify_db.php?req=5">
        <div style="border:1px solid black;padding:10px;float:center;width:97%;height:300px;overflow-x:scroll;overflow-y:scroll;">
            <?php
            $full_data = [];
            $menu_cnt = 0;
            $d = +1;
            if($date_from > $date_to) {
                $d = -1;
            }
            for($date = strtotime("$date_from +0 day"); $date != strtotime("$date_to +1 days"); $date = strtotime(date("Y-m-d" , $date)." +$d days")) {
                echo date("Y-m-d" , $date)."<br>";
                $data = get_menu_list_hafs(date("Y-m-d" , $date));
                $full_data[] = $data;
                $database_food = get_menu_list(date("Y-m-d" , $date));
                foreach(["breakfast","lunch","dinner"] as $meal) {
                    echo ["breakfast"=>"아침","lunch"=>"점심","dinner"=>"저녁"][$meal]." : <br>";
                    foreach($data[$meal] as $menu_name) {
                        $checked = "checked";
                        if($menu_name == "") {
                            continue;
                        }
                        foreach($database_food[$meal] as $one_menu) {
                            if($one_menu['name'] == $menu_name) {
                                $checked = "";
                                break;
                            }
                        }
                        ?>
                        <input name="autoadd_chk_<?php echo $menu_cnt ?>" type="checkbox" value="<?php echo date("Y-m-d",$date).'|'.$meal.'|'.$menu_name ?>" <?php echo $checked ?>>
                        <?php
                        $menu_cnt++;
                        echo $menu_name."";
                    }
                    echo "<br>";
                }
            }
            ?>
        </div>
        <input type="hidden" name="autoadd_item_cnt" value="<?php echo $menu_cnt ?>">
        <div style="display:flex;justify-content:center;padding:20px;">
            <input type="submit" id="autoadd_data_submit" class="button_submit" value="입력">
        </div>
    </form>
</div>