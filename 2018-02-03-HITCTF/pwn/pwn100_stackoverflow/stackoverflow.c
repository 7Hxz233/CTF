#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>

//gcc stackoverflow.c -fno-stack-protector -o stackoverflow

char *command = NULL;

void vuln();
void flag();
void CheckFailed();

void main()
{   
    setvbuf(stdout,NULL,_IONBF,0);
    setvbuf(stdin,NULL,_IONBF,0);
    vuln();
}

void vuln()
{
    char name[0x20];
    puts("Welcome to pwn world!\nLeave your name:");
    fflush(stdout);
    read(0,name,0x40);
    puts("bye~");
}

void flag(int var1, int var2)
{   
    if(var1 != 0xdeadbeef)
        CheckFailed();

    command = "cat flag";
    
    if(var2 != 0xc0ffee)
        CheckFailed();

    system(command);
}

void CheckFailed()
{
    printf("bad :(\n");
    exit(0);
}

