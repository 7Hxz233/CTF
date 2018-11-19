import sys
class config(object):
    SECRET_KEY = '1234567890123456789'
    SQLALCHEMY_DATABASE_URI = 'sqlite:///'+sys.path[0]+'/test.db'
    SQLALCHEMY_TRACK_MODIFICATIONS = True



