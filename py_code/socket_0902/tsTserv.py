from socket import *
from time import ctime
HOST = ""
PORT = 21567
BUFSIZE = 1024
ADDR = (HOST,PORT)
#tcpSerSock =  socket(AF_INET6,SOCK_STREAM)
tcpSerSock =  socket(AF_INET6,SOCK_STREAM)
tcpSerSock.bind(ADDR)
tcpSerSock.listen(5)
while True:
    print('waiting for connection....')
    tcpCliSocke,addr=tcpSerSock.accept()
    print('connected from :',addr)
    while True:
        data = tcpCliSocke.recv(BUFSIZE)
        if not data:
            break
        tcpCliSocke.send(('[%s] %s' % (str(bytes(ctime(),'utf-8')),data)).encode())
    tcpCliSocke.close()
tcpSerSock.close()