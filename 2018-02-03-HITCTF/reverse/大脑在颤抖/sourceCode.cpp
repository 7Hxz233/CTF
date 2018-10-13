#include<stdlib.h>
#include<iostream>
#include<stdio.h>
#include<windows.h>
using namespace std;

#define MAX_CODE_LEN 1000
#define MAX_DATA_LEN 1000
void bfproc();
void BFMachine(char * bfdata);
int check(char* flag);
void int_process(char * code_in);
void(*g_funExceptionReturn)();
void(*g1_funExceptionReturn)();
void fakeSeh();
void decode();
EXCEPTION_DISPOSITION mySEH(EXCEPTION_RECORD              *pExceptionRecord,   // 异常状态描述
							EXCEPTION_REGISTRATION_RECORD *pEstablisherFrame,  // 异常注册框架
							CONTEXT                       *pContextRecord,     // 返回线程上下文
							PVOID                         pDispatcherContext // 分发器上下文（系统使用，无需关注）
							);
EXCEPTION_DISPOSITION mySEH1(EXCEPTION_RECORD              *pExceptionRecord,   // 异常状态描述
	EXCEPTION_REGISTRATION_RECORD *pEstablisherFrame,  // 异常注册框架
	CONTEXT                       *pContextRecord,     // 返回线程上下文
	PVOID                         pDispatcherContext // 分发器上下文（系统使用，无需关注）
);
EXCEPTION_DISPOSITION(*mSeh)(EXCEPTION_RECORD              *pExceptionRecord,   // 异常状态描述
	EXCEPTION_REGISTRATION_RECORD *pEstablisherFrame,  // 异常注册框架
	CONTEXT                       *pContextRecord,     // 返回线程上下文
	PVOID                         pDispatcherContext // 分发器上下文（系统使用，无需关注）
	) = mySEH;
EXCEPTION_DISPOSITION(*mSeh1)(EXCEPTION_RECORD              *pExceptionRecord,   // 异常状态描述
	EXCEPTION_REGISTRATION_RECORD *pEstablisherFrame,  // 异常注册框架
	CONTEXT                       *pContextRecord,     // 返回线程上下文
	PVOID                         pDispatcherContext // 分发器上下文（系统使用，无需关注）
	) = mySEH1;
