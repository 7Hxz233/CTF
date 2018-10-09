#### [WEB] md5 collision  

#### Description:   

```
源码
```php
<?php
$md51 = md5('QNKCDZO');
$a = @$_GET['a'];
$md52 = @md5($a);
if(isset($a)){
if ($a != 'QNKCDZO' && $md51 == $md52) {
    echo "nctf{*****************}";
} else {
    echo "false!!!";
}}
else{echo "please input a";}
?>
```
传送门：<a href="http://chinalover.sinaapp.com/web19/" target="_blank">题目地址</a>
```

