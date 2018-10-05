#!/usr/local/python3.6/bin/python3.6
from socket import *
from time import ctime
HOST = '127.0.0.1'
PORT = 21567
BUFFSIZE = 1024
ADDR = (HOST,PORT)

tcpSerSock = socket(AF_INET,SOCK_STREAM)
tcpSerSock.bind(ADDR)
tcpSerSock.listen(5)

while True:
    print("waiting for connecting....\r\n")
    tcpCliSock,addr = tcpSerSock.accept()
    print("connected from:",addr)
    print("\r\n")
    while True:
        data = tcpCliSock.recv(BUFFSIZE)
        if not data:
            break
        tcpCliSock.send(('[%s] %s' % (bytes(ctime(),'utf-8'),data)).encode())
        tcpCliSock.close()
tcpSerSock.close()