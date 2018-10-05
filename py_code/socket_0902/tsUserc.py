from socket import *
from time import ctime
HOST = ''
PORT = 21568
BUFSIZE = 1024
ADDR = (HOST,PORT)

updSerSock = socket(AF_INET,SOCK_DGRAM)
updSerSock.bind(ADDR)
while True:
     print("waiting for message....")
     data,addr = updSerSock.recvfrom(BUFSIZE)
     updSerSock.sendto(('[%s] %s' % (str(ctime()),data)).encode(),addr)
     #updSerSock.sendto((getservbyname('UPD')).encode(), addr)
     print("....received from and returned to:",addr)
updSerSock.close()