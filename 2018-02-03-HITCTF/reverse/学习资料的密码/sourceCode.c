#include<stdio.h>
#include<stdlib.h>
#include<string.h>
#define MAX_SIZE 50
const char* chart = "W3lc0me!";
char* basen(char * inps){
	char* result = NULL;
	int len = strlen(inps);
	result = (char*)calloc(sizeof(char), (len / 3 + 1) * 8);
	int i = 0;
	int stdLen = len - (len % 3);
	for (int j = 0; j < stdLen; j += 3){
		result[i++] = chart[(int)(inps[j] >> 5) & 0x07];
		result[i++] = chart[(int)((inps[j] & 0x1c) >> 2)];
		result[i++] = chart[(int)((inps[j] & 0x03) << 1 | (inps[j + 1] >> 7) & 0x01)];
		result[i++] = chart[(int)((inps[j + 1] & 0x70) >> 4)];
		result[i++] = chart[(int)((inps[j + 1] & 0x0E) >> 1)];
		result[i++] = chart[(int)((inps[j + 1] & 0x01) << 2 | (inps[j + 2] >> 6) & 0x03)];
		result[i++] = chart[(int)((inps[j + 2] & 0x38) >> 3)];
		result[i++] = chart[(int)(inps[j + 2] & 0x07)];
	}
	if (len % 3 == 0){
		result[i] = '\0';
	}
	else if (len % 3 == 1){
		result[i++] = chart[(int)(inps[len - 1] >> 5) & 0x07];
		result[i++] = chart[(int)((inps[len - 1] & 0x1c) >> 2)];
		result[i++] = chart[((int)(inps[len - 1] & 0x03) << 1)];
		result[i] = '\0';
	}
	else{
		result[i++] = chart[(int)(inps[len - 2] >> 5)];
		result[i++] = chart[(int)((inps[len - 2] & 0x1c) >> 2)];
		result[i++] = chart[(int)((inps[len - 2] & 0x03) << 1 | (inps[len - 1] >> 7) & 0x01)];
		result[i++] = chart[(int)((inps[len - 1] & 0x70) >> 4)];
		result[i++] = chart[(int)((inps[len - 1] & 0x0E) >> 1)];
		result[i++] = chart[(int)((inps[len - 1] & 0x01) << 2)];
		result[i] = '\0';
	}
	return result;
}
void myRead(char* inputs){
	int i = 0;
	inputs[i] = getchar();
	while (1){
		if (i >= 50) break;
		if (inputs[i] == '\n'){
			inputs[i] = '\0';
			return;
		}
		else{
			inputs[++i] = getchar();
		}
	}
}

int main(){
	char* inputs = NULL;
	char* res = NULL;
	FILE* f = fopen("encrypt", "r");
	char buffer[100] = { 0 };
	if (f == NULL){
		printf("Failed to open file encrypt!\n");
		system("pause");
		exit(1);
	}
	fgets(buffer, 100, f);
	printf("Give me your key:\n");
	inputs = (char*)calloc(sizeof(char), MAX_SIZE);
	myRead(inputs);
	res = basen(inputs);
	if (!strcmp(res, buffer)){
		printf("You've got my base :)\n");
	}
	else{
		printf("It's not that hard, try again! (:P\n");
	}
	system("pause");
	return 0;
}