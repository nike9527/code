from socket import *
import struct
import time
udp_sock = socket(AF_INET,SOCK_DGRAM)  #创建一个socket
#send_data = 0x01612E706E67006f6374657400
ip = ("192.168.1.10",69)
send_data = struct.pack("!H5sb5sb",1,b"a.png",0,b"octet",0)#将数据打包成二进制字符串
udp_sock.sendto(send_data,ip)#向服务器发送请求下载
while True:
    data,addr = udp_sock.recvfrom(1024)#接收服务端的数据
    data_len = len(data)
    send_data = struct.unpack("!HH",data[:4])#把二进制转换成数据
    if send_data[0] == 3:#3表示服务端发送过来的数据包
        if send_data[1] == 1:
            f = open("b.png","wb+")
        f.write(data[4:])#数据包前4个字节是操作码和块编号，之后的就是实际数据
        ack_data = struct.pack("!HH",4,send_data[1])#这里的4确认码  send_data[1]是服务端发过来的块编号
        udp_sock.sendto(ack_data,addr)#向服务发送数据表示确认收到数据
        if data_len<516:
            f.close()
            print("下载完成")
            break
    elif send_data[0] == 5:
        print("下载失败")
        break
udp_sock.close()