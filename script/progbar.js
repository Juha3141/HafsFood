function start_progressbar(classname , percentages) {
    var prog_bars = document.getElementsByClassName(classname);
    let id_list = [];
    let width = [0 , 0];
    let vel = [1.6 , 1.6];
    let acc = [-0.001 , -0.001];
    for(var i = 0; i < prog_bars.length; i++) {
        prog_bars.item(i).style.width = "0%";
        var id = setInterval(move_progbar, 5 , prog_bars.item(i) , i , percentages[i]);
        id_list = id_list.push(id);
    }
    function move_progbar(prog_bar , i , percentage) {
        if(width[i] >= percentage) {
            prog_bar.style.width = Math.trunc(percentage)+"%";
            prog_bar.innerHTML = Math.trunc(percentage)+"%";
            clearInterval(id_list[i]);
        }
        else {
            prog_bar.style.width = Math.trunc(width[i])+"%";
            prog_bar.innerHTML = Math.trunc(width[i])+"%";
            width[i] += vel[i];
            vel[i] += acc[i];
        }
    }
}

function start_progressbar_special(classname , initial , percentages , bar_event) {
    var prog_bars = document.getElementsByClassName(classname);
    let id_list = [];
    let width = initial;
    let vel = [1.6 , 1.6];
    let acc = [-0.001 , -0.001];
    for(var i = 0; i < prog_bars.length; i++) {
        prog_bars.item(i).style.width = "0%";
        var id = setInterval(move_progbar, 5 , prog_bars.item(i) , i , percentages[i]);
        id_list = id_list.push(id);
    }
    function move_progbar(prog_bar , i , percentage) {
        if(width[i] >= percentage) {
            prog_bar.style.width = Math.trunc(percentage)+"%";
            clearInterval(id_list[i]);
        }
        else {
            prog_bar.style.width = Math.trunc(width[i])+"%";
            bar_event();
            width[i] += vel[i];
            vel[i] += acc[i];
        }
    }
}

function start_progressbar_all(classname , initial , percentage_global) {
    var prog_bars = document.getElementsByClassName(classname);
    let id_list = [];
    let width = [];
    let vel = [];
    let acc = [];
    for(var k = 0; k < prog_bars.length; k++) {
        width.push(initial);
        prog_bars.item(k).style.width = "0%";
        var id = setInterval(move_progbar, 5 , prog_bars.item(k) , k , percentage_global);
        id_list.push(id);

        vel.push(1.6);
        acc.push(-0.001);
    }
    function move_progbar(prog_bar , i , percentage) {
        if(width[i] >= percentage) {
            prog_bar.style.width = Math.trunc(percentage)+"%";
            clearInterval(id_list[i]);
        }
        else {
            prog_bar.style.width = Math.trunc(width[i])+"%";
            width[i] += vel[i];
            vel[i] += acc[i];
        }
    }
}