char bfcode[MAX_CODE_LEN] = { 0 };
char data[MAX_DATA_LEN] = { 0 };
int res[26] = {0,0,0,0,84888, 83001, 109368, 87033, 97608, 75390, 149814, 135048, 91390, 138244, 60894, 110200, 115260, 151740, 68328, 128132, 137465, 129600, 147852, 143316, 170478, 135125};
char op1[] = { "TTTTTTTTTT$ATTTTTTTTTT$ATTTTTTTTTT$ATTTTTTTTTT$AAAAAAAAAARRRRRRRRCCCCCCCCCCR\"CR\"CR\"CR\"TTTTTTTTTT$ATTTTTTTTTT$ATTTTTTTTTT$AAAAAAAAAAARRRCCCCCCCCCCCR\"CR\"CR\"TTTTTTTTTT$ATTTTTTTTTT$AAAAAAAAAAAARRRRRRRRRCCCCCCCCCCCCR\"CR\"TTTTTTTTTT$AAAAAAAAAAAAARRRRRRRRCCCCCCCCCCCCCR\"" };
char op2[] = { "TTTTTTTTTT$ATTTTTTTTTT$ATTTTTTTTTT$ATTTTTTTTTT$AAAAAAAAAAATTTTTTCCCCCCCCCCCR\"CR\"CR\"CR\"TTTTTTTTTT$ATTTTTTTTTT$ATTTTTTTTTT$AAAAAAAAAAAATTTTTTCCCCCCCCCCCCR\"CR\"CR\"TTTTTTTTTT$ATTTTTTTTTT$AAAAAAAAAAAAATTTTTTTTCCCCCCCCCCCCCR\"CR\"TTTTTTTTTT$AAAAAAAAAAAAAATTTTTTCCCCCCCCCCCCCCR\"AAAAAAAAAAAAAATTTT" };
char op3[] = {"TTTTTTTTTT$ATTTTTTTTTT$ATTTTTTTTTT$ATTTTTTTTTT$AAAAAAAAAAAAATTCCCCCCCCCCCCCR\"CR\"CR\"CR\"TTTTTTTTTT$ATTTTTTTTTT$ATTTTTTTTTT$AAAAAAAAAAAAAATCCCCCCCCCCCCCCR\"CR\"CR\"TTTTTTTTTT$ATTTTTTTTTT$AAAAAAAAAAAAAAATTTTCCCCCCCCCCCCCCCR\"CR\"TTTTTTTTTT$AAAAAAAAAAAAAAAATTTTTTTCCCCCCCCCCCCCCCCR\""};
char op4[] = {"TTTTTTTTTT$ATTTTTTTTTT$ATTTTTTTTTT$ATTTTTTTTTT$AAAAAAAAAAAAAARRRRRRRRCCCCCCCCCCCCCCR\"CR\"CR\"CR\"TTTTTTTTTT$ATTTTTTTTTT$ATTTTTTTTTT$AAAAAAAAAAAAAAARRRRCCCCCCCCCCCCCCCR\"CR\"CR\"TTTTTTTTTT$ATTTTTTTTTT$AAAAAAAAAAAAAAAARRRCCCCCCCCCCCCCCCCR\"CR\""};
char op5[] = {"TTTTTTTTTT$ATTTTTTTTTT$ATTTTTTTTTT$ATTTTTTTTTT$AAAAAAAAAAAAAAATTTTTTTTCCCCCCCCCCCCCCCR\"CR\"CR\"CR\"TTTTTTTTTT$ATTTTTTTTTT$ATTTTTTTTTT$AAAAAAAAAAAAAAAATTTTTCCCCCCCCCCCCCCCCR\"CR\"CR\"TTTTTTTTTT$ATTTTTTTTTT$AAAAAAAAAAAAAAAAATTTTCCCCCCCCCCCCCCCCCR\"CR\"AAAAAAAAAAAAAAAAAATTTTTTTTTT"};
char op6[] = {"TTTTTTTTTT$ATTTTTTTTTT$ATTTTTTTTTT$ATTTTTTTTTT$AAAAAAAAAAAAAAAATCCCCCCCCCCCCCCCCR\"CR\"CR\"CR\"TTTTTTTTTT$ATTTTTTTTTT$ATTTTTTTTTT$AAAAAAAAAAAAAAAAATTTCCCCCCCCCCCCCCCCCR\"CR\"CR\"TTTTTTTTTT$ATTTTTTTTTT$AAAAAAAAAAAAAAAAAATTTTTTCCCCCCCCCCCCCCCCCCR\"CR\"TTTTTTTTTT$AAAAAAAAAAAAAAAAAAATTTTTTTTCCCCCCCCCCCCCCCCCCCR\"AAAAAAAAAAAAAAAAAAATTTT"};
char op7[] = {"TTTTTTTTTT$ATTTTTTTTTT$ATTTTTTTTTT$ATTTTTTTTTT$AAAAAAAAAAAAAAAAAARRRRRCCCCCCCCCCCCCCCCCCR\"CR\"CR\"CR\"TTTTTTTTTT$ATTTTTTTTTT$AAAAAAAAAAAAAAAAAAAARRRRCCCCCCCCCCCCCCCCCCCCR\"CR\""};
char op8[] = {"TTTTTTTTTT$ATTTTTTTTTT$ATTTTTTTTTT$AAAAAAAAAAAAAAAAAAAATTTCCCCCCCCCCCCCCCCCCCCR\"CR\"CR\"TTTTTTTTTT$ATTTTTTTTTT$AAAAAAAAAAAAAAAAAAAAATTTTTTTTTCCCCCCCCCCCCCCCCCCCCCR\"CR\"TTTTTTTTTT$AAAAAAAAAAAAAAAAAAAAAATTTTTTTTTCCCCCCCCCCCCCCCCCCCCCCR\"AAAAAAAAAAAAAAAAAAAAAATTTTTT"};
char op9[] = {"TTTTTTTTTT$ATTTTTTTTTT$ATTTTTTTTTT$ATTTTTTTTTT$AAAAAAAAAAAAAAAAAAAARCCCCCCCCCCCCCCCCCCCCR\"CR\"CR\"CR\"TTTTTTTTTT$ATTTTTTTTTT$ATTTTTTTTTT$AAAAAAAAAAAAAAAAAAAAARRRRCCCCCCCCCCCCCCCCCCCCCR\"CR\"CR\"TTTTTTTTTT$ATTTTTTTTTT$AAAAAAAAAAAAAAAAAAAAAARRRRRCCCCCCCCCCCCCCCCCCCCCCR\"CR\"TTTTTTTTTT$AAAAAAAAAAAAAAAAAAAAAAARRRRRRRRRCCCCCCCCCCCCCCCCCCCCCCCR\"AAAAAAAAAAAAAAAAAAAAAAARRRRRRR"};
char op10[] = {"TTTTTTTTTT$ATTTTTTTTTT$ATTTTTTTTTT$ATTTTTTTTTT$AAAAAAAAAAAAAAAAAAAAARRCCCCCCCCCCCCCCCCCCCCCR\"CR\"CR\"CR\"TTTTTTTTTT$ATTTTTTTTTT$ATTTTTTTTTT$AAAAAAAAAAAAAAAAAAAAAARCCCCCCCCCCCCCCCCCCCCCCR\"CR\"CR\"TTTTTTTTTT$ATTTTTTTTTT$AAAAAAAAAAAAAAAAAAAAAAARRRRRRCCCCCCCCCCCCCCCCCCCCCCCR\"CR\"TTTTTTTTTT$AAAAAAAAAAAAAAAAAAAAAAAARRRRCCCCCCCCCCCCCCCCCCCCCCCCR\"AAAAAAAAAAAAAAAAAAAAAAAARRRRRRRR"};
void BFMachine(char * bfdata){
	char* exec = bfcode;
	char* pdata = bfdata;
	int flags[2] = { 0, 0 };
	while (*exec){
		if (flags[0] || flags[1]){
			cout << "[-]Invalid Code,quit...." << endl;
			break;
		}
		switch (*exec){
		case '>':
			pdata++; break;
		case '<':
			pdata--; break;
		case '+':
			*pdata = *pdata + 1; break;
		case '-':
			*pdata = *pdata - 1; break;
		case '.':
			putchar(*pdata); break;
		case ',':
			cin >> pdata; break;
		case ']':
			//cout << (int)*pdata << endl;
			if (*pdata != 0){
				int c = 1;
				for (exec--; exec > bfcode && c; exec--){
					if (*exec == '[') c--;
					else if (*exec == ']') c++;
				}
			}
			break;
		case '[':
			if (*pdata == 0){
				int c = 1;
				for (exec++; exec < bfcode+MAX_CODE_LEN && c; exec++){
					if (*exec == '[') c++;
					else if (*exec == ']') c--;
				}
			}
			break;
		case '!':
			if (*(exec + 1) == '+'){
				*(int *)pdata += 1;
				(char *)pdata;
				exec++;
			}
			else if (*(exec + 1) == '-'){
				*(int *)pdata -= 1;
				(char *)pdata;
				exec++;
			}
			else{
				flags[0] = 1;
			}
			break;
		default:
			flags[0] = 1;
			break;
		}
		exec++;
	}
}

