from socket import *
from threading import Thread
sock = socket(AF_INET,SOCK_DGRAM)
sock.bind(("",8001))
def sendMsg():
    while True:
        sendData = input("<<:")
        sock.sendto(sendData.encode("utf-8"),("192.168.1.9",8000))
        print(">>%s:%s" % (sendData[1], sendData[0]))
def recvMsg():
    while True:
        recvData = sock.recvfrom(1024)
        print("\r\n>>%s:%s" % (recvData[1], recvData[0]))
def main():
    tr = Thread(target=sendMsg)
    ts = Thread(target=recvMsg)
    tr.start()
    ts.start()
    tr.join()
    ts.join()

if __name__ == "__main__":
    main()