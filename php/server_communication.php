<?php

function block_sql_strings($str) {
    return preg_replace("/( select| union| insert| update| delete| drop| and| or|\"|\'|#|\/\*|\*\/|\\\|\;)/i" , "" ,  $str); 
}

function block_sql_injection($str) {
	$str = str_replace("<" , "&lt" , $str);  
	$str = str_replace(">" , "&gt" , $str);  
	$str = str_replace("'" , "&apos" , $str);   
	$str = str_replace("\"" , "&quot" , $str);  
	$str = str_replace("\r" , "" , $str);
	$str = str_replace("'" , "" , $str);   
	$str = str_replace('"' , "" , $str);  
	$str = str_replace("--" , "" , $str);
	$str = str_replace(";" , "" , $str);
	$str = str_replace("%" , "" , $str);
	$str = str_replace("+" , "" , $str);
	$str = str_replace("script" , "" , $str);
	$str = str_replace("alert" , "" , $str);
	$str = str_replace("cookie" , "" , $str);
	$str = block_sql_strings($str);
    return $str;
}

function connect_server() {
    return mysqli_connect('localhost' , 'admin' , '314159' , 'HAFSFood');
}

function increment_connect() {
	$connect = connect_server();
	$sql_req = "SELECT * FROM connected_number WHERE year=".date("Y")." AND month=".date("m")." AND day=".date("d").";";
	$result = mysqli_query($connect , $sql_req);
	$row = mysqli_fetch_assoc($result);
	$new_num = 0;
	if(!$row) {
		$sql_req = "INSERT INTO connected_number (year,month,day,num) VALUE(".date("Y").",".date("m").",".date("d").",1);";
		// echo $sql_req;
	}
	else {
		$new_num = ((int)$row['num'])+1;
		$sql_req = "UPDATE connected_number SET num=$new_num WHERE year=".date("Y")." AND month=".date("m")." AND day=".date("d").";";
		// echo $sql_req;
	}
	mysqli_close($connect);
	$connect = connect_server();
	if(!mysqli_query($connect , $sql_req)) {
		mysqli_close($connect);
		return false;
	}
	mysqli_close($connect);
	return true;
}

?>