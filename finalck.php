<?php

session_start();

header("content-type:text/html; charset=utf-8");

$db = new PDO("mysql:host=127.0.0.1;dbname=trycart;port=3306", "root", "root");
$db->exec("set names utf8");

$bidTmp = $_SESSION["bidTmp"];
$sUserName = $_SESSION["txtUserName"];

// echo $bidTmp."<hr>";
// echo $sUserName."<hr>";

// 查詢當前使用者id
$useridresult =$db->query("select memid from member where memName='$sUserName'");
// 取得當前使用者id
$userid=$useridresult->fetch();


// 取消當前訂單
if (isset($_POST["btnCancal"]))
{   
    $db->query("update billdetial set status='-1' where bid =$bidTmp and memid={$userid["memid"]} and status =0");
    header("Location: index.php");
    exit();
}
// 確認當前訂單
if (isset($_POST["btnckOK"]))
{
    $db->query("update billdetial set status='1' where bid =$bidTmp and memid={$userid["memid"]} and status=0");
    header("Location: index.php");
    exit();
}



?>