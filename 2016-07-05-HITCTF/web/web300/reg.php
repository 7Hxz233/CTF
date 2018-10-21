<html>
  <style>
  .line_title {
      display:inline-block;
      margin-left:20px;
      font-size:1.5em;
  }
  </style>
  <head>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
    <title>注册</title>
  </head>
  <body>
    <form action="reg.php" method = "post">
      <div><div class ="line_title">用户名：</div><input type = "text" class = "form-control" name = "username" style = "display:inline-block;width:200px"></div>
      <div><div class = "line_title">密码：</div><input type =  "password" class = "form-control" name = "password" style = "display:inline-block;width:200px"></div>
      <input type = "submit" class ="btn" style = "margin-left:20px" value="注册">
      <a href="index.php">去登录</a>
    </form>
  
  </body>
<?php
if (isset($_POST["username"]) &&$_POST["username"] == "admin") {
    echo "不要重复注册！！！！";
}
if (isset($_POST["password"]) && $_POST["password"] != "") {
  if (strlen($_POST["password"])>4||preg_match("/[^\d]/",$_POST["password"]))
  {
    echo "密码太长太难我们怕你记不住，只需4位数字";
  } else {
    echo "网络错误";
  }
}
?>
</html>