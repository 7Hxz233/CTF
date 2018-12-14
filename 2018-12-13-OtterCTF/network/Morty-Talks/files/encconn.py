import socket, pickle

def client():
    data = pickle.load(open(r"1.pc", 'rb'))
    KEY = open("key.txt", 'r').read()
    p = 0
    s = socket.socket()
    s.bind(("0.0.0.0", PORT))
    s.listen(0)
    s, addr = s.accept()
    for msg in data:
        res = KEY[p]
        for i in xrange(len(msg)):
            res+= chr(ord(msg[i])^ord(KEY[i % (p+1)]))
        s.send(res)
        KEY = KEY[:p+1] + s.recv(1) + KEY[p+1:]
        p += 2


client()
