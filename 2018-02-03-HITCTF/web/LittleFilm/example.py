#coding=utf-8
import web  
import cgi
import os
import time
import subprocess
import random
cgi.maxlen = 512*1024 #500k

#web.config.debug = False

urls = ('/upload', 'Upload',
'/download(.*)', 'Download',
'/','Readme',
'/convert','Convert'

)  
class Readme:
    def GET(self):
	return """<html><h3>/upload , /download?name=xxx.avi .We will help you convert video with ffmpeg. Maybe you will find something different </h3>
<h3>Don't attack the platform ,it's simple .</h3>
<h3>Pay more attention to the video file and you will see what you want .</h3> 
<!-- flag is in /flag.txt -->
</html>
"""
  
class Upload:  
    def GET(self):  
        web.header("Content-Type","text/html; charset=utf-8")  
        return """<html><head></head><body> 
要不要上传一些avi呀
<form method="POST" enctype="multipart/form-data" action=""> 
<input type="file" name="myfile" >
<input type="submit" /> 
</form> 
</body></html>"""  
  
    def POST(self):  
	try:
            x = web.input(myfile={})  
            filedir = '/home/wwwroot/default/upload' # change this to the directory you want to store the file in.  
	    if 'myfile' in x: # to check if the file-object is created  
		print x.myfile.filename
	        if x.myfile.filename != '123.avi':
	            return "名字不对 ,要不换成 123.avi? "
		filepath=x.myfile.filename.replace('\\','/') # replaces the windows-style slashes with linux ones.
		r=str(random.randint(1,60))
		t=str(int(time.time()))+r+".avi"  
	        filename=t # splits the and chooses the last part (the filename with extension)  
    	        fout = open(filedir +'/'+ filename,'w') # creates the file where the uploaded file should be stored  
	        fout.write(x.myfile.file.read()) # writes the uploaded file to the newly created file.  
        	fout.close() # closes the file, upload complete.

	    avipath =filedir+"/"+filename
	    if os.path.exists(avipath):
		eval="/home/wwwroot/default/upload/ffmpeg-2.3.3/ffmpeg -i /home/wwwroot/default/upload/"+filename+" /home/wwwroot/default/upload/"+filename+" -y"
                os.system(eval)
		file="upload success: "+filename+" \nbut it wont last long"
	        return file
            else:
	        return "something wrong "

	except ValueError:
	    return "文件太大啦 , 只有500K 的容量哦 "
        raise web.seeother('/upload')  

''' 
class Convert:
    def GET(self):
    	avipath = '/home/wwwroot/default/upload/123.avi'
    	if os.path.exists(avipath):
        	os.system("/home/wwwroot/default/upload/ffmpeg-2.3.3/ffmpeg -i /home/wwwroot/default/upload/123.avi /home/wwwroot/default/upload/456.avi -y")
        	return "上传成功"
    	else:
        	return "something wrong "
'''

class Download:
    def GET(self,name):
	name=web.input(name="no data")
	filename=name.name
	if(filename.split('.')[-1] != 'avi'):
		return "only can download avi"
	try:
	    fout = open(os.path.join('/home/wwwroot/default/upload/', filename), "rb") 
	except:
	    return "还没有文件呢"
	web.header('Content-Type','application/octet-stream') 
	web.header('Content-disposition', 'attachment; filename=%s' % filename.decode('utf-8')) 
	stream = fout.read()
	return stream

class Del:
    def GET(self):
	os.popen("rm -f /home/wwwroot/default/upload/456.avi")
	return "删了别人就看不到啦 "
if __name__ == "__main__":  
   app = web.application(urls, globals())   
   app.run()  

