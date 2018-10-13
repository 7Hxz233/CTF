from flask import Flask, request, jsonify, render_template
from Crypto.Cipher import AES
from binascii import b2a_hex, a2b_hex
import os

app = Flask(__name__)
flag_key=os.environ['KEY']
flag_enc='9cf742955633f38d9c628bc9a9f98db042c6e4273a99944bc4cd150a0f7b9f317f52030329729ccf80798690667a0add'


@app.route('/')
def index():
    return render_template('index.html',flag_enc=flag_enc)
@app.route('/getflag',methods=['POST'])
def getflag():
    req = request.json
    if not req:
        return jsonify(result=False)
    if 'key' not in req:
        return jsonify(result=False)
    key=req['key']
    if len(key)!=len(flag_key):
        return jsonify(result=False)
    for x,y in zip(key,flag_key):
        if ord(x)^ord(y):
            return jsonify(result=False)
    cryptor = AES.new(key, AES.MODE_CBC, b'0000000000000000')
    plain_text = cryptor.decrypt(a2b_hex(flag_enc))
    flag=plain_text.decode('utf-8').strip()
    return jsonify(result=True,flag=flag)


if __name__ == '__main__':
    app.run()
