<?php include VIEWPATH.'admin/include/header.php';?>
<body>
<div id="wrapper">

    <?php include VIEWPATH.'admin/include/nav.php';?>
<div id="page-wrapper">

    <div class="container-fluid" >

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">

                <ol class="breadcrumb">

                    <li>
                        <i class="fa fa-dashboard"></i>  <a href="<?php echo base_url();?>admin/position">推荐位管理</a>
                    </li>
                    <li class="active">
                        <i class="fa fa-table"></i>推荐位列表
                    </li>
                </ol>
            </div>
        </div>
        <!-- /.row -->
        <div >
            <button id="add" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>添加 </button>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <h3></h3>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover singcms-table">
                        <thead>
                        <tr>
                            <th>id</th>
                            <th>推荐位名称</th>
                            <th>时间</th>
                            <th>状态</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody id="position_list"></tbody>
                    </table>

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
<script id="position_list_tpl" type="text/html">
    <%for (var i = 0; i < positionList.length; i++) {%>
        <tr>
            <td><%=positionList[i].id%></td>
            <td><%=positionList[i].name%></td>
            <td><%=positionList[i].create_time%></td>
            <td><span id="change_status"  attr-status="<%=positionList[i].status%>"  data-id="<%=positionList[i].id%>" class="sing_cursor singcms-on-off"><%=positionList[i].status%></span></td>
            <td>
                <span class="sing_cursor glyphicon glyphicon-edit" aria-hidden="true" id="edit" data-id="<%=positionList[i].id%>" ></span>
                <a href="javascript:void(0)" id="delete" attr-id="<%=positionList[i].id%>"  attr-message="删除">
                    <span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>
                </a>
                
            </td>
        </tr>
    <%}%>
</script>
<script src="<?php echo base_url();?>static/js/admin/position.js"></script>
<?php include VIEWPATH.'admin/include/footer.php';?>