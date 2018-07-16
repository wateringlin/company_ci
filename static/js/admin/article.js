;(function() {

  var article = {

    data: {
      // 页面地址
      addUrl: G_BASE_URL + 'admin/article/add',
      // 获取数据地址
      homeMenuUrl: G_BASE_URL + 'admin/article/getHomeMenus',

      // 上传图片相关
      uploadSwfUrl: G_BASE_URL + 'static/js/party/uploadify.swf',
      uploadImageUrl: G_BASE_URL + 'admin/article/getUploadedImage',
      
      // 编辑器
      editorUrl: G_BASE_URL + 'admin/article/editorUpload',

      // 标题颜色
      titleColor: [
        { label: '红色', value: '#5674ed' },
        { label: '蓝色', value: '#ed568e' }
      ],
      // 来源
      origin:[
        { label: '本站', value: '0' },
        { label: '新浪网', value: '1' },
        { label: '央视网', value: '2' },
        { label: '网易', value: '3' },
        { label: '搜狐', value: '4' }
      ],
      homeNavs: [], // 前端栏目

    },

    // 跳转至新增页面
    jumpAddUrl: function() {
      location.href = this.data.addUrl;
    },

    renderHomeNavs: function() {
      var tpl = document.getElementById('home_navs_tpl').innerHTML;
      var data = {
        homeNavs: this.data.homeNavs
      };
      var html = template(tpl, data);
      document.getElementById('home_navs').innerHTML = html;
    },
    renderTitleColor: function() {
      var tpl = document.getElementById('title_color_tpl').innerHTML;
      var data = {
        titleColor: this.data.titleColor
      };
      var html = template(tpl, data);
      document.getElementById('title_color').innerHTML = html;
    },
    renderOrigin: function() {
      var tpl = document.getElementById('origin_tpl').innerHTML;
      var data = {
        origin: this.data.origin
      };
      var html = template(tpl, data);
      document.getElementById('origin').innerHTML = html;
    },

    getHomeMenu: function() {
      var _this = this;
      $.ajax({
        type: 'GET',
        url: this.data.homeMenuUrl,
        dataType: 'JSON',
        success: function(res) {
          if (res.retcode === 0) {
            // 成功
            _this.data.homeNavs = res.data;
            _this.renderHomeNavs();
          } else {
            // 失败
            return dialog.error(res.msg);
          }
        }
      })
    },

    // 绑定事件
    bindEvents: function() {
      var _this = this;
      // 跳转至添加页面
      $('#add').click(function() {
        _this.jumpAddUrl();
      });

      // 上传图片，要先把swf文件加载处理，所以不能把上传方法写到外面去点击时再调用
      $('#ajax_file_upload').uploadify({
        'swf': this.data.uploadSwfUrl, // 使用了flash，记得把浏览器设置为允许flash运行，否则没反应
        'uploader': this.data.uploadImageUrl,
        'buttonText': '上传图片',
        'fileTypeDesc': 'Image Files', // 文件类型的描述
        'fileObjName': 'file', // 后端接收数据使用的文件对象名
        'fileTypeExts': '*.gif; *.jpg; *.png', // 允许上传的文件后缀
        // 每一个文件上传成功时触发该事件
        'onUploadSuccess': function(file, data, response) {
          // file 上传成功的文件对象
          // data 上传数据处理文件输出的值(uploadify.php)
          // response 服务端相应，上传成功为true，失败为false
          if (response) {
            var obj = JSON.parse(data); // 由JSON字符串转换为JSON对象
            $('#' + file.id).find('.data').html(' 上传完毕');
            // 显示已上传的图片，填充已上传图片路径到value值中以待提交
            $('#show_uploaded_img').attr('src', obj.data);
            $('#file_uploaded_urls').attr('value', obj.data);
            $('#show_uploaded_img').show();
          } else {
            alert('上传失败');
          }
        }
      });
    },

    // 获取数据列表
    initData: function() {
      // 初始化富文本框
      var _this = this;
      KindEditor.ready(function(K) {
        window.editor = K.create('#editor', {
          uploadJson: _this.data.editorUrl,
          afterBlur: function() { this.sync(); }
        })
      })

      // 获取前端栏目
      this.getHomeMenu();
      this.renderTitleColor();
      this.renderOrigin();
    },

    // 初始化
    init: function() {
      this.initData();
      this.bindEvents();
    }

  };

  article.init();

})();