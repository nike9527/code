from socket import *
from time import ctime
HOST = "::1"
PORT = 21567
BUFSIZE = 1024
ADDR = (HOST,PORT)

tcpCliSock =  socket(AF_INET6,SOCK_STREAM)
print('waiting for connection....')
tcpCliSock.connect(ADDR)
print('connection success....')
while True:
    data = input("> ")
    if not data:
        break
    tcpCliSock.send(data.encode())
    data = tcpCliSock.recv(BUFSIZE)
    if not data:
        break
    print(data.decode('utf-8'))
tcpCliSock.close()