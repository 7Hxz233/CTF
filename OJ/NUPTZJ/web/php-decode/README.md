#### [WEB] php decode  

#### Description:   

```
见到的一个类似编码的shell，请解码
```php
<?php
function CLsI($ZzvSWE) {
 
    $ZzvSWE = gzinflate(base64_decode($ZzvSWE));
 
    for ($i = 0; $i < strlen($ZzvSWE); $i++) {
 
        $ZzvSWE[$i] = chr(ord($ZzvSWE[$i]) - 1);
 
    }
 
    return $ZzvSWE;
 
}eval(CLsI("+7DnQGFmYVZ+eoGmlg0fd3puUoZ1fkppek1GdVZhQnJSSZq5aUImGNQBAA=="));?>
```
```

