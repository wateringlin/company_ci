;(function() {
    var position = {

        data: {
            // 页面地址
            listUrl: G_BASE_URL + 'admin/position',
            addUrl: G_BASE_URL + 'admin/position/add',

            // 获取或修改数据地址
            getAllListUrl: G_BASE_URL + 'admin/position/getAllList',
            modifyUrl: G_BASE_URL + 'admin/position/modify',

            positionList: [], // 推荐位列表
            
        },

        // 跳转到增加页面
        jumpAddUrl: function() {
            location.href = this.data.addUrl;
        },

        // 渲染推荐位列表
        renderPostionList: function() {
            var position_list_tpl = document.getElementById('position_list_tpl');
            if (position_list_tpl) {
                var tpl = position_list_tpl.innerHTML;
                var data = {
                    positionList: this.data.positionList
                };
                var html = template(tpl, data);
                document.getElementById('position_list').innerHTML = html;
            }
        },

        // 修改或增加文章数据
        modify: function() {
            var _this = this;
            var data = {};
            var form = {};
            var id = parseRouteParams().id;

            // 判断表单类型
            if (id) {
                data.id = id;
                form = $('#form_edit');
            } else {
                form = $('#form_add');
            }
            var formData = form.serializeArray();

            // 整理获取的表单数据，要发给后端的数据
            var postData = {};
            $(formData).each(function() {
                postData[this.name] = this.value;
            });
            data.fields = postData;
            console.log('data: ', data);

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
            });
        },

        // 获取推荐位列表数据
        getAllList: function() {
            var _this = this;

            $.ajax({
                type: 'get',
                url: this.data.getAllListUrl,
                data: {},
                dataType: 'json',
                success: function(res) {
                    if (res.retcode === 0) {
                        // 成功
                        _this.data.positionList = res.data.data;
                        _this.renderPostionList();
                    } else {
                        // 失败
                        return dialog.error(res.msg);
                    }
                }
            });
        },

        // 绑定事件
        bindEvents: function() {
            var _this = this;

            $('#btn_add_submit').click(function() {
                _this.modify();
            });

            $('#add').click(function() {
                _this.jumpAddUrl();
            });
        },

        // 获取数据列表
        initData: function() {
            var _this = this;

            // 获取推荐位列表数据
            this.getAllList();
        },

        // 初始化
        init: function() {
            this.initData();
            this.bindEvents();
        }

    };

    position.init();

})();