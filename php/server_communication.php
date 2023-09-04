<?php

function block_sql_injection($str) {
    return 0;
}

function connect_server() {
    return mysqli_connect('localhost' , 'admin' , '314159' , 'HAFSFood');
}

?>