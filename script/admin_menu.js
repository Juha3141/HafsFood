function process_menu(target,meal) {
    var input = document.getElementById("input_"+meal+"_"+target);
    input.readOnly = false;
    
    document.getElementById("btn_modify_"+meal+"_"+target).style.display = "none";
    document.getElementById("btn_remove_"+meal+"_"+target).style.display = "none";
    document.getElementById("btn_submit_"+meal+"_"+target).style.display = "";
    document.getElementById("btn_cancel_"+meal+"_"+target).style.display = "";
    
    input.style.borderRadius = "5px";
    input.style.border = "2px solid black";
}

function cancel_menu(target,meal,date) {
    var input = document.getElementById("input_"+meal+"_"+target);
    input.readOnly = true;
    
    document.getElementById("btn_modify_"+meal+"_"+target).style.display = "";
    document.getElementById("btn_remove_"+meal+"_"+target).style.display = "";
    document.getElementById("btn_submit_"+meal+"_"+target).style.display = "none";
    document.getElementById("btn_cancel_"+meal+"_"+target).style.display = "none";
    input.style.border = "0";
    input.value = target;
}

function modify_menu(target,meal,date) {
    var input = document.getElementById("input_"+meal+"_"+target);
    input.readOnly = true;
    
    document.getElementById("btn_modify_"+meal+"_"+target).style.display = "";
    document.getElementById("btn_remove_"+meal+"_"+target).style.display = "";
    document.getElementById("btn_submit_"+meal+"_"+target).style.display = "none";
    document.getElementById("btn_cancel_"+meal+"_"+target).style.display = "none";
    input.style.border = "0";
    if(input.value == target) {
        return;
    }
    var new_value = btoa(`${encodeURIComponent(input.value)}`);
    var original = btoa(`${encodeURIComponent(target)}`);
    location.href = "./modify_db.php?req=1&val="+new_value+"&o="+original+"&date="+date+"&meal="+meal;
}

function remove_menu(target,meal,date) {
    var original = btoa(`${encodeURIComponent(target)}`);
    if(confirm("메뉴을 지웁니다.")) {
        location.href = "./modify_db.php?req=2&o="+original+"&date="+date+"&meal="+meal;
    }
}

function create_new_form(meal,date) {
    var dialog = document.createElement("div");
    var input = document.createElement("input");
    var span_btn = document.createElement("span");
    var btn_confirm = document.createElement("input");
    var btn_cancel = document.createElement("input");
    btn_confirm.id = "btn_create_confirm";
    btn_confirm.className = "modify_button";
    btn_confirm.type = "button";
    btn_confirm.value = "추가";
    btn_confirm.onclick = ()=>{
        if(input.value == "") {
            alert("메뉴 이름을 입력해 주세요!");
            return;
        }
        var new_value = btoa(`${encodeURIComponent(input.value)}`);
        location.href = "./modify_db.php?req=3&new="+new_value+"&date="+date+"&meal="+meal;
    }
    
    btn_cancel.id = "btn_create_cancel";
    btn_cancel.className = "modify_button";
    btn_cancel.type = "button";
    btn_cancel.value = "취소";
    btn_cancel.onclick = ()=>{
        dialog.remove();
    }
    span_btn.style.marginRight = "20px";
    span_btn.style.display = "flex";
    span_btn.style.justifyContents = "space-between";
    span_btn.appendChild(btn_confirm);
    span_btn.appendChild(btn_cancel);
    input.id = "btn_submit_<?php echo $meal_id[$meal_index] ?>_new";
    input.className = "menu_editor";
    input.type = "text";
    input.value = "";
    input.readOnly = false;
    input.style.borderRadius = "5px";
    input.style.border = "2px solid black";
    dialog.className = "one_menu";
    
    dialog.appendChild(input);
    dialog.appendChild(span_btn);
    document.getElementById("menu_mod_child_"+meal).appendChild(dialog);
}