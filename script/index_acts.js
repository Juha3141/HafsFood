function update_date() {
    date = new Date();
    years = date.getFullYear();
    month = date.getMonth()+1;
    day = date.getDate();
    document.getElementById("today_show").innerHTML = years+". "+month+". "+day+".";
    setTimeout(update_date , 1000);
}

function goto_special() {
    location.href="./special_menu_page.php";
}