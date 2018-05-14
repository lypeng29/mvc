# 说明，一个最基础的MVC

> 参考：https://www.cnblogs.com/foonsun/p/5788564.html
> fastphp

1. 采用命名空间
2. 控制器类名后面不跟Controller，直接Index.php,首字母大写
3. 模板使用php与html混写方式，后缀.php
4. 采用mysqli,不是pdo(mysql扩展比较老了，放弃了，不怎么喜欢pdo的写法，还是更熟练mysqli方式)
5. composer增加vendor扩展(目前有phpmailer，querylist)
6. 增加各种Helper类，目前有db,str，time，api

## 去掉index.php配置
Apache重写
```
<IfModule mod_rewrite.c>
    # 打开Rerite功能
    RewriteEngine On

    # 如果请求的是真实存在的文件或目录，直接访问
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d

    # 如果访问的文件或目录不是真事存在，分发请求至 index.php
    RewriteRule . index.php
</IfModule>
```

nginx重写
```
location / {
    try_files $uri $uri/ /index.php$args;
}
```

## 单例模式测试
http://www.cnblogs.com/iforever/p/4132927.html


## 数据库
```sql
CREATE DATABASE `mvc` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `mvc`;

CREATE TABLE `item` (
    `id` int(11) NOT NULL auto_increment,
    `item_name` varchar(255) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
 
INSERT INTO `item` VALUES(1, 'Hello World.');
INSERT INTO `item` VALUES(2, 'Lets go!');
```
