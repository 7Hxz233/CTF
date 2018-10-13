#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>

typedef struct node{
    unsigned int value;
    char data[0x30];
    struct node *next;
}node;

char s[0x24];
int count = 0x00;
node *head = NULL;

void init()
{
    setbuf(stdin, NULL);
    setbuf(stdout, NULL);
    *(int *)&s[0x20] = 0x30;
}

void add()
{
    if(head == NULL){
        head = (node *)malloc(sizeof(node));
        printf("Value:");
        scanf("%u", &(head->value));
        printf("Data:");
        read(0, head->data, *(int *)&s[0x20]);
        head->next = NULL;
    }
    else{
        node *n = head;
        while(n->next != NULL)
        {
            n = n->next;
        }

        node *new = (node *)malloc(sizeof(node));
        printf("Value:");
        scanf("%u", &(new->value));
        printf("Data:");
        read(0, new->data, *(int *)&s[0x20]);
        new->next = NULL;
        n->next = new;
    }
    count ++;
}

void edit()
{
    unsigned int value;
    printf("Node's value:");
    scanf("%u",&value);

    node *n = head;
    while(n != NULL && n->value != value)
    {
        n = n->next;
    }

    if(n)
    {
        printf("New value:");
        scanf("%u", &(n->value));
        printf("New data:");
        read(0, n->data, *(int *)&s[0x20]);
    }
    else
    {
        printf("Invalid value.\n\n");
    }

}

void list()
{
    node *n = head;
    printf("Your nodes:\n");

    while(n != NULL)
    {
        printf("Value:%u\n", n->value);
        printf("Data:");
        puts(n->data);
        n = n->next;
    }
}

void main()
{
    int choice;

    init();
    while(count < 150){
        printf("please input your choice:");
        scanf("%d",&choice);
        getchar();

        if(choice == 1)
        {      
            add();
            sprintf(s, "You have already insert %d nodes", count);
            puts(s);
            puts("");
        }
        else if(choice == 2)
        {
            edit();
        }
        else if(choice == 3)
        {
            list();
        }
        else if(choice == 4){
            printf("Bye~\n");
            return;
        }
    }
    
    printf("Too much nodes, bye~\n");
}
