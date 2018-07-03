
<?php include VIEWPATH.'admin/include/header.php';?>
<body>

<div id="wrapper">

    <?php include VIEWPATH.'admin/include/nav.php';?>

    <div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">

                <ol class="breadcrumb">
                    <li>
                        <i class="fa fa-dashboard"></i>  <a href="/admin.php?c=menu">菜单管理</a>
                    </li>
                    <li class="active">
                        <i class="fa fa-edit"></i> 编辑
                    </li>
                </ol>
            </div>
        </div>
        <!-- /.row -->

        <div class="row">
            <div class="col-lg-6">

                <form class="form-horizontal" id="form_edit">
                    <div class="form-group">
                        <label for="inputname" class="col-sm-2 control-label">菜单名:</label>
                        <div class="col-sm-5">
                            <input type="text" name="name" class="form-control" id="inputname" placeholder="请填写菜单名" value="<?php echo $menu['name'];?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">菜单类型:</label>
                        <div class="col-sm-5">
                            <input type="radio" name="type" id="optionsRadiosInline1" value="1" <?php echo $menu['type'] == '1' ? 'checked' : ''?>> 后台菜单
                            <input type="radio" name="type" id="optionsRadiosInline2" value="0" <?php echo $menu['type'] == '0' ? 'checked' : ''?>> 前端栏目
                        </div>

                    </div>
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">模块名:</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="m" id="inputPassword3" placeholder="模块名如admin" value="<?php echo $menu['m'];?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">控制器:</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="c" id="inputPassword3" placeholder="控制器如index" value="<?php echo $menu['c'];?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">方法:</label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="f" id="inputPassword3" placeholder="方法名如index" value="<?php echo $menu['f'];?>">
                        </div>
                    </div>
                    <!--<div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">是否为前台菜单:</label>
                        <div class="col-sm-5">
                            <input type="radio" name="type" id="optionsRadiosInline1" value="0" checked> 否
                            <input type="radio" name="type" id="optionsRadiosInline2" value="1"> 是
                        </div>

                    </div>-->

                    <div class="form-group">
                        <label for="inputPassword3" class="col-sm-2 control-label">状态:</label>
                        <div class="col-sm-5">
                            <input type="radio" name="status" id="optionsRadiosInline1" value="1" <?php echo $menu['status'] == '1' ? 'checked' : ''?>> 开启
                            <input type="radio" name="status" id="optionsRadiosInline2" value="0" <?php echo $menu['status'] == '0' ? 'checked' : ''?>> 关闭
                        </div>

                    </div>
                    <input type="hidden" name="menu_id" value="<?php echo $menu['menu_id'];?>"/>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="button" class="btn btn-default" id="btn_edit_submit">更新</button>
                    </div>
                </div>
                </form>


            </div>

        </div>
        <!-- /.row -->

    </div>
    <!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->

</div>
<!-- /#wrapper -->
<script src="<?php echo base_url();?>static/js/admin/menu.js"></script>
<?php include VIEWPATH.'admin/include/footer.php';?>





