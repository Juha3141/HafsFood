function goto_special() {
    location.href="./special_menu_page.php";
}

function deadline_show(deadline) {
    let today = new Date();
    let deadline_time = new Date(deadline);
    var sec = Math.floor((deadline_time.getTime()-today.getTime())/1000);
    var element = document.getElementById("deadline_show");

    days = Math.floor(sec/86400);
    time = sec-(days*86400);
    hours = Math.floor(time/3600);
    time = time-(hours*3600);
    minutes = Math.floor(time/60);
    seconds = time-(minutes*60);
    
    var end_str = "";
    if(days != 0) {
        end_str += days+"일 ";
    }
    if(hours != 0) {
        end_str += hours+"시간 ";
    }
    if(minutes != 0) {
        end_str += minutes+"분 ";
    }
    if(seconds != 0) {
        end_str += seconds+"초";
    }
    element.innerHTML = "설문 종료까지 "+end_str+" 남음";
}

function start_clock(deadline) {
    setInterval(deadline_show , 500 , deadline);
}