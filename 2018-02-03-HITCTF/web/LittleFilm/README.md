# 小电影
本题利用了FFmpeg可以处理HLS播放列表的特性，而播放列表可以引用外部文件。通过在AVI文件中添加自定义的包含本地文件引用的HLS播放列表，可以触发该漏洞并在该文件播放过程中显示本地文件的内容。
[详细漏洞介绍](http://blog.cyberpeace.cn/FFmpeg/)
1、主页右键查看源码可得知flag位置。
```html
<html><h3>/upload , /download?name=xxx.avi .We will help you convert video with ffmpeg. Maybe you will find something different </h3>
<h3>Don't attack the platform ,it's simple .</h3>
<h3>Pay more attention to the video file and you will see what you want .</h3> 
<!-- flag is in /flag.txt -->
</html>

```
2、利用exp构造avi文件。
<https://github.com/neex/ffmpeg-avi-m3u-xbin>
限制上传文件名只能为123.avi`python3 gen_xbin_avi.py file:///flag.txt 123.avi`

3、上传结束之后即可通过`download?name=xxx.avi`下载文件，从视频文件中即可获取flag。
