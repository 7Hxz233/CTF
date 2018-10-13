import graphene
import sqlite3
import os 
import base64

this_dir = os.path.dirname(__file__)
database_dir = this_dir + '/web-sqlinjection.db'
conn = sqlite3.connect(database_dir)
c = conn.cursor()

# vulnerable Field
class Getscorebyname(graphene.ObjectType):
    score = graphene.String()
    name = graphene.String()
    userid = graphene.String()

    def resolve_score(self, info):
        username = info.operation.selection_set.selections[0].arguments[0].value.value
        username = base64.b32decode(username)
        sql = "select score from users where name='%s'" % username
        result = c.execute(sql)
        out = ''
        for row in result:
            out += str(row[0])
        return str(out)

    def resolve_name(self, info):
        username = info.operation.selection_set.selections[0].arguments[0].value.value
        return base64.b32decode(username)

class Getscorebyid(graphene.ObjectType):
    score = graphene.String()
    name = graphene.String()
    id = graphene.String()

    def resolve_score(self, info):
        userid = info.operation.selection_set.selections[0].arguments[0].value.value
        userid = base64.b32decode(userid)
        sql = "select score from users where id= ? "
        result = c.execute(sql, userid)
        out = ''
        for row in result:
            out += str(row[0])
        return str(out)
    def resolve_id(self, info):
        userid = info.operation.selection_set.selections[0].arguments[0].value.value
        userid = base64.b32decode(userid)
        return userid
    def resolve_name(self, info):
        userid = info.operation.selection_set.selections[0].arguments[0].value.value
        userid = base64.b32decode(userid)
        sql = "select name from users where id= ? "
        result = c.execute(sql, userid)
        out = ''
        for row in result:
            out += str(row[0])
        return str(out)
        

class Query(graphene.ObjectType):
    getscorebyyourname = graphene.Field(Getscorebyname,name=graphene.Argument(graphene.String))
    getscorebyid = graphene.Field(Getscorebyid,id=graphene.Argument(graphene.String))

    def resolve_getscorebyyourname(self, info, name):
        return name
    def resolve_getscorebyid(self, info, id):
        return id

    
my_schema = graphene.Schema(query = Query)

# query = '''
# query { getscorebyid(id: "GE======"){ id name score } }
# '''
# result = my_schema.execute(query)
# print result.data
# print result.errors

# query = '''
# query { __schema { queryType{ fields { name } } } }
# '''
