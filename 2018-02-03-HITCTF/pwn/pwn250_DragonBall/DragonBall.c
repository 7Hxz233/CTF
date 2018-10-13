#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>

//gcc DragonBall.c -fno-stack-protector -z execstack -o DragonBall

int money = 15;
int dragon_ball = 0;

int read_input_raw(char *buf,int max_len)
{
    int i=0;
    int sz_read;
    char tmp;
    for (i=0;i<max_len;i++)
    {
        sz_read = read(0,&tmp,1);
        if(sz_read == -1)
            exit(-1);
        if(sz_read == 0)
        {
            break;
        }
        if(sz_read != 1)
            exit(-1);
        if (tmp == '\n')
            break;
        buf[i] = tmp;
    }
    return i;
}

void menu()
{
    puts("----------------------");
    puts("     Dragon Ball     ");
    puts("----------------------");
    puts(" 1.Buy a dragon ball ");
    puts(" 2.Sell a dragon ball");
    puts(" 3.List your own      ");
    puts(" 4.Make a wish        ");
    puts(" 5.Exit               ");
    puts("----------------------");
    printf("You choice: ");
}

void buy()
{
    if((unsigned int)money > 0)
    {
        money -= 5;
        dragon_ball += 1;
        printf("Success!\n");
    }
    else
        printf("You don't have enough money.\n");
}

void sell()
{
    if(dragon_ball > 0)
    {
        dragon_ball -= 1;
        money += 3;
        printf("Success!\n");
    }
    else
        printf("You don't have enough dragon ball.\n");
}

void list()
{
    printf("Your dragon ball: %d\n", dragon_ball);
}

void wish()
{   
    char buf[0x60] = {0};   
    if(dragon_ball == 7)
    {
        printf("Tell me your wish: ");
        read_input_raw(&buf[0],0x68);
        printf("Your wish is %s, is it right?\n(Y/N) ", buf);
        read_input_raw(&buf[0x30],0x40);
        printf("OK.\n");
    }
    else
        printf("You can't make a wish.\n");
}

void main()
{
    char buf[4]={0};
    int choice;
    setbuf(stdin, NULL);
    setbuf(stdout, NULL);
    printf("You have 15 yuan in total, use them to collect 7 Dragon balls!\n\n");
    while(1)
    {
        menu();
        read(0,buf,4);
        choice = atoi(buf);
        switch(choice)
        {
            case 1:
                buy();
                break;
            case 2:
                sell();
                break;
            case 3:
                list();
                break;
            case 4:
                wish();
                break;
            case 5:
                exit(0);
                break;
            default:
                puts("Invalid choice!");
                break;
        }
    }
}
