#!/usr/bin/python
#-*- coding: UTF-8 -*-
from __future__ import unicode_literals
from flask_wtf import FlaskForm
from wtforms import RadioField, SubmitField, StringField, PasswordField
from wtforms.validators import DataRequired, Length, ValidationError
class TodoListForm(FlaskForm):
    title = StringField('标题', validators=[DataRequired(), Length(1, 250)])
    status = RadioField('是否完成', validators=[DataRequired()],  choices=[("1", '是'),("0",'否')])
    submit = SubmitField('提交')

    def validate_title(self, field):
        name = field.data
        if  'srcipt' in name.lower():
            raise ValidationError("validate data!")
