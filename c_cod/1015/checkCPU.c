#include<stdio.h>
void byteorder()
{
	union{
		short value;
		char union_bytes[sizeof(short)];
	} test;
	test.value = 0x0102;
	if((test.union_bytes[0]==1) && (test.union_bytes[1]==2))
	{
		printf("big endian!\n");
	}else if((test.union_bytes[0]==2) &&(test.union_bytes[1]==1) ){
		printf("little endian\n");
	}else{
		printf("unknown..\n");
	}
	printf("&test = %X test = %d\n",&test,sizeof(test));
	printf("size_shor = %d size_char = %d\n",sizeof(short),sizeof(char));
	printf("&   =  %X\n",&(test.value));
	printf("&[0] = %X  value = %d\n", &test.union_bytes[0],test.union_bytes[0]);
	printf("&[1] = %X  value = %d\n", &test.union_bytes[1],test.union_bytes[1]);
	
}

int main()
{
	byteorder();
	return 0;
}
