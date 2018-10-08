import socket
sk = socket.socket()
ip_port = ('127.0.0.1',9999)
sk.bind(ip_port)
sk.listen(5)
while True:
    conn,address =sk.accept()
    while True:
        with open("meinv.jpg","ab") as f:
            data = conn.recv(1024)
            if data == b'quit':
                break
            f.write(data)
            conn.send('success'.encode())
    print("数据接收完成")
sk.close()