<?php 

session_start();

// 判斷登出且跳轉至首頁
if (isset($_GET["logout"]))
{
  unset($_SESSION["txtUserName"]);
  header("Location: index.php");
	exit();
}

// 判斷跳轉回首頁
if (isset($_POST["btnHome"]))
{
	header("Location: index.php");
	exit();
}

header("content-type:text/html; charset=utf-8");

$db = new PDO("mysql:host=127.0.0.1;dbname=trycart;port=3306", "root", "root");
$db->exec("set names utf8");

$sUserName = $_POST["txtUserName"];
$sUserPassword = $_POST["txtPassword"];


// 查詢帳號密碼
// 帳：abc 密：456
// 帳：qwe 密：123
// 帳：zxc 密：789
$result = $db->query("select mempasswd from member where memName='$sUserName'");
 

if (isset($_POST["btnOK"]))
{
  $row = $result->fetch();
  // echo $row["mempasswd"];

  // 判斷帳號密碼
  if($sUserPassword == $row["mempasswd"] && $sUserName != "" && $sUserPassword != ""){ 
    header("Location:index.php"); 
    $_SESSION["txtUserName"] = $sUserName; 
    } else { 
    echo "登入失敗"; 
  }
	
}

?>


<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Lab - Login</title>
</head>
<body>
<form id="form1" name="form1" method="post" action="login.php">
  <table width="300" border="0" align="center" cellpadding="5" cellspacing="0" bgcolor="#F2F2F2">
    <tr>
      <td colspan="2" align="center" bgcolor="#CCCCCC"><font color="#FFFFFF">會員系統 - 登入</font></td>
    </tr>
    <tr>
      <td width="80" align="center" valign="baseline">帳號</td>
      <td valign="baseline"><input type="text" name="txtUserName" id="txtUserName" /></td>
    </tr>
    <tr>
      <td width="80" align="center" valign="baseline">密碼</td>
      <td valign="baseline"><input type="password" name="txtPassword" id="txtPassword" /></td>
    </tr>
    <tr>
      <td colspan="2" align="center" bgcolor="#CCCCCC"><input type="submit" name="btnOK" id="btnOK" value="登入" />
      <input type="reset" name="btnReset" id="btnReset" value="重設" />
      <input type="submit" name="btnHome" id="btnHome" value="回首頁" />
      </td>
    </tr>
  </table>
</form>
</body>
</html>