from app.index.index import index


class Router(object):
    """docstring for Router"""

    def __init__(self, app):
        super(Router, self).__init__()
        self.app = app

    def register(self):
        self.app.register_blueprint(index, url_prefix="/")

