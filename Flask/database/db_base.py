from flask_sqlalchemy import SQLAlchemy
from config import SQLALCHEMY_DATABASE_URI, DEBUG, SQLALCHEMY_POOL_PRE_PING, SQLALCHEMY_POOL_RECYCLE, \
    SQLALCHEMY_POOL_SIZE
from sqlalchemy import create_engine

db = SQLAlchemy()
engine = create_engine(
    SQLALCHEMY_DATABASE_URI,
    echo=DEBUG,
    pool_recycle=SQLALCHEMY_POOL_RECYCLE,
    pool_size=SQLALCHEMY_POOL_SIZE,
    pool_pre_ping=SQLALCHEMY_POOL_PRE_PING)

# https://www.cnblogs.com/lesliexong/p/8654615.html
