#include <sys/socket.h>
#include <netinet/in.h>
#include <arpa/inet.h>
#include <signal.h>
#include <unistd.h>
#include <stdlib.h>
#include <assert.h>
#include <stdio.h>
#include <string.h>
#include <stdbool.h>

int main(int argc, char*argv[])
{
	if(argc <= 2)
	{
		printf("usage: %s ip_address port_number\n",basename(argv[0]) );
		return 1;
	}
	const char* ip = argv[1];
	int port = atoi (argv[2]);
	struct sockaddr_in server_address;
	bzero(&server_address,sizeof(server_address));
	server_address.sin_family = AF_INET;
	inet_pton(AF_INET,ip,&server_address.sin_addr);
	server_address.sin_port = htons(port);
	int sockefd = socket(PF_INET,SOCK_STREAM,0);
	assert(sockefd >= 0);
	socklen_t ser_add_len = sizeof(server_address);
	if(connect(sockefd,(struct sockaddr*)&server_address,ser_add_len)<0){
		printf("connection failed\n");
	}else{
		const char* oob_data = "abc";
		const char* normal_data = "123";
		send(sockefd,normal_data,strlen(normal_data),0);
		send(sockefd,oob_data,strlen(oob_data),MSG_OOB);
		send(sockefd,normal_data,strlen(normal_data),0);
	}

	close(sockefd);
	return 0;
}