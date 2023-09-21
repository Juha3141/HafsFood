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
        <a href="index.php">
            <img src="img/logo.png" style="width:150px;position:absolute;top:0px;left:0px;">
        </a>
        <div id="user_info_dialog_div" style="width:20%;">
            <h3 style="text-align: center">관리자 로그인</h3>
            <form name="login_form" method="POST">
                <div id="user_info_dialog">
                    <label class="label">아이디</label> 
                    <input name="user" class="input" type="text" placeholder="id"></input>
                    <label class="label">비밀번호</label> 
                    <input name="password" class="input" type="password" placeholder="password"></input>
                    <p style="color:#FF0000;font-size:10px;margin:5px;" id="error_msg" hidden></p>
                    <input type="submit" class="button" value="Login"/>
                </div>
            </form>
        </form>
        <?php
            if(!isset($_POST['user'])||!isset($_POST['password'])) {
                exit();
            }
            if($_POST['user'] == "") {
                echo "<script>document.getElementById(\"error_msg\").hidden = false;
                document.getElementById(\"error_msg\").innerHTML = \"아이디를 입력해 주세요!\";</script>";
                exit();
            }
            if($_POST['password'] == "") {
                echo "<script>document.getElementById(\"error_msg\").hidden = false;
                document.getElementById(\"error_msg\").innerHTML = \"비밀번호를 입력해 주세요!\";</script>";
                exit();
            }
            $result = process_submit($_POST['user'],$_POST['password'],"admin_list");
            if($result == 0) {
                $_POST['password'] = "";
                session_start();
                $_SESSION['username'] = $_POST['user'];
                // where to go?
                echo "<script>location.href = \"./admin.php\"</script>"; // admin page
            }
            else if($result == -2) { // incorrect password
                echo "<script>document.getElementById(\"error_msg\").hidden = false;
                document.getElementById(\"error_msg\").innerHTML = \"비밀번호가 일치하지 않습니다!\"</script>";
            }
            else if($result == -1) {
                echo "<script>document.getElementById(\"error_msg\").hidden = false;
                document.getElementById(\"error_msg\").innerHTML = \"아이디가 존재하지 않습니다!\"</script>";
            }
        ?>
    </body>
</html>