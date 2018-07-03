var login = {
  check: function() {
    // 获取登录页面中的用户名和密码
    var username = $('input[name="username"]').val();
    var password = $('input[name="password"]').val();

    if (!username) {
      dialog.error('用户名不能为空');
    }
    if (!password) {
      dialog.error('密码不能为空');
    }

    // 发送请求，获取后端返回的数据，登录
    var url = '/admin/login/check';
    var data = {
      username: username,
      password: password
    };
    $.post(url, data, function(res) {
      if (res.retcode == 0) {
        // 登录成功
        return dialog.success(res.msg, '/admin/index');
      } else {
        // 登录失败
        return dialog.error(res.msg);
      }
    }, 'json'); // 这里如果没加json的话，返回的如果是中文则还需要解析
  }
}

$(function() {
  $('#login').click(function() {
    login.check();
  })
})