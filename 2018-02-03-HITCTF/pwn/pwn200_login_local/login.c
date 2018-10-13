#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>
#include <string.h>

char username[17] = {0};
char password[33] = {0};

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

int check()
{
    int flag = 0;
    
    if(!strncmp(username,"root",4))
    {
        puts("Correct username!");
        flag++;
    }
    else    
        puts("Incorrect username!");
    
    if(!strncmp(password,"passwd_has_be_changed_in_remote_",32))
    {
        puts("Correct password!");
        flag++;
    }
    else
        puts("Incorrect password!");
        
    return flag;
}

int login()
{
    int level = 0xff;
    printf("Username: ");
    int len1 = read_input_raw(username,16);
    printf("Password: ");
    int len2 = read_input_raw(password,32);
    
    if(!strncmp(username,"root",len1) && !strncmp(password,"passwd_has_be_changed_in_remote_",len2))
    {
        level = 0x00;
    }
    else if(!strncmp(username,"lilac",len1) && !strncmp(password,"a_password_you_know_uh_huh_^_^@!",len2))
    {
        level = 0x10;
    }
    return level;
}

void main()
{
    setvbuf(stdout,NULL,_IONBF,0);
    setvbuf(stdin,NULL,_IONBF,0);
    puts("Welcome to 7HxzZ login system!");

    int level = login();
    
    if(level == 0x00)
    {
        printf("How can you login successful as root!\nThere must be something wrong with the login function,let me check again!\n");
        puts("Checking......");
        if(check() < 2)
        {
            puts("Don't fool me, you are not the true root user!");
            exit(0);
        }
        else
        {
            printf("This is your flag: ");
            system("cat flag");
        }
        
    }
    else if(level == 0x10)
    {
        printf("Login successful\nBut you have no permission to get the flag!\n");
        exit(0);
    }
    else
    {
        printf("Login failed!\n");
        exit(0);
    }
}
