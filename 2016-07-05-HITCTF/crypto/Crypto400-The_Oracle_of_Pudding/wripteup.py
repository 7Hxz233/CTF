#!/usr/bin/env python
from zio import *
import base64
import os

#io = zio('./oracle.rb',  print_read=False)#,  print_write=False)
io = zio(('202.118.236.190', 5001), print_read=False)

io.readline()
io.readline()
str_cipher = io.readline()
bin_cipher = base64.decodestring(str_cipher)
io.readline()

c1 = bin_cipher[0:16:1]
c2 = bin_cipher[16:32:1]
c3 = bin_cipher[32:48:1]

i2 = [None] * 16
i3 = [None] * 16
p2 = [None] * 16
p3 = [None] * 16

times = 0

for i in xrange(16):
	for j in xrange(256):
		times += 1
		if i == 0:
			cf = []
		else:
			cf = map(lambda x: chr(x ^ (i+1)), i3[-i:])
		c = os.urandom(15 - i) + chr(j) + ''.join(cf)
		challenge = base64.encodestring(''.join([c1, c, c3]))
		io.readline()
		io.write(challenge)
		ret = io.readline()
		if ret[0:5] != "Error":
			i3[15 - i] = (i + 1) ^ j
			p3[15 - i] = ord(c2[15 - i]) ^ i3[15 - i]
			break
		else:
			pass

		if j == 255:
			print "Missing!"
			exit()

for i in xrange(16):
	for j in xrange(256):
		times += 1
		if i == 0:
			cf = []
		else:
			cf = map(lambda x: chr(x ^ (i+1)), i2[-i:])
		c = os.urandom(15 - i) + chr(j) + ''.join(cf)
		challenge = base64.encodestring(''.join([c, c2]))
		io.readline()
		io.write(challenge)
		ret = io.readline()
		if ret[0:5] != "Error":
			i2[15 - i] = (i + 1) ^ j
			p2[15 - i] = ord(c1[15 - i]) ^ i2[15 - i]
			break
		else:
			pass

		if j == 255:
			print "Missing!"
			exit()

print ''.join(map(chr, p2))
print ''.join(map(chr, p3))
print '-' * 20
print "total ", times, " times"
