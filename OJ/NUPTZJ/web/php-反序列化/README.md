#### [WEB] php 反序列化  

#### Description:   

```
http://115.28.150.176/php1/index.php
代码：
```php
<?php
class just4fun {
    var $enter;
    var $secret;
}

if (isset($_GET['pass'])) {
    $pass = $_GET['pass'];
    
    if(get_magic_quotes_gpc()){
        $pass=stripslashes($pass);
    }
    
    $o = unserialize($pass);
    
    if ($o) {
        $o->secret = "*";
        if ($o->secret === $o->enter)
            echo "Congratulation! Here is my secret: ".$o->secret;
        else 
            echo "Oh no... You can't fool me";
    }
    else echo "are you trolling?";
}
?>
```
```

