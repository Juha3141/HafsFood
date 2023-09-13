<p style="margin:0;font-weight:bold;font-size:30px;text-align:center;">특식 추가/수정</p>
<?php 
include('index_element_manage.php');
?>

<script src="script/admin_special.js"></script>

<?php
function print_one_special($month,$input_id,$input_value,$btn_ok_id,$btn_cancel_id,$btn_mod_id) {?>
    <div class="one_menu">
        <!-- <p style="font-size:20px;margin:0;padding:5px;"> -->
        <span style="margin-left:20px;display:flex;">
            <label style="padding:5px;margin-right:10px;font-size:20px;">
                <?php echo $month."월" ?>
            </label>
            <input id="<?php echo $input_id ?>" 
                   class="menu_editor"
                   style="width:70%;";
                   type="text" 
                   value="<?php echo $input_value ?>" 
                   readonly>
        </span>
        <span style="margin-right:20px;display:flex;justify-content:space-between;">
            <input id="<?php echo $btn_ok_id ?>"
                   class="modify_button" 
                   type="button" 
                   value="확인"
                   style="display:none;"; 
                   onclick="modify_special_by_id(<?php echo '\''.$input_value.'\',\''.$month.'\',\''.$input_id.'\',\''.$btn_mod_id,'\',\''.$btn_ok_id.'\',\''.$btn_cancel_id.'\'';?>);"/>
            <input id="<?php echo $btn_cancel_id ?>"
                   class="modify_button" 
                   type="button" 
                   value="취소"
                   style="display:none;"; 
                   onclick="process_cancel(<?php echo '\''.$input_value.'\',\''.$input_id.'\',\''.$btn_mod_id.'\',\''.$btn_ok_id.'\',\''.$btn_cancel_id.'\''?>);"/>
           <input id="<?php echo $btn_mod_id ?>" 
                   class="modify_button"
                   type="button"
                   value="수정"
                   onclick="process_modify(<?php echo '\''.$input_id.'\',\''.$btn_mod_id.'\',\''.$btn_ok_id.'\',\''.$btn_cancel_id.'\''?>);"/>
        </span>
    </div> <?php
}
?>

<div class="viewer" style="display:flex;flex-direction:row;">
    <?php
    $month = 1;
    $year = date("Y");
    for($j = 0; $j < 3; $j++) { // row
        ?> 
        <div class="one_seg_show"> 
        <?php
        for($i = 0; $i < 4; $i++) {
            $input_id = "input_".$month;
            $input_value = get_special_menu($month , $year);
            $btn_ok_id = "btn_ok_".$month;
            $btn_cancel_id = "btn_cancel_".$month;
            $btn_mod_id = "btn_mod_".$month;
            if($input_value == "") {
                $input_value = "(없음)";
            }
            print_one_special($month , $input_id , $input_value , $btn_ok_id , $btn_cancel_id , $btn_mod_id);
            $month++;
        }
        ?>
        </div>
        <?php    
    }
    ?>
</div>