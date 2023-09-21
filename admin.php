<?php
include('./php/server_communication.php');
include('./php/admin/admin_tools.php');

session_start();
if((!isset($_SESSION['username']))) {
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
        <link rel="stylesheet" type="text/css" href="css/progbar.css"></style>
        
        <script src="script/progbar.js"></script>
    </head>
    <body>
        <h2>관리자 페이지</h2>

        <form id="nav_form" action="GET">
            <a class="txt_content" href="admin.php#statistics">통계</a>
            <a class="txt_content" href="admin.php#modifymenu">메뉴 설정</a>
            <a class="txt_content" href="admin.php#modifyspecial">특식 설정</a>
            <a class="txt_content" href="admin.php#autoadd">자동 메뉴 추가</a>
        </form>

        <div id="board">
            <div id="stat" class="element">
                <a name="statistics"></a>
                <?php include('./php/admin/stat.php'); ?>
            </div>
            <div id="menu" class="element">
                <a name="modifymenu"></a>
                <?php include('./php/admin/menu.php'); ?>
            </div>
            <div id="special" class="element">
                <a name="modifyspecial"></a>
                <?php include('./php/admin/special.php'); ?>
            </div>
            <div id="special" class="element">
                <a name="autoadd"></a>
                <?php include('./php/admin/autoadd.php'); ?>
            </div>
        </div>
        <script>
        start_progressbar_all("progressbar_mid",70,100);
        </script>
    </body>
</html>