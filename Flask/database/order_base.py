class OrderBase(object):
    """docstring for OrderBase"""

    @staticmethod
    def get_columns():
        from sqlalchemy.dialects.mysql import INTEGER
        from sqlalchemy import Column, DECIMAL, DateTime
        return [
            Column("datetime", DateTime, primary_key=True),
            Column("price", DECIMAL(10, 3), nullable=False),
        ]

    __model_mapper = {}

    @staticmethod
    def get_split_model(name):
        from sqlalchemy import Table
        from sqlalchemy.orm import mapper
        from db_base import engine
        cid = name.lower()
        if cid in OrderBase.__model_mapper:
            return OrderBase.__model_mapper[cid]
        table_name = "order_{}".format(cid)
        table = Table(table_name, OrderBase.metadata, *OrderBase.get_columns(), extend_existing=True)
        if not table.exists(bind=engine):
            table.create(bind=engine, checkfirst=True)
        model = type("O{}".format(cid), (db.Model,), {"__tablename__": table_name})
        mapper(model, table, non_primary=True)
        OrderBase.__model_mapper[cid] = model
        return model
