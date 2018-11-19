from flask import Flask
from config import  *
from flask_bootstrap import Bootstrap
import sys




def create_app():
    app = Flask(__name__)
    app.config.from_object(config)
    bootstrap = Bootstrap(app)

    from models import *

    db.init_app(app)
    db.app = app
    # sql()

    from views import test,MyResponse
    app.response_class = MyResponse

    app.register_blueprint(test)

    return  app

app = create_app()
if __name__ == '__main__':
    app.run('0.0.0.0',int(sys.argv[1]))
