#### [WEB] pass check  

#### Description:   

```
核心源码
```php
<?php
$pass=@$_POST['pass'];
$pass1=***********;//被隐藏起来的密码
if(isset($pass))
{
if(@!strcmp($pass,$pass1)){
echo "flag:nctf{*}";
}else{
echo "the pass is wrong!";
}
}else{
echo "please input pass!";
}
?>
```
传送门：<a href="http://chinalover.sinaapp.com/web21/" target="_blank">题目地址</a>
```

