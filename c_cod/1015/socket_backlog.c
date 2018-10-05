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

static bool stop = false;
static void handle_term(int sig)
{
	stop = true;
}
int main(int argc,char * argv[])
{
	signal(SIGTERM,handle_term);  //设置处理信号函数
	if(argc <= 3)
	{
		printf("usage: %s ipaddress prot_number backlog\n",basename(argv[0]));
	}
	const char *ip = argv[1];
	int port = atoi(argv[2]);
	int backlog = atoi(argv[3]);
	int sock = socket(PF_INET,SOCK_STREAM,0);  //创建一个socket资源
	assert(sock >= 0);
	/*创建一个IPv4 socket 地址*/
	struct sockaddr_in address;  //socket地址结构
	bzero(&address,sizeof(address));
	address.sin_family = AF_INET;
	inet_pton(AF_INET,ip,&address.sin_addr);//ip地址转换
	address.sin_port = htons(port);
	int ret = bind(sock,(struct  sockaddr*)&address,sizeof(address));
	assert(ret != -1);
	ret = listen(sock,backlog);
	assert(ret != -1);
	sleep(20);
	struct sockaddr_in client;
	socklen_t client_addrlength = sizeof(client);
	int connfd = accept(sock,(struct sockaddr*)&client,&client_addrlength);
	if(connfd<0)
	{	
		printf("errno is %d\n", connfd);
	}else{
		/*打印连接成功的客户端IP和端口*/
		char remote[INET_ADDRSTRLEN];
		printf("connectd with ip %s and port %d\n", 
			inet_ntop(AF_INET,&client.sin_addr,remote,INET_ADDRSTRLEN),
			ntohs(client.sin_port)
			);
		close(connfd);
	}

	while(!stop)
	{
		sleep(1);
	}
	/*关闭socket*/
	close(sock);
	return 0;
}











