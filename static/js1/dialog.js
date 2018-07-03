var dialog = {
  // 错误弹出层
  error: function(msg) {
    layer.open({
      content: msg,
      icon: 2,
      title: '错误提示'
    });
  },

  // 成功弹出层
  success: function(msg, url) {
    layer.open({
      content: msg,
      icon: 1,
      yes: function() {
        location.href = url;
      }
    });
  },

  // 确认弹出层
  confirm: function(msg, url) {
    layer.open({
      content: msg,
      icon: 3,
      btn: ['是', '否'],
      yes: function() {
        location.href = url;
      }
    });
  },

  // 无需跳转到指定页面的确认弹出层
  toconfirm: function(msg) {
    layer.open({
      content: msg,
      icon: 3,
      btn: ['确定']
    });
  }
};