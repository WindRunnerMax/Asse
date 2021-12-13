@celery.task(bind=True)
def run(self):
    # http://www.pythondoc.com/flask-celery/first.html
    db_session = sessionmaker(bind=engine)  # 构造session会话
    db_session_instance = db_session()  # 创建session
    db_session_instance.commit()
    db_session_instance.close()
    return { "status": 1, "msg": "finish" }
