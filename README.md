# 说明，一个最基础的MVC

> 参考：https://www.cnblogs.com/foonsun/p/5788564.html
> fastphp

采用命名空间，控制器类名后面不跟Controller

## 去掉index.php配置
Apache==>.htaccess
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

nginx==>nginx.conf
location / {
    try_files $uri $uri/ /index.php$args;
}

## 单例模式测试
http://www.cnblogs.com/iforever/p/4132927.html

