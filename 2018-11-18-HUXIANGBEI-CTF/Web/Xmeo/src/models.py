from flask_sqlalchemy import  SQLAlchemy
from flask_login import UserMixin,LoginManager

import  uuid
import os
import time

db = SQLAlchemy()

class User(UserMixin,db.Model):
    id = db.Column(db.Integer, primary_key=True)
    name = db.Column(db.String(80), nullable=False)
    password = db.Column(db.String(80),nullable=False)

    def __repr__(self):
        return '<User %r>' % self.name

class Contact(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    content = db.Column(db.String(100),nullable=False)
    # user_id = db.Column(db.Integer,db.ForeignKey('user.id'))
    # user = db.relationship('User',backref=db.backref('posts', lazy='dynamic'))
    def __repr__(self):
        return '<Contact %r>' % self.content

class TodoList(db.Model):
    __tablename__ = 'todolist'
    id = db.Column(db.Integer, primary_key=True)
    user_id = db.Column(db.String(80), nullable=False)
    name = db.Column(db.String(80), nullable=False)
    title = db.Column(db.String(1024), nullable=False)
    status = db.Column(db.Integer, nullable=False)
    create_time = db.Column(db.Integer, nullable=False)

    def __init__(self, user_id, name, title, status):
        self.user_id = user_id
        self.name = name
        self.title = title
        self.status = status
        self.create_time = time.time()


def sql():
    db.drop_all()
    db.create_all()
    db.session.add(User(name='admin', password='admin')) #str(uuid.uuid1())
    db.session.add(User(name='action', password=str(1)))
    db.session.commit()


