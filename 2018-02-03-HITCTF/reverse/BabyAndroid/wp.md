丢进jeb分析之后是一个简单的亦或加密，解密脚本如下:

~~~python
key = "#$%$#!&#^_^~(:p@_*#######"
encrypt = "kmqgwg]Tm3=NE_/4ouKJW@WE^"
#flag = "HITCTF{w3lc0me_t0_hitctf}"
k = 0
result = ""
for i in range(len(key)):
	result += chr(ord(key[i])^ord(encrypt[i]))
print result
~~~

解得flag：HITCTF{w3lc0me_t0_hitctf}
