from flask import abort, jsonify


def safe_params(value):
    if value is None:
        abort(jsonify(status="0", msg="System Hint"))
    else:
        return value
