from flask import Blueprint

task = Blueprint("task", __name__)

"""
@ path task
"""


@use_data.route("/update/", methods=["POST"])
def update_data(start, end, qid):
    task = run.delay('''params''')
    task_id = task.id
    return { "status": 1, "taskId": task.id }



@task.route("/revoketask/<tid>", methods=["POST"])
def revoketask(tid):
    # https://juejin.im/entry/6844903477349449736
    from celery_app import celery
    from celery.app.control import Control
    celery_control = Control(celery)
    celery_control.revoke(tid, terminate=True)
    return { "status": 1 }
