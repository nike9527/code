#! /usr/local/python3.6/bin/python3.6
from socket import *
from time import ctime
import time
HOST = ''
PORT = 21567
BUFSIZ = 1024
ADDR = (HOST, PORT)
tcpSerSock = socket(AF_INET, SOCK_STREAM)
tcpSerSock.bind(ADDR)
tcpSerSock.listen(5)
tcpSerSock.setblocking(False)
while True:
    print('waiting for connection...')
    try:
        tcpCliSock, addr = tcpSerSock.accept()
        print('...connected from:', addr)
        while True:
            data = tcpCliSock.recv(BUFSIZ)
            if not data:
                break
            tcpCliSock.send(('[%s] %s' % (bytes(ctime(), 'utf-8'), data)).encode())
        tcpCliSock.close()
    except:
        time.sleep(1)
tcpSerSock.close()

