<?php

session_start();

if (isset($_SESSION["txtUserName"])){
  $sUserName = $_SESSION["txtUserName"];
}else{ 
 header("location:login.php");
}

$db = new PDO("mysql:host=127.0.0.1;dbname=trycart;port=3306", "root", "root");
$result = $db->query("select * from product");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ProductPage</title>
</head>
<body>
    <tr>Hello , <?php echo $sUserName; ?></tr>
    <form>
    <tr>
    <table border="1">
            <td>產品序號</td>
            <td>產品名稱</td>
            <td>單價</td>
            <td>購買數量</td>
        <?php while($row = $result->fetch()){ ?>
        <tr>
            <td><?php echo $row["pdtid"]?></td>
            <td><?php echo $row["pdtName"]?></td>
            <td><?php echo $row["pdtprice"]?></td>
            <td><input type="number" min="0" max="10" value="0" name="int_count" id="intCount" /></td>
        </tr>
        <?php } ?>
    </table>
    <tr>
    <input type="submit" name="btnOK" id="btnOK" value="結帳" />
    <input type="reset" name="btnReset" id="btnReset" value="清空" />
    </tr>
    </form>
</body>
</html>