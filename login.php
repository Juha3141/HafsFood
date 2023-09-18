<?php
include('./php/server_communication.php');
include('./php/process_login.php');
?>

<html>
    <meta charset="utf-8">
    <title>로그인</title>
    <head>
        <link rel="stylesheet" type="text/css" href="css/user_info_dialog.css">
        <link rel="stylesheet" type="text/css" href="css/login.css">
        <script src="script/login_acts.js"></script>
    </head>
    <body>
        <img src="img/logo.png" style="width:150px;position:absolute;top:0px;left:0px;">
        <div id="user_info_dialog_div" style="width:20%;">
            <h3 style="text-align: center">로그인</h3>
            <form name="login_form" method="POST">
                <div id="text_select">
                    <span class="right">
                        <label for="login_type_student"><input type="radio" name="login_type" id="login_type_student" value="user_list" checked="checked">학생</label>
                        <label for="login_type_adminis"><input type="radio" name="login_type" id="login_type_adminis" value="admin_list">관리자</label>
                    </span>
                </div>
                <div id="user_info_dialog">
                    <label class="label">아이디</label> 
                    <input name="user" class="input" type="text" placeholder="id"></input>
                    <label class="label">비밀번호</label> 
                    <input name="password" class="input" type="password" placeholder="password"></input>
                    <p style="color: #FF0000; font-size: 10px;" id="error_msg"></p>
                    <input type="submit" class="button" value="Login"/>
                </div>
            </form>
        </form>
        <?php
            if(!isset($_POST['user'])||!isset($_POST['password'])) {
                exit();
            }
            if($_POST['user'] == "") {
                echo "<script>document.getElementById(\"error_msg\").innerHTML = \"아이디를 입력해 주세요!\";</script>";
                exit();
            }
            if($_POST['password'] == "") {
                echo "<script>document.getElementById(\"error_msg\").innerHTML = \"비밀번호를 입력해 주세요!\";</script>";
                exit();
            }
            $result = process_submit($_POST['user'],$_POST['password'],$_POST['login_type']);
            if($result == 0) {
                $_POST['password'] = "";
                session_start();
                $_SESSION['username'] = $_POST['user'];
                // where to go?
                if($_POST['login_type'] == "user_list") {
                    $_SESSION['account_type'] = "user";
                    echo "<script>location.href = \"./index.php\"</script>";
                }
                else if($_POST['login_type'] == "admin_list") {
                    $_SESSION['account_type'] = "admin";
                    echo "<script>location.href = \"./admin.php\"</script>"; // admin page
                }
            }
            else if($result == -2) { // incorrect password
                echo "<script>document.getElementById(\"error_msg\").innerHTML = \"비밀번호가 일치하지 않습니다!\"</script>";
            }
            else if($result == -1) {
                echo "<script>document.getElementById(\"error_msg\").innerHTML = \"아이디가 존재하지 않습니다!\"</script>";
            }
        ?>
    </body>
</html>