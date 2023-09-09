<?php
include('./php/server_communication.php');

session_start();
if((!isset($_SESSION['username']))||($_SESSION['account_type'] != "admin")) {
    echo "<script>history.back();</script>";
    exit();
}

?>

<!DOCTYPE html>

<html lang="ko">
    <meta charset="utf-8"/>
    <head>
        <title>관리자 페이지</title>
        <link rel="stylesheet" type="text/css" href="css/admin.css"></style>
    </head>
    <body>
        <h2>관리자 페이지</h2>
        <nav class="navbar">
            <form id="nav_form" action="GET">
                <a class="txt_content" href="admin.php?page=stat">통계</a>
                <a class="txt_content" href="admin.php?page=menu">메뉴 추가</a>
                <a class="txt_content" href="admin.php?page=special">특식 설정</a>
            </form>
        </nav>
    </body>
</html>