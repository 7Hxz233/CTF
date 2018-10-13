# PHPreading
1. 存在代码泄露，直接访问index.php.bak即可下载源码。
2. 源码如下：
```php
<?php 
	eval(base64_decode('JGZsYWc9JF9HRVRbJ2FzZGZnanh6a2FsbGdqODg1MiddO2lmKCRmbGFnPT0nSDFUY3RGMjAxOEV6Q1RGJyl7ZGllKCRmbGFnKTt9ZGllKCdlbW1tbScpOw=='))
?>
```
3.进行base64解码
```php
$flag=$_GET['asdfgjxzkallgj8852'];if($flag=='H1TctF2018EzCTF'){die($flag);}die('emmmm');
```
4.构造payload:url/?asdfgjxzkallgj8852=H1TctF2018EzCTF即可
