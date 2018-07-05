
<?php include VIEWPATH.'admin/include/header.php'?>
<body>

<div id="wrapper">

    <?php include VIEWPATH.'admin/include/nav.php'; ?>

    <div id="page-wrapper">

    <div class="container-fluid" >

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">

                <ol class="breadcrumb">
                    <li>
                        <i class="fa fa-dashboard"></i>  <a href="<?php echo site_url('admin/menu');?>">菜单管理</a>
                    </li>
                    <li class="active">
                        <i class="fa fa-table"></i>列表 
                    </li>
                </ol>
            </div>
        </div>
        <!-- /.row -->
        <div class="row">
            <div>

                <div class="input-group">
                    <span class="input-group-addon">类型</span>
                    <select class="form-control" name="type">
                        <option value="">请选择类型</option>
                        <option value="1" >后台菜单</option>
                        <option value="0" >前端导航</option>
                    <lect>
                </div>

                <input type="hidden" name="c" value="menu"/>
                <input type="hidden" name="a" value="index"/>
                <span class="input-group-btn">
                  <button id="btn_search_submit" class="btn btn-primary"><i class="glyphicon glyphicon-search"></i><tton>
                </span>

            </div>
        </div>
        <div>
          <button  id="add" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>添加 </button>
          <button id="export" type="button" class="btn btn-primary">下载模版</button>
          <button id="import" type="button" class="btn btn-primary">导入模版</button>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <h3></h3>
                <div class="table-responsive">
                    <form id="form_sort">
                    <table class="table table-bordered table-hover singcms-table">
                        <thead>
                            <tr>
                                <th width="14">排序</th>
                                <th>id</th>
                                <th>菜单名</th>
                                <th>模块名</th>
                                <th>类型</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody id="menu-list">
                        </tbody>
                    </table>
                    </form>
                    <nav>
                        <ul class="pagination" id="pagination"></ul>
                    </nav>
                    <div>
                        <button  id="btn_sort_submit" type="button" class="btn btn-primary dropdown-toggle" ><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>更新排序 </button>
                    </div>
                </div>
            </div>

        </div>
        <!-- /.row -->

    </div>
    <!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->

</div>
<!-- /#wrapper -->
<script id="menu_list_tpl" type="text/html">
    <%for (var i = 0; i < menuList.length; i++) {%>
    <tr>
        <td>
            <input type="text" size="4" name="<%=menuList[i].menu_id%>" value="<%=menuList[i].listorder%>">
        </td>
        <td><%=menuList[i].menu_id%></td>
        <td><%=menuList[i].name%></td>
        <td><%=menuList[i].m%></td>
        <td><%=menuList[i].type%></td>
        <td><%=menuList[i].status%></td>
        <td>
            <a href="javascript:void(0);" attr-id="<%=menuList[i].menu_id%>" id="edit" attr-a"menu" attr-message="编辑">
                <span class="glyphicon glyphicon-edit" aria-hidden="true" ></span>
            </a>
            <a href="javascript:void(0);" attr-id="<%=menuList[i].menu_id%>" id="delete" attr-a="menu" attr-message="删除">
                <span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>
            </a>
        </td>
    </tr>
    <%}%>
</script>
<script src="<?php echo base_url();?>static/js/admin/menu.js"></script>
<?php include VIEWPATH.'admin/include/footer.php'?>





