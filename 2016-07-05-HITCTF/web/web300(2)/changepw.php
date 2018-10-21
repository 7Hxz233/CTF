<?php
session_start();
?>
<html>
  <head>
    <meta http-equiv='Content-Type' content="text/html; charset=utf-8"/>
  </head>
  <body>
  <form method="post">
    旧密码：<input type="text" name = "password1">
    新密码：<input type="text" name = "password2">
    <input type="submit" value="修改">
  </form>
  <a href="logout.php">注销</a><br>
  <a href="reg.php">注册</a><br>
  <a href="login.php">登录</a><br>
  </body>
</html>
<?php
function filter($str){
    $str=addslashes($str);
    return $str;
}
if(!isset($_POST["password1"])||!isset($_POST["password2"]))
    exit();
include "db.php";
$pw1=filter($_POST["password1"]);
$pw2=filter($_POST["password2"]);
$strsql="select * from user where name = '".$_SESSION['username']."' and password = '".$pw1."'";
$result=$conn->query($strsql) or die(mysqli_error($conn));
if(mysqli_affected_rows($conn)==0)
    die("密码错误");
else
$strsql="update user set password = '".$pw2."' where name = '".$_SESSION['username']."'";
$result=$conn->query($strsql);
//$row=mysql_fetch_row($result);
$flag=false;
echo "修改成功";
mysqli_close($conn);
?>