1. 存在 SSRF 漏洞
2. 手动 fuzz 一下发现可以使用 file 协议读取文件
3. 读取 /etc/apache2/sites-enabled/000-default.conf 得到 web 目录为 /var/www/html/ssrf/web.php
4. 读取该文件发现，本地请求一下会执行一个叫 readflag 的二进制程序
5. 下载该二进制程序发现逻辑仅仅是读取当前文件夹下的 flag 文件
6. 通过 ssrf 读取即可
```
http://47.107.238.3/?url=file:///var/www/html/ssrf/flag
```
