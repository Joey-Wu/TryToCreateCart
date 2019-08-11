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

$a = $bidTmp;
$_SESSION["bidTmp"]=$a;

// $countrow = $countresult->fetch();
// $ckcountrow = $countrow["countrow"];
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
    // echo 
    //     "bid:".$bidTmp."<br>".
    //     "pid:".$row["pdtid"]."<br>".
    //     "memid:".$userid["memid"]."<br>".
    //     "數量:".$_POST["int_count"][$i-1]."<br>"."<hr>";
    
    // 將訂購內容寫入資料庫
    $db->query("insert into billdetial(bid,memid,pdtid,quantity,status) values($bidTmp,{$userid["memid"]},{$row["pdtid"]},{$_POST["int_count"][$i-1]},0)");
   }

   // 取得未結訂單明細
   $billck =$db->query("select 
                        b.bid,
                        p.pdtName,
                        p.pdtprice,
                        b.quantity,
                        p.pdtprice*b.quantity as subtotal 
                        from billdetial as b,member as m,product as p 
                        where b.memid=m.memid and
                                b.pdtid=p.pdtid and 
                                b.bid =$bidTmp  and b.memid={$userid["memid"]} and status =0");

   // 取得未結訂單總額
    $totalck =$db->query("select 
                            b.bid,
                            b.memid,
                            sum(p.pdtprice*b.quantity) as total 
                            from billdetial as b,product as p 
                            where b.pdtid=p.pdtid and 
                            b.bid =$bidTmp and b.memid={$userid["memid"]} and status =0 
                            group by b.bid,b.memid");

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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bill_Ckeck</title>
</head>
<body>
    <tr>Hello , <?php echo $sUserName; ?><br></tr>
    <tr>請確認訂購內容</tr>
    <form method="post" action="finalck.php">
    <tr>
    <table border="1">
        <tr>
        <td>訂單編號<?php $totalrow = $totalck->fetch(); 
                echo $totalrow["bid"];
            ?></td>
        </tr>
            <td>產品名稱</td>
            <td>單價</td>
            <td>購買數量</td>
            <td>小記</td>
        <?php while($billckrow = $billck->fetch()){ ?>
        <tr>
            <td><?php echo $billckrow["pdtName"]?></td>
            <td><?php echo $billckrow["pdtprice"]?></td>
            <td><?php echo $billckrow["quantity"]?></td>
            <td><?php echo $billckrow["subtotal"]?></td>
        </tr>
        <?php } ?>
            <td>總計 
            <?php 
                echo $totalrow["total"];
            ?>
            </td>
    </table>
    <tr>
    <input type="submit" name="btnckOK" id="btnckOK" value="確認訂單" />
    <input type="submit" name="btnCancal" id="btnCancal" value="取消" />
    </tr>
    </form> 
</body>
</html>