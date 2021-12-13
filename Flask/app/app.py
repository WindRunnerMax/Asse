import os
import config
from flask import Flask
from router import Router
from db_base import db


if __name__ == "__main__":
    app = Flask(__name__)
    app.config.from_object(config)
    app.config["SECRET_KEY"] = os.urandom(24)
    db.init_app(app)
    db.create_all(app=app)
    Router(app).register()
    app.run(host="0.0.0.0", port="3000")
