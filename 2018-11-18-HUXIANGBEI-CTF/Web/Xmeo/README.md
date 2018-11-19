1. 注册登录之后创建一个 TODO Item 名称处可以 SSTI
2. 然后通过 SSTI 可以执行命令
3. 发现执行命令为 root 权限，读取 /home/Xmeo/auto.js 即可获取 flag
```
 page.addCookie({
    'name'     : 'flag',
    'value'    : 'hxb2018{9a6c2dfd3c22c8928f544c0b576e100f}',
    'domain'   : '127.0.0.1',
    'path'     : '/admin/mysecrecy_directory',
    'httponly' : false,
    'secure'   : false,
    'expires'  : (new Date()).getTime() + (1000 * 60 * 60)
  });
```
