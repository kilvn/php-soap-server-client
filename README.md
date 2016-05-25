#使用php的协议搭建soap协议接口，并带有调试文件

#首先，得确定环境开启了soap协议

/op/soap.wsdl       #是xml格式的对soap函数的注册声明

/op/stdserver.php   #引入soap，动态注册接口函数

/op/functions.php   #接口函数文件，所有的函数写在method类里面

/client.php         #是客户端，模拟发起soap或post请求并且打印返回值，用于开发调试

#需要注意的是 soap.wsdl 尾部和 client.php 引用的soap链接IP、端口和目录要正确，不正确会导致soap通讯失败。

#soap值的传入和传出都是json格式的字符串。
