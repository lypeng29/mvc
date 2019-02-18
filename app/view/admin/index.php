<!DOCTYPE html>
<html>
<head>
    <title>管理首页</title>
</head>
<body>
    <div>
    <ul class="topbar">
        <li><a href="/config/index">配置管理</a></li>
        <li><a href="/config/index">分类管理</a></li>
        <li><a href="/config/index">文章管理</a></li>
    </ul>
    </div>
    <div>
        网站配置
    </div>
</body>
</html>
<script src="https://www.lypeng.com/static/js/jquery-2.1.3.min.js"></script>
<script>
$('#sub').click(function(){
    // alert(login.username.value);
    $.ajax({
        url:'http://www.mvc.com/api/checklogin',
        data:{user:login.username.value,pass:login.password.value},
        type:'POST',
        success:function(data){
            if(data.code != 0){
                alert(data.message);
            }else{
                window.location.href='/admin/index';
                // alert(data.data.token);
            }
        },
        error:function(data){
            alert('error');
        }
    })
})
</script>