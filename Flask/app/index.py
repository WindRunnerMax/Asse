from flask import Blueprint
from flask import render_template

index = Blueprint("index", __name__)

"""
@ path /
"""


@index.route("/", methods=["GET"])
def home():
    return render_template("index.html")
