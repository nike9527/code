from socket import *
from time import ctime
HOST = '127.0.0.1'
PORT = 21568
BUFSIZE = 1024
ADDR = (HOST,PORT)

updCliSock = socket(AF_INET,SOCK_DGRAM)
while True:
    data = input('>')
    if not data:
        break
    updCliSock.sendto(data.encode(),ADDR)
    data,ADDR=updCliSock.recvfrom(BUFSIZE)
    if not data:
        break
    print(data.decode())
updCliSock.close()