<p style="margin:0;font-weight:bold;font-size:30px;text-align:center;">메뉴 추가/수정</p>
<?php
$day = date("Y-m-d");
if(isset($_GET['menu_date'])) {
    $day = $_GET['menu_date'];
}
?>
<form method="GET" action="admin.php#modifymenu">
    <div style="display:flex;flex-direction:row;align-items:center;">
        <label for="menu_date">
            <p style="font-size: 15px;">조회할 날짜 :
                <input name="menu_date" type="date" value="<?php echo $day ?>"/>
            </p>
        </label>
        <input type="submit" value="조회" style="margin:10px;padding:3px;display:inline-block;"/>
    </div>
</form>

<script src="script/admin_menu.js"></script>

<div class="viewer">
    <div style="display:flex;flex-direction:row;">
        <?php 
        $menu_data = get_menu_list($day);
        $meal_index = 0;
        foreach($menu_data as $one_meal) {
            $meal_to_kr = ["아침","점심","저녁"];
            $meal_id = ["breakfast","lunch","dinner"];
        ?>
        <div id="menu_mod_parent_<?php echo $meal_id[$meal_index] ?>" class="one_seg_show">
            <div id="menu_mod_child_<?php echo $meal_id[$meal_index] ?>">
                <p style="font-size:25px;margin:0;padding:5px;"><?php echo $meal_to_kr[$meal_index] ?></p>
                <?php
                foreach($one_meal as $one_menu) {
                    ?>
                    <div class="one_menu">
                        <!-- <p style="font-size:20px;margin:0;padding:5px;"> -->
                        <input id="input_<?php echo $meal_id[$meal_index]."_".$one_menu['name']; ?>" 
                               class="menu_editor" 
                               type="text" 
                               value="<?php echo $one_menu['name']; ?>" 
                               readonly>
                        <span style="margin-right:20px;display:flex;justify-content:space-between;">
                            <input id="btn_submit_<?php echo $meal_id[$meal_index]."_".$one_menu['name']; ?>" 
                                   class="modify_button" 
                                   type="button" 
                                   value="확인" 
                                   style="display:none;" 
                                   onclick="modify_menu('<?php echo $one_menu['name'] ?>','<?php echo $meal_id[$meal_index] ?>','<?php echo $day ?>');"/>

                            <input id="btn_cancel_<?php echo $meal_id[$meal_index]."_".$one_menu['name']; ?>" 
                                   class="modify_button" 
                                   type="button" 
                                   value="취소" 
                                   style="display:none;" 
                                   onclick="cancel_menu('<?php echo $one_menu['name'] ?>','<?php echo $meal_id[$meal_index] ?>');"/>

                            <input id="btn_modify_<?php echo $meal_id[$meal_index]."_".$one_menu['name']; ?>" 
                                   class="modify_button"
                                   type="button"
                                   value="수정"
                                   onclick="process_menu('<?php echo $one_menu['name'] ?>','<?php echo $meal_id[$meal_index] ?>');"/>

                            <input id="btn_remove_<?php echo $meal_id[$meal_index]."_".$one_menu['name']; ?>"
                                   class="modify_button"
                                   type="button"
                                   value="삭제"
                                   onclick="remove_menu('<?php echo $one_menu['name'] ?>','<?php echo $meal_id[$meal_index] ?>','<?php echo $day ?>');"/>
                        </span>
                    </div>
                    <?php
                }?>
            </div>
            <div style="display:flex;justify-content:center;">
                <input type="button" class="add_button" value="+" onclick="create_new_form('<?php echo $meal_id[$meal_index] ?>','<?php echo $day ?>');"/>
            </div>
            <?php $meal_index++ ?>
        </div>
        <?php
        }
        ?>
    </div>
</div>