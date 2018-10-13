#### [逆向] ReadAsm2  

#### Description:   

```
读汇编是逆向基本功。

给出的文件是func函数的汇编
main函数如下
输出的结果即为flag，格式为```flag{**********}```，请连flag{}一起提交

编译环境为linux gcc x86-64
调用约定为System V AMD64 ABI
**请不要利用汇编器，IDA等工具。。这里考的就是读汇编与推算汇编结果的能力**
```c
int main(int argc, char const *argv[])
{
  char input[] = {0x0,  0x67, 0x6e, 0x62, 0x63, 0x7e, 0x74, 0x62, 0x69, 0x6d,
                  0x55, 0x6a, 0x7f, 0x60, 0x51, 0x66, 0x63, 0x4e, 0x66, 0x7b,
                  0x71, 0x4a, 0x74, 0x76, 0x6b, 0x70, 0x79, 0x66 , 0x1c};
  func(input, 28);
  printf("%s\n",input+1);
  return 0;
}
```

参考资料:
 https://github.com/veficos/reverse-engineering-for-beginners 
《汇编语言》王爽
《C 反汇编与逆向分析技术揭秘》
```

#### Files:   

* http://ctf.nuptzj.cn//files/static/uploads/a480ff52cdbc70bd1443763f27f35279/2.asm  
