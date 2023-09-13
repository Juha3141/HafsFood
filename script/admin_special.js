function process_cancel(target,input_id,modify_id,submit_id,cancel_id) {
    var input = document.getElementById(input_id);
    input.readOnly = true;
    
    document.getElementById(modify_id).style.display = "";
    document.getElementById(submit_id).style.display = "none";
    document.getElementById(cancel_id).style.display = "none";
    input.style.border = "0";

    input.value = target;
}

function process_modify(input_id,modify_id,submit_id,cancel_id) {
    var input = document.getElementById(input_id);
    input.readOnly = false;
    
    document.getElementById(modify_id).style.display = "none";
    document.getElementById(submit_id).style.display = "";
    document.getElementById(cancel_id).style.display = "";
    
    input.style.borderRadius = "5px";
    input.style.border = "2px solid black";
}

function modify_special_by_id(target,month,input_id,modify_id,submit_id,cancel_id) {
    var input = document.getElementById(input_id);
    input.readOnly = true;
    
    if(modify_id != "") document.getElementById(modify_id).style.display = "";
    if(submit_id != "") document.getElementById(submit_id).style.display = "none";
    if(submit_id != "") document.getElementById(cancel_id).style.display = "none";
    input.style.border = "0";
    if(input.value == target) {
        return;
    }
    var new_value = btoa(`${encodeURIComponent(input.value)}`);
    location.href = "./modify_db.php?req=4&val="+new_value+"&mon="+month;
}