<?php
session_start();
$a = rand(1,100);
$b = rand(1,2);
$c = rand(1,100);
$f1 = 0;
    if (isset($_POST["captcha"])) {
        if ($_POST["captcha"]==$_SESSION["captcha"]) {
            if (isset($_POST["password"]) && isset($_POST["username"])) {
                if ($_POST["password"] == "6384" && $_POST["username"]=="admin")
                    $f1 = 1;
                else {
                    $f1 = 2;
                }
            }
        } else {
            $f1 = 3;
        }
    }
?>
<style>
  .line_title {
      display:inline-block;
      margin-left:20px;
      font-size:1.5em;
  }
</style>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <title>登录</title>
  </head>
  <body>
    <form action="index.php" method = "post">
      <div><div class="line_title">用户名：</div><input type = "text" class = "form-control" style = "display:inline-block;width:200px"name = "username"></div>
      <div><div class="line_title">密<div style="width:1em;display:inline-block"></div>码：</div><input type = "password" class = "form-control" style = "display:inline-block;width:200px" name = "password"></div>
      <div><div class="line_title">
        <?php
          echo $a;
          if ($b == 1) {
              echo "+";
              $_SESSION["captcha"] = $a+$c;
          } else {
              echo "*";
              $_SESSION["captcha"] = $a*$c;
          }
          echo $c;
        ?>
      =?&nbsp</div><input type =  "text" class = "form-control" name = "captcha" style = "display:inline-block;width:200px"></div>
      <input style = "margin-left:20px" type = "submit" class ="btn" value="登录">
      <a href="reg.php">去注册</a>
    </form>
  <?php
    if ($f1 == 1)
        echo "hitctf{I_Wi1l_iMprO_v3_M4_pw}";
    else if ($f1 == 2)
        echo "用户名或密码错误";
    else if ($f1 == 3)
        echo "验证码错误";
  ?>
  </body>
</html>