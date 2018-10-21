#include <stdio.h>
#include <string.h>

const char table[64] = {"0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_"};

int main()
{
	int i = 0;
	int errCode = 0;
	char input[50];
	char flag[50] = {"!=^2%^%R^V2P9!H^U2)K''"};

	memset(input, '\0', 50);
	printf("input something to get flag:");
	errCode = read(0, input, 49);
	if (errCode < 0)
	{
		goto END;
	}
	
	printf("hitctf{");
	for (i = 0; i < strlen(input) - 2; ++i)
	{
		if ((flag[i] >> 1) + 16 != input[i])
		{
			errCode = -1;
			goto END;
		}
		printf("%c", table[flag[i] - 32]);
	}
	printf("}");
END:
	return errCode;
}