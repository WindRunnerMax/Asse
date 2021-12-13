from flask import session, redirect, url_for, abort, jsonify


def safe_session(key):
    if key in session:
        return session[key]
    else:
        # abort(redirect(url_for("index.home")))
        abort(jsonify(status=0, msg="System Hint"))


def read_session(key):
    if key in session:
        return session[key]
    else:
        return None
