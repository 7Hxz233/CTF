#!/usr/bin/env python
from zio import *
import base64
import os

#io = zio('./oracle.rb',  print_read=False)#,  print_write=False)
io = zio(('202.118.236.190', 7621), print_read=False) 

io.readline()
io.readline()
str_cipher = io.readline()
bin_cipher = base64.decodestring(str_cipher)
io.readline()

c1 = bin_cipher[0:16:1]
c2 = bin_cipher[16:32:1]
c3 = bin_cipher[32:48:1]
c4 = bin_cipher[48:64:1]

challenge1 = base64.encodestring(''.join(["0", c1[1:], c2, c3, c4])).replace("\n", '')
challenge2 = base64.encodestring(''.join([c1, c2, "0", c3[1:], c4])).replace("\n", '')

io.readline()
io.write(challenge1 + "\n")
ret1 = io.readline()
io.readline()
io.write(challenge2 + "\n")
ret2 = io.readline()
print base64.decodestring(ret2)
print base64.decodestring(ret1)
print base64.decodestring(ret2)[0:32] + base64.decodestring(ret1)[32:]
