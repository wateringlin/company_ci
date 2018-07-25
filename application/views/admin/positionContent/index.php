<?php include VIEWPATH.'admin/include/header.php';?>
<body>
<div id="wrapper">

    <?php include VIEWPATH.'admin/include/nav.php'; ?>
<div id="page-wrapper">

    <div class="container-fluid" >

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">

                <ol class="breadcrumb">

                    <li class="active">
                        <i class="fa fa-table"></i>推荐位内容管理
                    </li>
                </ol>
            </div>
        </div>
        <!-- /.row -->
        <div >
            <button  id="add" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>添加 </button>
        </div>

        <div class="row">
            <form action="/admin.php" method="get">
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-addon">推荐位</span>
                        <select class="form-control" name="position_id">
                            <foreach name="positions" item="position">
                                <option value="{$position.id}"<if condition="$position['id'] eq $positionId">selected="selected"</if> >{$position.name}</option>
                            </foreach>
                        </select>
                    </div>
                </div>
                <input type="hidden" name="c" value="positioncontent"/>
                <input type="hidden" name="a" value="index"/>
                <div class="col-md-3">
                    <div class="input-group">
                        <input class="form-control" name="title" type="text" value="{$title}" placeholder="文章标题" />
                <span class="input-group-btn">
                  <button id="sub_data" type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-search"></i></button>
                </span>
                    </div>
                </div>
            </form>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <h3></h3>
                <div class="table-responsive">
                    <form id="singcms-listorder">
                    <table class="table table-bordered table-hover singcms-table">
                        <thead>
                        <tr>
                            <th width="14">排序</th><!--7-->
                            <th>id</th>
                            <th>标题</th>
                            <th>时间</th>
                            <th>封面图</th>
                            <th>状态</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody id="pcontent_list"></tbody>
                    </table>
                    </from>
                    <div>
                        <button  id="button-listorder" type="button" class="btn btn-primary dropdown-toggle" ><span class="glyphicon glyphicon-resize-vertical" aria-hidden="true"></span>更新排序</button>
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
<script>
    var SCOPE = {
        'edit_url' : '/admin.php?c=positioncontent&a=edit',
        'set_status_url' : '/admin.php?c=positioncontent&a=setStatus',
        'add_url' : '/admin.php?c=positioncontent&a=add',
        'listorder_url' : '/admin.php?c=positioncontent&a=listorder',
    }

</script>
<script id="pcontent_list_tpl" type="text/html">
    <% for (var i = 0; i < pcontentList.length; i++) { %>
        <tr>
            <td><input size="4" type='text'  name='<%= pcontentList[i].id %>' value="<%= pcontentList[i].listorder %>"/></td>
            <td><%= pcontentList[i].id %></td>
            <td><%= pcontentList[i].title %></td>
            <td><%= pcontentList[i].create_time %></td>
            <td><%= pcontentList[i].thumb %></td>
            <td>
                <span data-id="<%= pcontentList[i].id %>" class="sing_cursor singcms-on-off" id="change_status" data-status="<%= pcontentList[i].status %>"><%= pcontentList[i].status %></span>
            </td>
            <td>
                <a href="javascript:void(0);" attr-id="<%= pcontentList[i].id %>" id="edit" attr-a"pcontent" attr-message="编辑">
                    <span class="glyphicon glyphicon-edit" aria-hidden="true" ></span>
                </a>
                <a href="javascript:void(0);" attr-id="<%= pcontentList[i].id %>" id="delete" attr-a="pcontent" attr-message="删除">
                    <span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>
                </a>
            </td>
        </tr>
    <% } %>
</script>
<script src="<?php echo base_url();?>static/js/admin/positionContent.js"></script>
<?php include VIEWPATH.'admin/include/footer.php'?>