int check(char* flag){
	//22元一次方程组
	if (strlen(flag) > 22 || strlen(flag) <= 0){
		return 0;
	}
	int temp[22] = {0};
	int matrix[22][22] = { 
	{ 72, 66, 12, 54, 67, 60, 21, 19, 71, 67, 37, 39, 40, 39, 60, 49, 39, 61, 72, 94, 87, 53 },
	{ 68, 12, 70, 83, 78, 94, 12, 56, 81, 15, 96, 43, 24, 54, 29, 52, 47, 47, 44, 57, 65, 10 },
	{ 74, 88, 31, 25, 18, 99, 41, 32, 78, 80, 83, 53, 23, 90, 95, 89, 76, 19, 30, 52, 41, 85 },
	{ 94, 68, 96, 35, 63, 60, 34, 17, 58, 80, 70, 29, 11, 28, 65, 65, 98, 100, 82, 67, 35, 44 },
	{ 28, 74, 44, 37, 70, 12, 49, 98, 78, 56, 46, 33, 13, 46, 85, 36, 98, 49, 39, 74, 61, 36 },
	{ 13, 46, 72, 59, 53, 33, 34, 34, 64, 87, 90, 82, 22, 42, 84, 56, 17, 28, 85, 10, 47, 19 },
	{ 27, 97, 27, 74, 80, 48, 88, 18, 47, 97, 78, 17, 36, 39, 38, 100, 50, 23, 55, 49, 100, 30 },
	{ 83, 38, 38, 13, 87, 94, 43, 60, 81, 44, 82, 84, 79, 74, 73, 26, 14, 31, 79, 29, 75, 97 },
	{ 64, 48, 83, 97, 84, 46, 61, 48, 66, 21, 53, 13, 13, 89, 81, 63, 71, 81, 43, 89, 95, 97 },
	{ 14, 96, 40, 70, 49, 32, 79, 100, 75, 74, 31, 65, 75, 28, 72, 65, 64, 71, 39, 15, 57, 81 },
	{ 21, 28, 72, 88, 32, 25, 39, 97, 23, 79, 73, 32, 90, 12, 49, 62, 69, 94, 33, 62, 31, 83 },
	{ 83, 52, 85, 84, 33, 96, 74, 17, 46, 70, 84, 57, 20, 100, 50, 36, 34, 26, 41, 10, 28, 34 },
	{ 47, 18, 68, 49, 39, 70, 37, 50, 60, 58, 61, 70, 71, 67, 90, 45, 44, 37, 10, 46, 77, 16 },
	{ 82, 47, 39, 74, 17, 63, 74, 39, 71, 93, 85, 56, 79, 47, 17, 80, 51, 67, 89, 96, 76, 63 },
	{ 23, 96, 68, 29, 54, 34, 38, 28, 88, 16, 65, 25, 76, 67, 88, 97, 91, 14, 96, 73, 97, 51 },
	{ 88, 56, 58, 18, 80, 79, 34, 74, 51, 27, 78, 63, 38, 41, 54, 82, 70, 15, 73, 27, 40, 98 },
	{ 34, 98, 85, 77, 91, 50, 80, 75, 47, 56, 67, 11, 58, 40, 76, 91, 67, 95, 63, 48, 92, 46 },
	{ 43, 12, 41, 75, 65, 32, 79, 43, 12, 41, 75, 98, 64, 32, 99, 85, 93, 22, 21, 51, 76, 41 },
	{ 68, 85, 17, 64, 87, 52, 35, 99, 34, 66, 30, 54, 62, 38, 71, 15, 72, 97, 91, 80, 75, 40 },
	{ 11, 35, 85, 21, 91, 95, 46, 22, 36, 56, 55, 42, 100, 42, 76, 82, 21, 62, 80, 87, 85, 97 },
	{ 80, 80, 83, 83, 66, 99, 41, 90, 92, 79, 92, 14, 39, 22, 12, 48, 31, 83, 25, 94, 28, 72 },
	{ 62, 66, 37, 61, 39, 34, 41, 25, 28, 68, 41, 77, 46, 30, 28, 57, 61, 92, 89, 41, 31, 27 } };
	for (int i = 0; i < strlen(flag); i++){
		for (int j = 0; j < strlen(flag); j++){
			temp[i] += int(flag[i]) * matrix[i][j];
		}
		if (temp[i] != res[i+4]){
			return 0;
		}
	}
	return 1;
}

