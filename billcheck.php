<?php

session_start();

header("content-type:text/html; charset=utf-8");

$db = new PDO("mysql:host=127.0.0.1;dbname=trycart;port=3306", "root", "root");
$db->exec("set names utf8");

// 取得當前使用者名稱
$sUserName = $_SESSION["txtUserName"];

// 查詢當前使用者id
$useridresult =$db->query("select memid from member where memName='$sUserName'");

$result = $db->query("select * from product order by pdtid");

$countresult = $db->query("select count(1) as countrow  from product");

// echo "<br>". step1 ."<hr>";

// 確認回傳商品訂購數陣列
// var_dump($_POST["int_count"]);
// echo "<hr>";

// 取得當下年月日時分秒作為唯一帳單id
$bidTmp = (new \DateTime())->format('YmdHis');
//  echo "唯一帳單id:".$bidTmp."<hr>";

// $countrow = $countresult->fetch();
// echo $countrow["countrow"]."<hr>";

// 確認當前使用者名稱
// echo "當前使用者帳號:".$sUserName."<hr>";

// 取得當前使用者id
$userid=$useridresult->fetch();
// echo "當前使用者id:".$userid["memid"]."<hr>";

//商品訂購數陣列
$billtail =$_POST["int_count"];

//測試取出商品訂購數陣列
// foreach($billtail as $key => $value){
//     echo $key."的分數：".$value."分<br>";
// }

// echo "<br>". step2 ."<hr>";


//產品與訂購數組合
while($row = $result->fetch()){
    $i+=1;
    // 測試產品與訂購數組合
    echo 
        "bid:".$bidTmp."<br>".
        "pid:".$row["pdtid"]."<br>".
        "memid:".$userid["memid"]."<br>".
        "數量:".$_POST["int_count"][$i-1]."<br>"."<hr>";
    
    // 將訂購內容寫入資料庫
    $db->query("insert into billdetial(bid,memid,pdtid,quantity,status) values($bidTmp,{$userid["memid"]},{$row["pdtid"]},{$_POST["int_count"][$i-1]},0)");

    
}



?>