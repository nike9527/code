from socketserver import  TCPServer as TCP,StreamRequestHandler as SRH
from time import ctime
HOST = '127.0.0.1'
PORT = 21567
ADDR = (HOST,PORT)
class MyRequestHandler(SRH):
    def handler(self):
        print(
            "...connected from",
            self.client_address,
            self.wfile.write(
                '[%s] %s' % (ctime(),self.rfile.readline())
            )
        )
tcpServ = TCP(ADDR,MyRequestHandler)
print("waiting for connection.....")
tcpServ.serve_forever()