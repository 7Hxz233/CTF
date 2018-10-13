from flask import Flask, request, render_template
from handle_database import *
import graphene

app = Flask(__name__)

@app.route('/')
def index():
    return render_template('index.html')

@app.route('/graphql', methods=['GET', 'POST'])
def graphql():
    if request.method == 'POST':
        # query = '''
        # query { getscorebyid(id: "GE======"){ name score } }
        # '''
        query = request.form['query']
        my_schema = graphene.Schema(query = Query)
        result = my_schema.execute(query)
        if result.errors == None:
            return str(result.data)
        else:
            return str(result.errors)
    else:
        return "GraphQL is good"

if __name__ == "__main__":
    app.run(host = "0.0.0.0", port = 80)