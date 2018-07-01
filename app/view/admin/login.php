<form name="login" action="" method="post">
账号：<br/>
<input type="text" name="username" value="123"/><br/><br/>
密码：<br/>
<input type="password" name="password" value=""/><br/><br/>
<input type="button" name="sub" id="sub" value="登录"/>
</form>
<script src="https://www.lypeng.com/themes/home/cms/js/jquery-1.8.3.min.js"></script>
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
                alert(data.data.token);
            }
        },
        error:function(data){
            alert('error');
        }
    })
})
</script>