from celery import Celery
from config import CELERY_BROKER_URL, CELERY_RESULT_BACKEND

task = []
celery = Celery(__name__, broker=CELERY_BROKER_URL, backend=CELERY_RESULT_BACKEND, include=task)
