;(function() {

    var article = {
  
      data: {
        // 页面地址
        listUrl: G_BASE_URL + 'admin/positioncontent',
        addUrl: G_BASE_URL + 'admin/positioncontent/add',
        editUrl: G_BASE_URL + 'admin/positioncontent/edit',
        // 获取或修改数据地址
        getListUrl: G_BASE_URL + 'admin/positioncontent/getList',
        homeMenuUrl: G_BASE_URL + 'admin/positioncontent/getHomeMenus',
        modifyUrl: G_BASE_URL + 'admin/positioncontent/modify',
        deleteUrl: G_BASE_URL + 'admin/positioncontent/delete',
        sortUrl: G_BASE_URL + 'admin/positioncontent/sort',
        getPositionList: G_BASE_URL + 'admin/position/getAllList',
        pushUrl: G_BASE_URL + 'admin/positioncontent/push',
  
        // 上传图片相关
        uploadSwfUrl: G_BASE_URL + 'static/js/party/uploadify.swf',
        uploadImageUrl: G_BASE_URL + 'admin/positioncontent/getUploadedImage',
        
        // 编辑器
        editorUrl: G_BASE_URL + 'admin/positioncontent/editorUpload',
  
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
  
        articleList: [], // 文章列表
        isPaginationLoaded: false,
        pagination: {
          total: '',
          page: 1,
          pageSize: 10
        },
  
        isAddPage: false,
        selectedHomeNav: '',
  
        pcontentList: [], // 推荐位列表数据
        selectedPosition: 0
      },
  
      // 跳转到新增页面
      jumpAddUrl: function() {
        location.href = this.data.addUrl;
      },
  
      // 跳转到编辑页面
      jumpEditUrl: function(id) {
        location.href = this.data.editUrl + '?id=' + id;
      },
  
      // 分页条
      pagination: function() {
        var _this = this;
        $('#pagination').jqPaginator({
          totalCounts: this.data.pagination.total ? parseInt(this.data.pagination.total) : 1, // totalCounts不能为0，不然保错
          pageSize: parseInt(this.data.pagination.pageSize),
          onPageChange: function(page) {
            // 如果初始化时分页条已经加载，则禁止再调用getList方法，防止重复调用
            if (!_this.data.isPaginationLoaded) {
              _this.data.pagination.page = page;
              _this.getList();
            }
            _this.data.isPaginationLoaded = false;
          }
        });
      },
  
      // 渲染文件列表
      renderPositionContentList: function() {
        var pcontent_list_tpl = document.getElementById('pcontent_list_tpl');
        if (pcontent_list_tpl) {
          var tpl = pcontent_list_tpl.innerHTML;
          var data = {
            pcontentList: this.data.pcontentList
          };
          var html = template(tpl, data);
          document.getElementById('pcontent_list').innerHTML = html;
        }
      },
  
      // 渲染前端栏目
      renderHomeNavs: function() {
        var home_navs_tpl = document.getElementById('home_navs_tpl');
        var index_home_navs_tpl = document.getElementById('index_home_navs_tpl');
        if (home_navs_tpl) {
          var tpl = home_navs_tpl.innerHTML;
          var data = {
            homeNavs: this.data.homeNavs
          };
          var html = template(tpl, data);
          document.getElementById('home_navs').innerHTML = html;
        }
        if (index_home_navs_tpl) {
          var tpl = index_home_navs_tpl.innerHTML;
          this.data.homeNavs.unshift({
            name: '全部分类',
            menu_id: ''
          });
          var data = {
            homeNavs: this.data.homeNavs,
            selectedHomeNav: this.data.selectedHomeNav
          };
          
          var html = template(tpl, data);
          document.getElementById('index_home_navs').innerHTML = html;
        }
      },
  
      // 渲染标题颜色
      renderTitleColor: function() {
        var title_color_tpl = document.getElementById('title_color_tpl');
        if (title_color_tpl) {
          var tpl = title_color_tpl.innerHTML;
          var data = {
            titleColor: this.data.titleColor
          };
          var html = template(tpl, data);
          document.getElementById('title_color').innerHTML = html;
        }
      },
  
      // 渲染来源
      renderOrigin: function() {
        var origin_tpl = document.getElementById('origin_tpl');
        if (origin_tpl) {
          var tpl = origin_tpl.innerHTML;
          var data = {
            origin: this.data.origin
          };
          var html = template(tpl, data);
          document.getElementById('origin').innerHTML = html;
        }
      },
  
      renderPositionList: function() {
        var position_list_tpl = document.getElementById('position_list_tpl');
        if (position_list_tpl) {
          var tpl = position_list_tpl.innerHTML;
          var data = {
            positionList: this.data.positionList,
            selectedPosition: this.data.selectedPosition
          };
          var html = template(tpl, data);
          document.getElementById('position_list').innerHTML = html;
        }
      },
  
      // 根据前端栏目id获取前端栏目名称
      getHomeNavName: function(articleItem) {
        var catid = articleItem.catid;
        for (var i = 0; i < this.data.homeNavs.length; i++) {
          var item = this.data.homeNavs[i];
          if (item.menu_id == catid) {
            articleItem.catid = item.name; // 匹配到就跳出该循环，继续循环下一个文章item
            break;
          }
        }
        // 重新渲染页面
        this.renderArticleList();
      },
  
      // 获取全部推荐位
      getPositionList: function() {
        var _this = this;
  
        $.ajax({
          type: 'get',
          url: this.data.getPositionList,
          data: {},
          dataType: 'json',
          success: function(res) {
            if (res.retcode === 0) {
              // 成功
              _this.data.positionList = res.data.data;
              _this.renderPositionList();
            } else {
              // 失败
              return dialog.error(res.msg);
            }
          }
        });
      },
  
      pushArticleToPosition: function() {
        // 获取要推送的推荐位
        var position_id = $('select[name="position_id"]').val();
        // 保存已选择推荐位，保持显示下拉框中被选中的值
        this.data.selectedPosition = position_id;
        // 获取要推送到相关推荐位的文章
        var push = {};
        $('input[name="pushcheck"]:checked').each(function(index) {
          push[index] = $(this).val(); // {0:'18', 1:'19', ...}
        });
        // 设置发送到服务端的数据
        var postData = {
          position_id: position_id,
          push: push
        };
        $.ajax({
          type: 'post',
          url: this.data.pushUrl,
          data: postData,
          dataType: 'json',
          success: function(res) {
            if (res.retcode === 0) {
              // 成功
              
            } else {
              // 失败
            }
          }
        });
      },
  
      // 获取前端栏目数据
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
              // 转化数字数据为中文
              for (var i = 0; i < _this.data.articleList.length; i++) {
                _this.getHomeNavName(_this.data.articleList[i]);
              }
  
              _this.renderHomeNavs();
            } else {
              // 失败
              return dialog.error(res.msg);
            }
          }
        })
      },
  
      // 转换数据为中文
      transferData: function() {
        var origin = this.data.origin;
        for (var i = 0; i < this.data.pcontentList.length; i++) {
          var item = this.data.pcontentList[i];
          // 转换来源数据
          for (var j = 0; j < origin.length; j++) {
            if (item.copyfrom == origin[j].value) {
              item.copyfrom = origin[j].label; // 匹配到就跳出该循环，继续循环下一个文章item
              break;
            }
          }
          if (item.thumb) {
            item.thumb = '有';
          } else {
            item.thumb = '无';
          }
        }
        // 重新渲染页面
        this.renderPositionContentList();
      },
  
      // 排序数据
      sort: function() {
        var _this = this;
        var data = {};
        // 通过form表单序列化获取填写的排序
        var formData = $('#form_sort').serializeArray();
        var postData = {};
        $(formData).each(function() {
          postData[this.name] = this.value;
        });
        data.fields = postData;
  
        $.ajax({
          type: 'post',
          url: this.data.sortUrl,
          data: data,
          dataType: 'json',
          success: function(res) {
            if (res.retcode == 0) {
              // 成功
              // 更新数据列表
              _this.getList(function() {
                _this.data.isPaginationLoaded = true;
                _this.pagination();
              });
              return dialog.success(res.msg);
            } else {
              // 失败
              return dialog.error(res.msg);
            }
          }
        });
      },
  
      // 删除数据
      delete: function(id, type) {
        var _this = this;
        var data = {};
        data.id = id;
        data.fields = {
          status: type === 'delete' ? -1 : type
        };
  
        layer.open({
          type: 0,
          title: type === 'delete' ? '删除' : '状态',
          btn: ['是', '否'],
          icon: 3,
          closeBtn: 2,
          content: type === 'delete' ? '是否确定删除' : '是否确定改变状态',
          scrollbar: true,
          yes: function() {
            $.ajax({
              type: 'post',
              url: _this.data.deleteUrl,
              data: data,
              dataType: 'json',
              success: function(res) {
                if (res.retcode == 0) {
                  // 成功
                  _this.getList(function() {
                    _this.data.isPaginationLoaded = true;
                    _this.pagination();
                  });
                  return dialog.success(res.msg);
                } else {
                  // 失败
                  return dialog.error(res.msg);
                }
              }
            });
          }
        });
      },
  
      // 搜索数据
      search() {
        var _this = this;
        // 获取查询条件
        var homeNav = $('select[name="catid"]').val();
        var title = $('input[name="title"]').val();
  
        this.data.selectedHomeNav = homeNav; // 保存已选择推荐位，保持下拉框中选中的值
  
        // 设置查询条件
        this.data.search = {
          catid: { value: homeNav, operator: '=' },
          title: { value: title, operator: '=' }
        };
  
        // 根据查询条件获取数据
        this.getList(function() {
          _this.data.isPaginationLoaded = true;
          _this.pagination();
        });
      },
  
      // 修改或增加文章数据
      modify:function(type) {
        var _this = this;
        var data = {};
        var form = {};
        var id = parseRouteParams().id;
        // 判断表单类型
        console.log('id: ', id);
        if (id) {
          data.id = id;
          form = $('#form_edit');
        } else {
          form = $('#form_add');
        }
        var formData = form.serializeArray();
        console.log('formData: ', formData);
  
        // 整理获取的表单数据，要发给后端的数据
        var postData = {};
        $(formData).each(function() {
          postData[this.name] = this.value;
        });
        data.fields = postData;
  
        $.ajax({
          type: 'post',
          url: this.data.modifyUrl,
          data: data,
          dataType: 'json',
          success: function(res) {
            if (res.retcode == 0) {
              // 成功，跳转回列表页面
              return dialog.success(res.msg, _this.data.listUrl);
            } else {
              // 失败
              return dialog.error(res.msg);
            }
          }
        })
      },
  
      // 获取文章列表数据
      getList: function(callback) {
        var _this = this;
        var data = {
          search: {},
          page: this.data.pagination.page,
          pageSize: this.data.pagination.pageSize
        }
        for (var i in this.data.search) {
          var item = this.data.search[i];
          if (item.value) {
            data.search[i] = item;
          }
        }
        // console.log('data: ', data);
  
        $.ajax({
          type: 'GET',
          url: this.data.getListUrl,
          data: data,
          dataType: 'JSON',
          success: function(res) {
            if (res.retcode === 0) {
              // 成功
              _this.data.pcontentList = res.data.data;
              console.log('_this.data.pcontentList: ', _this.data.pcontentList);
              // 获取并转换数字为中文
              _this.transferData();
            //   _this.getHomeMenu();
  
              // console.log('_this.data.articleList: ', _this.data.articleList);
              _this.data.pagination.total = res.data.total;
              _this.renderPositionContentList();
  
              if (typeof callback === 'function') {
                callback && callback();
              }
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
  
        // 提交增加数据按钮
        $('#btn_add_submit').click(function() {
          _this.modify();
        });
  
        // 提交编辑数据按钮
        $('#btn_edit_submit').click(function() {
          _this.modify();
        });
  
        // 提交查询按钮
        $('#btn_search_submit').click(function() {
          _this.search();
        });
  
        // 跳转到编辑页面
        $('body').delegate('#edit', 'click', function() {
          var id = $(this).data('id');
          _this.jumpEditUrl(id);
        });
  
        // 删除数据，status -1为删除
        $('body').delegate('#delete', 'click', function() {
          var id = $(this).data('id');
          _this.delete(id, 'delete');
        });
  
        // 改变状态，status 0为关闭
        $('body').delegate('#change_status', 'click', function() {
          var id = $(this).data('id');
          console.log('id');
          var status = $(this).data('status') == '正常' ? 0 : 1;
          console.log('1111');
          _this.delete(id, status);
        });
  
        // 提交排序数据按钮
        $('#btn_sort_submit').click(function() {
          _this.sort();
        });
  
        $('body').delegate('#push', 'click', function() {
          _this.pushArticleToPosition();
        });
      },
  
      // 获取数据列表
      initData: function() {
        var _this = this;
  
        // 获取文章列表数据
        this.getList(function() {
          // 防止重复调用getList方法
          _this.data.isPaginationLoaded = true;
          // 加载完数据后，显示分页条
          _this.pagination();
        });
  
        this.getPositionList();
        
        // 初始化富文本编辑器
        KindEditor.ready(function(K) {
          window.editor = K.create('#editor', {
            uploadJson: _this.data.editorUrl,
            afterBlur: function() { this.sync(); }
          })
        });
  
        // 增加页面渲染标题数据和来源
        this.renderTitleColor();
        this.renderOrigin();
        this.renderHomeNavs();
      },
  
      // 初始化
      init: function() {
        this.initData();
        this.bindEvents();
      }
  
    };
  
    article.init();
  
  })();