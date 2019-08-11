<?php

session_start();

header("content-type:text/html; charset=utf-8");

$db = new PDO("mysql:host=127.0.0.1;dbname=trycart;port=3306", "root", "root");
$db->exec("set names utf8");

// 取得當前使用者名稱
$sUserName = $_SESSION["txtUserName"];

// 查詢當前使用者id
$useridresult =$db->query("select memid from member where memName='$sUserName'");
$userid=$useridresult->fetch();

// 取得訂單總額
$history=$db->query("select 
                    b.bid,
                    sum(p.pdtprice*b.quantity) as total,
                    case 
                    when b.status=-2 then '退貨' 
                    when b.status=-1 then '已取消' 
                    when b.status=0 then '未確認' 
                    when b.status=1 then '已確認' end as new_status
                    from billdetial as b,member as m,product as p 
                    where b.memid=m.memid and b.pdtid=p.pdtid and b.memid={$userid["memid"]} 
                    group by b.bid,new_status order by b.bid");
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>History</title>
</head>
<body>
<form method="post" action="login.php">
<input type="submit" name="btnHome" id="btnHome" value="回首頁" /> 
</form>
<form method="post" action="">  
<table border="1">
            <td>訂單編號</td>
            <td>總計金額</td>
            <td>訂單狀態</td>
            <td>取消/退貨</td>            
        <?php while($row = $history->fetch()){ ?>
        <tr>
            <td><?php echo $row["bid"]?></td>
            <td><?php echo $row["total"]?></td>
            <td><?php echo $row["new_status"]?></td>
            <td><input type="submit" name="btnCancal" id="btnCancal" value="取消" />
            <input type="submit" name="btnReturn" id="btnReturn" value="退貨" /></td>
        </tr>
        <?php } ?>
    </table>
    </form>    
</body>
</html>