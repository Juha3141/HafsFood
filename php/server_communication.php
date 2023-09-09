<?php

function block_sql_strings($str) {
    return preg_replace("/( select| union| insert| update| delete| drop| and| or|\"|\'|#|\/\*|\*\/|\\\|\;)/i" , "" ,  $str); 
}

function block_sql_injection($str) {
    $str = str_replace("&" , "&amp" , $str); 
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

?>