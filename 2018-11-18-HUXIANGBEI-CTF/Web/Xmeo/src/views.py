from flask import Flask, render_template,request,Blueprint,session,url_for,flash,redirect,make_response,render_template_string,Response
from functools import wraps
from werkzeug.datastructures import Headers
from models import User,Contact,TodoList,db
from uuid import uuid1
from forms import TodoListForm
test = Blueprint('test',__name__)
class MyResponse(Response):
    """docstring for MyResponse"""
    def __init__(self, response, **kwargs):
        kwargs['headers'] =  Headers([('Content-Security-Policy',"script-src 'self';")])
        return super(MyResponse, self).__init__(response, **kwargs)

        
def logintest(f):
    @wraps(f)
    def tmp():

        if session.has_key('username'):
            return f()
        else:
            return render_template('signin.html')
    return tmp

def admin_logintest(f):
    @wraps(f)
    def tmp():

        if session.has_key('username') and session['username'] == 'admin':
            return f()
        else:
            return render_template('signin.html')
    return tmp

@test.route('/logout')
def logout():
    if session.has_key('username'):
        session.clear()

    return redirect(url_for('test.login'))
@test.route('/index',methods=['get','post'])
@test.route('/',methods=['get','post'])
@logintest
def index():
    form = TodoListForm()
    if request.method == 'GET':
        todolists = TodoList.query.filter_by(name=session['username']).all()
        return render_template('index.html', todolists=todolists, form=form)
    else:
        if form.validate_on_submit():
            todolist = TodoList(str(uuid1()),session['username'], form.title.data, form.status.data)
            db.session.add(todolist)
            db.session.commit()
            flash('You have add a new todo list')
        else:
            flash('validate string!')
        return redirect(url_for('test.index'))

@test.route('/show/<id>',methods=['GET', 'POST'])
# @logintest
def show(id):
    todolist = TodoList.query.filter_by(user_id=id).first_or_404()
    return render_template_string(todolist.title)

@test.route('/contact',methods=['get','post'])
@logintest
def contact():
    if request.method == 'POST':
        if request.form.has_key('content'):
            db.session.add(Contact(content=request.form['content']))
            db.session.commit()
            return  render_template('contact.html',flag = 'Submit successfully! administrator will read it soon!')
        else:
            return render_template('contact.html')
    else:
        return render_template('contact.html')

@test.route('/login',methods=['get','post'])
def login():
	# print request.method,request.form['username']

    if session.has_key('username'):
        return redirect(url_for('test.index'))
    if request.method == 'POST':
        # print request.method, request.form['username']
        user = User.query.filter_by(name=request.form['username'], password=request.form['password']).first()
        if user:
            session['username'] = user.name
            return redirect(url_for('test.index'))
        else:
            # flash('Invalid username or password')
            return render_template('signin.html',flag='Invalid username or password')
    else:
	    return render_template('signin.html')

@test.route('/registe',methods=['get','post'])
def registe():
    if session.has_key('username'):
        return redirect(url_for('test.index'))

    if request.method == 'POST':
        if User.query.filter_by(name=request.form['username']).first():
            return render_template('signup.html',flag='Username conflicted!')
        else:
            if request.form['password0'] == request.form['password1']:
                db.session.add(User(name=request.form['username'],password=request.form['password0']))
                db.session.commit()
                return redirect(url_for('test.login'))
            else:
                return render_template('signup.html',flag='Confirm Your Password!')
    else:
        return render_template('signup.html')
	# print request.method,request.form['username']
	return render_template('signup.html')


@test.route('/delete/<id>')
def delete(id):
     todolist = TodoList.query.filter_by(user_id=id).first_or_404()
     db.session.delete(todolist)
     db.session.commit()
     flash('You have delete a todo list')
     return redirect(url_for('test.index'))


@test.route('/change/<id>', methods=['GET', 'POST'])
def edit(id):
    if request.method == 'GET':
        todolist = TodoList.query.filter_by(user_id=id).first_or_404()
        form = TodoListForm()
        form.title.data = todolist.title
        form.status.data = str(todolist.status)
        return render_template('modify.html', form=form)
    else:
        form = TodoListForm()
        if form.validate_on_submit():
            todolist = TodoList.query.filter_by(user_id=id).first_or_404()
            todolist.title = form.title.data
            todolist.status = form.status.data
            db.session.commit()
            flash('You have modify a todolist')
        else:
            flash(form.errors)
        return redirect(url_for('test.index'))

@test.route('/about',methods=['get','post'])
@logintest
def about():
	# print request.method,request.form['username']
	return render_template('about.html')


@test.route('/sidebarl',methods=['get','post'])
def sidebarl():
	# print request.method,request.form['username']
	return render_template('sidebar-left.html')


@test.route('/sidebarr',methods=['get','post'])
def sidebarr():
	# print request.method,request.form['username']
	return render_template('sidebar-right.html')


@test.route('/admin/',methods=['get','post'])
@test.route('/admin/mysecrecy_directory',methods=['get','post'])
# @admin_logintest
def readpost():
    a = Contact.query.first()
    flag = 1 if request.remote_addr == '127.0.0.1' else 0
    if a != None:
        db.session.delete(a)
        db.session.commit()
        print a.content
        return render_template('admin.html',flag=flag,xxx=a.content)

    return render_template('admin.html',flag=flag)
    