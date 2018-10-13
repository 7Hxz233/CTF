#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>

//gcc babynote.c -s -pie -fpic -o babynote

typedef struct note
{
    int size;
    char *content;
    void (*print)(struct note *);
}note;

note *list[3] = {NULL};
int note_num = 0;

void menu()
{
    puts("----------------------");
    puts("       BabyNote       ");
    puts("----------------------");
    puts(" 1. Add note          ");
    puts(" 2. Edit note         ");
    puts(" 3. Print note        ");
    puts(" 4. Delete note       ");
    puts(" 5. Exit              ");
    puts("----------------------");
    printf("Your choice :");
}

void delete()
{
    int index;
    char buf[4] = {0};
    printf("Input the index:");
    read(0,buf,4);
    index = atoi(buf);
    if(index < note_num && index >= 0)
    {
        free(list[index]->content);
        free(list[index]);
        puts("Success !");
    }
    else
    {
        puts("Invalid index!");
    }
}

void printfunc(note *n)
{
    puts(n->content);
}

void print()
{
    int index;
    char buf[4] = {0};
    printf("Input the index:");
    read(0,buf,4);
    index = atoi(buf);
    if(index < note_num && index >= 0)
    {
        (*list[index]->print)(list[index]);
    }
}

void edit()
{
    int index;
    char buf[4] = {0};
    printf("Input the index:");
    read(0,buf,4);
    index = atoi(buf);
    if(index < note_num && index >= 0)
    {
        printf("New content:");
        read(0,list[index]->content,list[index]->size);
        puts("Success !");
    }
    else
    {
        puts("Invalid index!");
    }
} 

void add(){
    int i;
    char buf[4] = {0};
    if(note_num < 3)
    {
        for(i = 0;i < 3;i++)
        {
            if(!list[i])
            {
                list[i] = (note *)malloc(sizeof(note));
                if(list[i] == NULL){
                    printf("malloc error!\n");
                    exit(-1);
                }
                printf("Content size:");
                read(0,buf,4);
                list[i]->size = atoi(buf);
                list[i]->content = (char *)malloc(sizeof(char) * list[i]->size);
                if(list[i]->content == NULL)
                {
                    printf("malloc error!\n");
                    exit(-1);
                }
                printf("Input the content:");
                read(0,list[i]->content,list[i]->size);
                list[i]->print = &printfunc;
                puts("Success !");
                note_num ++;
                break;
            }   
        }
    }
    else
    {
        puts("You can only use no more than 3 notes to pwn me!");
    }
}

void main()
{
    setvbuf(stdout,NULL,_IONBF,0);
    setvbuf(stdin,NULL,_IONBF,0);
    char buf[4]={0};
    int choice;
    while(1)
    {
        menu();
        read(0,buf,4);
        choice = atoi(buf);
        switch(choice)
        {
            case 1:
                add();
                break;
            case 2:
                edit();
                break;
            case 3:
                print();
                break;
            case 4:
                delete();
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
