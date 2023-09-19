<?php
include('./php/server_communication.php');
include('./php/process_login.php');
?>

<html>
    <meta charset="utf-8">
    <title>회원가입</title>
    <head>
        <link rel="stylesheet" type="text/css" href="css/user_info_dialog.css">
        <link rel="stylesheet" type="text/css" href="css/join.css">
        <script src="script/login_acts.js"></script>
    </head>
    <body>
        <a href="index.php">
            <img src="img/logo.png" style="width:150px;position:absolute;top:0px;left:0px;">
        </a>
        <div id="user_info_dialog_div" style="width:20%;">
            <h3 style="text-align: center">회원가입</h3>
            <form name="join_form" method="POST">
                <div id="user_info_dialog">
                    <label class="label">아이디 (Case Sensitive!)</label>
                    <input name="user" class="input" type="text" placeholder="id"></input>
                    <label class="label">비밀번호</label> 
                    <input name="password" class="input" type="password" placeholder="password"></input>
                    <label class="label">비밀번호 확인</label> 
                    <input name="retypepassword" class="input" type="password" placeholder="retype password"></input>
                    <p style="color: #FF0000; font-size: 10px;" id="error_msg" hidden></p>
                    <input type="submit" class="button" value="Join"/>
                </div>
            </form>
        </div>
        <?php
            // 0 : var name, 1 : error msg (if it's not set)
            $vars = [['user' , "아이디를 입력해 주세요!"] , ['password' , "비밀번호를 입력해 주세요!"] , ['retypepassword' , "비밀번호를 한번 더 입력해 주세요!"]];
            $res_submit = 0;
            $res_proper = array();
            $i = 0;
            foreach($vars as $one_varname) {
                $res_submit += isset($_POST[$one_varname[0]]);
                $res_proper[$i++] = ($_POST[$one_varname[0]] != "");
            }
            if($res_submit == 0) {
                exit();
            }
            $err_msg = "";
            for($i = 0; $i < count($res_proper); $i++) {
                if($res_proper[$i] == 0) {
                    $err_msg = $vars[$i][1];
                    break;
                }
            }
            // extra filter
            if($error_msg == "") {
                if(id_exist($_POST['user'] , "user_list")) {
                    $err_msg = "아이디가 이미 존재합니다!";
                }
                if($_POST['password'] != $_POST['retypepassword']) {
                    $err_msg = "비밀번호가 일치하지 않습니다!";
                }
                if(!check_valid_id($_POST['user'])) {
                    $err_msg = "아이디는 공백 제외 영문, 숫자, '-' 또는 '_' 포함 5자 이상이여야 합니다!";
                }
            }

            if($err_msg != "") {
                echo "<script>document.getElementById(\"error_msg\").hidden = false;
                document.getElementById(\"error_msg\").innerHTML = \"".$err_msg."\"</script>";
                exit();
            }
            
            if(!register_user($_POST['user'] , $_POST['password'])) {
                echo "<br>error. you sure have messed the fuck out of this code";
            }
            
            echo "<script>alert(\"회원가입이 완료되었습니다!\"); location.href=\"./index.php\";</script>";
        ?>
    </body>
</html>