<?php
session_start();
if(isset($_SESSION['username']))
{
    
    echo "欢迎您：".$_SESSION['username'];
    echo "<br><a href='changepw.php'>修改密码</a>";
    echo "<br><a href='logout.php'>注销</a>";
    exit();
}
?>
<html>
  <head>
    <meta http-equiv='Content-Type' content="text/html; charset=utf-8"/>
  </head>
  <body>
  <form method="post">
    用户名：<input type="text" name = "username">
    密码：<input type="text" name = "password">
    <input type="submit" value="注册">
  </form>
  <a href="login.php">登录</a><br>
  </body>
</html>
<?php
function filter($str){
    $str=addslashes($str);
    return $str;
}
if(!isset($_POST["username"])||!isset($_POST["password"]))
    exit();
include "db.php";

$username=filter($_POST["username"]);
$pw=filter($_POST["password"]);
$strsql="insert into user (name, password) values('$username','$pw')";
$result=$conn->query($strsql) or die(mysql_error());
$strsql="select * from user where name = '$username' and password = '$pw'";
$result=$conn->query($strsql);
$flag=false;
while($row=mysqli_fetch_row($result))
{
    $_SESSION["username"]=$row[1];
    $flag=true;
}

if(!$flag)
{
    echo mysqli_error()."<br>";
    echo "用户名或密码错误";
}
mysqli_free_result($result);
mysqli_close($conn);
if(isset($_SESSION["username"]))
{
?>
<meta http-equiv="refresh" content="1;url='reg.php'">   
<?php
}
?>