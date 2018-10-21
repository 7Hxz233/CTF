<!--
Author: W3layouts
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE html>
<?php
$flag = false;
$flag2 = false;
foreach ($_POST as $keyword => $value){
    if ($keyword == "Username") {
        $flag = true;
    } else if ($keyword == "Password") {
        $flag2 = true;
    }
    
}

if ($flag && $flag2) {
    $con = mysqli_connect("localhost", "web_user2", "79007295","web1");#修改这一行
    if (!$con)
    {
        die('Could not connect: ' . mysql_error());
    }
    $sql = "select * from user where pw = '".$_POST["Password"]."' and name = '".$_POST["Username"]."'";
    $re = mysqli_query($con, $sql);
    
    if (mysqli_num_rows($re)>0) {
        while ($row = mysqli_fetch_array($re)) {
                die("Welcome ".$row[1]);
        }
    } else {
        die("用户名或密码错误");
    }
    
    mysql_close($con);
}
?>
<html>
<head>
<title>Please login...</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>

<link href="css/style.css" rel='stylesheet' type='text/css' />
<!--webfonts-->

<!--/webfonts-->
</head>
<body>
<!--start-main-->
<h1>Welcome!<span>Please login...</span></h1>
<div class="login-box">
		<form action = "index.php?" method = "post">
			<input type="text" class="text" name = "Username" value="Username"  onblur="if (this.value == '') {this.value = 'Username';}" >
			<input type="password" name = "Password" value="Password" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Password';}">
		
            <div class="remember">
                <a href="#"><p>Remember me</p></a>
                <h4>Forgot your password?<a href="#">Click here.</a></h4>
            </div>
            <div class="clear"> </div>
            <div class="btn">
                <input type="submit" value="LOG IN" >
            </div>
        </form>
		<div class="clear"> </div>
</div>
<!--//End-login-form-->
<!--start-copyright-->
<div class="copy-right">
	<p>HITCTF</a></p> 
</div>
<!--//end-copyright-->		
</body>
</html>