<?php
session_start();
?>
<html>
  <head>
    <meta http-equiv='Content-Type' content="text/html; charset=utf-8"/>
  </head>
  <body>
  <form method="post">
    用户名：<input type="text" name = "username">
    密码：<input type="text" name = "password">
    <input type="submit" value="登录">
  </form>
  <a href="reg.php">注册</a>
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
$strsql="select * from user where name = '$username' and password = '$pw'";
$result=$conn->query($strsql);
//$row=mysql_fetch_row($result);
$flag=false;
while($row=mysqli_fetch_row($result))
{
    echo $row[0].','.$row[1].','.$row[2]."<br>";
    $_SESSION["username"]=$row[1];
    $flag=true;
}

if(!$flag)
{
    echo mysqli_error($conn)."<br>";
    echo "用户名或密码错误";
}
mysqli_free_result($result);
mysqli_close($conn);
if($flag)
{
?>
<meta http-equiv="refresh" content="1;url='reg.php'">   
<?php
}
?>