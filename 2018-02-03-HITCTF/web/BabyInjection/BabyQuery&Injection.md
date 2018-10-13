# BabyQuery
一道原题, 放在新生赛里的目的是为了考察新生对官方文档的阅读能力和对新知识的学习能力, 但是原题实际上有点无厘头, 所以改简单了

题目用Graphql处理查询语句, 我直接用graphql命名API提示了这一点

题目给的查询语句是`{ getscorebyid(id: "GE======"){ id name score } }`, 通过用户输入的id base32解码后查询score, 但是getscorebyid这里没有注入点, 一个有注入点的getscorebyyourname被隐藏了起来

第一种方法是通过Graphql的[Introspection](http://graphql.org/learn/introspection/), 通过查询__schema来得到getscorebyyourname这个field, 测试可以直接union注入, 没有任何过滤, 然后就是sqlite的正常注入流程

然而Graphql还有另外一个特性[Validation](http://graphql.org/learn/validation/), 它的本意是服务开发者快速定位错误, 但是可能会造成敏感信息泄露从而导致这道题的另外一种方法, 注意到我们通过getscorebyid查询了`id name score`, 返回的数据中多出来了一个name, 那么很容易想到构造类似`getscorebyname`的语句, 那么这个语句带入查询会返回一个泄露真正字段的报错`GraphQLError('Cannot query field "getscorebyname" on type "Query". Did you mean "getscorebyyourname" or "getscorebyid"?',)`使我们拿到了有漏洞的`getscorebyyourname `

# BabyInjection
一道过时了的题目, 来源于我很早以前对`group by ... with rollup`利用的拓展
[连接](http://www.au1ge.xyz/2017/05/14/mysql%E6%B3%A8%E5%85%A5%E4%B8%80%E4%BA%9B%E5%81%8F%E9%97%A8%E6%8A%80%E5%B7%A7/), 并且这里还降低了难度, 可以通过盲注直接用数据库里查询到的密码登录拿到flag

第一种解法在我的文章中有比较详细的推演思路, 一步步构造限制一步步bypass, 但实际上bypass的过程会引来新的问题, 这里暂且不表

而另一种解法是因为在验证登录的时候直接获取的数据库明文, 所以盲注得到密码之后可以直接登录

```
$passwd = $_POST['passwd'];
...
if ($result['passwd'] == $passwd){
    die('you did it and this is your flag: '.$flag);
}
```
`and|select|from|where|union|join|sleep|benchmark|,|\(|\)|like|rlike|regexp|limit|or`这个正则看似过滤了很多, 实际上最基本的`||`和`&&`都没有过滤, 所以构造`1'||passwd<''=0--+`就可以盲注出密码