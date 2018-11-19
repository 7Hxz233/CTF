var url="http://127.0.0.1:9990/admin"
setInterval(function(){
var webPage = require('webpage');
var page = webPage.create();

var setting={
    operation: "GET",
    encoding: "utf8",
    headers: {
            "Referer":url
          },
  };

  page.addCookie({
    'name'     : 'hint',
    'value'    : 'Try to get admin\'s page content',
    'domain'   : '127.0.0.1',
    'path'     : '/admin/',
    'httponly' : false,
    'secure'   : false,
    'expires'  : (new Date()).getTime() + (1000 * 60 * 60)
  });

  page.addCookie({
    'name'     : 'flag',
    'value'    : 'hxb2018{9a6c2dfd3c22c8928f544c0b576e100f}',
    'domain'   : '127.0.0.1',
    'path'     : '/admin/mysecrecy_directory',
    'httponly' : false,
    'secure'   : false,
    'expires'  : (new Date()).getTime() + (1000 * 60 * 60)
  });
  page.addCookie({
    'name'     : 'session',
    'value'    : 'eyJjc3JmX3Rva2VuIjp7IiBiIjoiWlRZNE0yUXlNR1pqTkRJNU16RXpOalJqWkRNMk4yUmxZVGd4TXpFelpHTmpabVEwWVRSak1RPT0ifSwidXNlcm5hbWUiOiJhZG1pbiJ9.DoVbkw.ylHkvfjRjM42G1I0gkcTQz4Hi5U',
    'domain'   : '127.0.0.1',
    'path'     : '/admin/',
    'httponly' : true,
    'secure'   : false,
    'expires'  : (new Date()).getTime() + (1000 * 60 * 60)
  });

page.open(url,setting,function(status){
    console.log(page.content);
    setTimeout(function(){},7000);
  });

},7000);



  
  
