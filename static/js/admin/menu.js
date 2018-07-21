;(function() {

  var menu = {

    data: {
      // 页面地址
      listUrl: G_BASE_URL + 'admin/menu',
      addUrl: G_BASE_URL + 'admin/menu/add',
      editUrl: G_BASE_URL + 'admin/menu/edit',
      // 获取或修改数据地址
      getListUrl: G_BASE_URL + 'admin/menu/getList',
      modifyUrl: G_BASE_URL + 'admin/menu/modify',
      deleteUrl: G_BASE_URL + 'admin/menu/delete',
      sortUrl: G_BASE_URL + 'admin/menu/sort',

      search: {
        type: {value: '', operator: '='}
      },
      isPaginationLoaded: false,
      pagination: {
        total: '',
        page: 1,
        pageSize: 10
      },

      menuList: [],

      // 上传下载模板文件相关
      uploadSwfUrl: G_BASE_URL + 'static/js/party/uploadify.swf',
      uploadFileUrl: G_BASE_URL + 'admin/menu/importData',
      downloadFileUrl: G_BASE_URL + 'res/menu_template.csv'
    },

    pagination: function() {
      var _this = this;
      $('#pagination').jqPaginator({
        totalCounts: this.data.pagination.total ? parseInt(this.data.pagination.total) : 1, // totalCounts不能为0，不然保错
        pageSize: parseInt(this.data.pagination.pageSize),
        onPageChange: function (page) {
          // 如果初始化时分页条已经加载，则禁止再调用getList方法，防止重复调用
          if (!_this.data.isPaginationLoaded) {
            _this.data.pagination.page = page;
            _this.getList();
          }
          _this.data.isPaginationLoaded = false;
        }
      });
    },

    // 跳转至新增页面
    jumpAddUrl: function() {
      location.href = this.data.addUrl;
    },

    // 跳转至编辑页面
    jumpEditUrl: function(id) {
      // URL带上id，编辑页面不方便js渲染，用PHP
      location.href = this.data.editUrl + '?id=' + id;
    },

    // 渲染菜单列表
    renderMenuList: function() {
      var menu_list_tpl = document.getElementById('menu_list_tpl');
      if (menu_list_tpl) {
        var tpl = menu_list_tpl.innerHTML;
        var data = {
          menuList: this.data.menuList
        };
        var html = template(tpl, data);
        document.getElementById('menu-list').innerHTML = html;
      }
    },

    // 排序数据
    sort: function() {
      var _this = this;
      var data = {};
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
    delete: function(id) {
      var _this = this;
      var data = {};
      data.id = id;
      data.fields = {
        status: -1
      };

      layer.open({
        type: 0,
        title: '删除',
        btn: ['是', '否'],
        icon: 3,
        closeBtn: 2,
        content: '是否确定删除',
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
          })
        }
      });
    },

    // 修改或增加或删除菜单数据
    modify: function (type) {
      var _this = this;
      var data = {};
      var form = {};
      var id = parseRouteParams().id;
      // 判断表单类型
      console.log('id: ', id);
      if (id) {
        form = $('#form_edit');
      } else {
        form = $('#form_add');
      }
      var formData = form.serializeArray();
      console.log('formData: ', formData);

      // 整理获取的表单数据，要发给后端的数据
      var postData = {};
      $(formData).each(function(i) {
        postData[this.name] = this.value;
      });
      data.fields = postData;

      // 判断是修改、增加还是删除操作
      var url = ''; 
      if (id) {
        data.id = id;
        url = this.data.modifyUrl;
        if (type === 'delete') {
          url = this.data.deleteUrl;
        }
      } else {
        url = this.data.modifyUrl;
      }

      $.ajax({
        type: 'post',
        url: url,
        data: data,
        dataType: 'json',
        success: function(res) {
          if (res.retcode == 0) {
            // 成功
            return dialog.success(res.msg, _this.data.listUrl);
          } else {
            // 失败
            return dialog.error(res.msg);
          }
        }
      })
    },

    // 获取菜单列表数据
    getList: function (callback) {
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
          // console.log('res: ', res)  ;
          if (res.retcode === 0) {
            // 成功
            _this.data.menuList = res.data.data;
            _this.data.pagination.total = res.data.total;
            _this.renderMenuList();

            if (typeof callback === 'function') {
              callback && callback();
            }
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

      // 跳转至编辑页面，该结构由js动态生成，使用delegate事件代理
      $('body').delegate('#edit', 'click', function() {
        var id = $(this).attr('attr-id');
        _this.jumpEditUrl(id);
      });

      // 删除数据，该结构由js动态生成，使用delegate事件代理
      $('body').delegate('#delete', 'click', function() {
        var id = $(this).attr('attr-id');
        _this.delete(id);
      });

      // 提交编辑数据按钮
      $('#btn_edit_submit').click(function() {
        _this.modify();
      });

      // 提交增加数据按钮
      $('#btn_add_submit').click(function() {
        _this.modify();
      });

      // 提交排序数据按钮
      $('#btn_sort_submit').click(function() {
        _this.sort();
      });

      // 查询按钮
      $('#btn_search_submit').click(function() {
        // 获取查询条件
        var selectVal = $('select[name="type"]').val();

        // 设置查询条件
        _this.data.search.type.value = selectVal;
        _this.data.pagination.page = 1; 

        // 根据查询条件获取数据
        _this.getList(function() {
          _this.data.isPaginationLoaded = true;
          _this.pagination();
        });
      });

      // 导入模板
      $('#import').uploadify({
        swf: this.data.uploadSwfUrl,
        uploader: this.data.uploadFileUrl,
        buttonText: '导入模板',
        fileTypeDesc: 'csv',
        fileObjName: 'file',
        fileTypeExts: '*.csv',
        opUploadSuccess: function(file, data, response) {
          if (response) {
            var obj = JSON.parse(data);
            $('#' + file.id).find('.data').html(' 上传完毕');
          } else {
            alert('上传失败');
          }
        }
      })
      
      // 导出模板
      $('#export').click(function() {
        // location.href = this.downloadFileUrl;
      });
    },

    // 获取数据列表
    initData: function() {
      var _this = this;
      // 获取数据列表
      this.getList(function() {
        // 防止重复调用getList方法
        _this.data.isPaginationLoaded = true;
        // 加载完数据后，显示分页条
        _this.pagination();
      });
    },

    // 初始化
    init: function() {
      this.bindEvents();
      this.initData();
    }
  }

  menu.init();
  
})();