void int_process(char * code_in){
	int count = 0;
	for (int i = 0; i < MAX_CODE_LEN; i++){
		bfcode[i] = 0;
	}
	char realcode[MAX_CODE_LEN] = { 0 };
	for (int i = 0; i < strlen(code_in); i++)
	{
		switch (code_in[i]){
		case '>':
			for (int j = 0; j < 4; j++){
				bfcode[count + j] = '>';
			}
			count += 4;
			break;
		case '<':
			for (int j = 0; j < 4; j++){
				bfcode[count + j] = '<';
			}
			count += 4;
			break;
		case '+':
			bfcode[count] = '!';
			bfcode[count + 1] = '+';
			count += 2;
			break;
		case '-':
			bfcode[count] = '!';
			bfcode[count + 1] = '-';
			count += 2;
			break;
		default:
			bfcode[count++] = code_in[i];
		}
	}
}
void dxor(char* op){
	for (int i = 0; i < strlen(op); i++){
		op[i] = char(op[i] ^ 0x7F);
	}
}
void decode(){
	dxor(op1);
	dxor(op2);
	dxor(op3);
	dxor(op4);
	dxor(op5);
	dxor(op6);
	dxor(op7);
	dxor(op8);
	dxor(op9);
	dxor(op10);
	g1_funExceptionReturn();
}
EXCEPTION_DISPOSITION mySEH(EXCEPTION_RECORD              *pExceptionRecord,   // 异常状态描述
							EXCEPTION_REGISTRATION_RECORD *pEstablisherFrame,  // 异常注册框架
							CONTEXT                       *pContextRecord,     // 返回线程上下文
							PVOID                         pDispatcherContext // 分发器上下文（系统使用，无需关注）
){
	g_funExceptionReturn = (void(*)())(pContextRecord->Eip);//保存正常执行地址
	if (IsDebuggerPresent() == 0){
		pContextRecord->Eip = (DWORD)bfproc;
	}
	else{
		pContextRecord->Eip = (DWORD)fakeSeh;
	}
	return ExceptionContinueExecution;
}
EXCEPTION_DISPOSITION mySEH1(EXCEPTION_RECORD              *pExceptionRecord,   // 异常状态描述
	EXCEPTION_REGISTRATION_RECORD *pEstablisherFrame,  // 异常注册框架
	CONTEXT                       *pContextRecord,     // 返回线程上下文
	PVOID                         pDispatcherContext // 分发器上下文（系统使用，无需关注）
){
	g1_funExceptionReturn = (void(*)())(pContextRecord->Eip + 2);//保存正常执行地址(跳过除零错误)
	if (IsDebuggerPresent() == 0){
		pContextRecord->Eip = (DWORD)decode;
	}
	else{
		pContextRecord->Eip = (DWORD)fakeSeh;
	}
	return ExceptionContinueExecution;
}
void fakeSeh(){
	__asm pop dword ptr fs : [0];
	g_funExceptionReturn();
}
void bfproc(){
	__asm{
		pop dword ptr fs : [0];//卸载上一个seh
		push mSeh1//安装新的seh
		push fs : [0]
		mov fs : [0], esp
		xor eax, eax
		div eax
	}
	int_process(op1);
	BFMachine((char *)res);
	int_process(op2);
	BFMachine((char *)res);
	int_process(op3);
	BFMachine((char *)res);
	int_process(op4);
	BFMachine((char *)res);
	int_process(op5);
	BFMachine((char *)res);
	int_process(op6);
	BFMachine((char *)res);
	int_process(op7);
	BFMachine((char *)res);
	int_process(op8);
	BFMachine((char *)res);
	int_process(op9);
	BFMachine((char *)res);
	int_process(op10);
	BFMachine((char *)res);
	g_funExceptionReturn();
}


int main(){
	__asm{
		//添加SEH处理函数，触发除零错误
		push mSeh
		push fs:[0]
		mov fs:[0],esp
		xor eax,eax
		div eax
	}
	cout << "Welcome to HITCTF2017 :)" << endl;
	cout << "This is a crackme game, flag is the key!" << endl;
	cout << "Plz input your key :" << endl;

	char input[23];
	cin.getline(input,23);
	if (check(input)){
		cout << "Congz you got me :D !" << endl;
	}
	else{
		cout<<"Try harder XD!" << endl;
	}
	system("pause");
	ExitProcess(0);
	return 0;
}