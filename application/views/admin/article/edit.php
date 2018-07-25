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
              <i class="fa fa-dashboard"></i>  <a href="/admin.php?c=content">文章管理</a>
            </li>
            <li class="active">
              <i class="fa fa-edit"></i> 文章添加
            </li>
          </ol>
        </div>
      </div>
      <!-- /.row -->

      <div class="row">
        <div class="col-lg-6">

          <form class="form-horizontal" id="form_edit">
            <div class="form-group">
              <label for="inputname" class="col-sm-2 control-label">标题:</label>
              <div class="col-sm-5">
                <input value="<?php echo $article['title'];?>" type="text" name="title" class="form-control" id="inputname" placeholder="请填写标题">
              </div>
            </div>
            <div class="form-group">
              <label for="inputname" class="col-sm-2 control-label">短标题:</label>
              <div class="col-sm-5">
                <input value="<?php echo $article['small_title'];?>" type="text" name="small_title" class="form-control" id="inputname" placeholder="请填写短标题">
              </div>
            </div>
            <div class="form-group">
              <label for="inputname" class="col-sm-2 control-label">缩图:</label>
              <div class="col-sm-5">
                <!-- 上传文件的按钮 -->
                <input id="ajax_file_upload"  type="file" multiple="true" >
                <!-- 显示已上传的图片 -->
                <img style="display: <?php echo $article['thumb'] ? 'block' : 'none'?>" id="show_uploaded_img" src="<?php echo $article['thumb']?>" width="150" height="150">
                <!-- 上传至后端的文件数据 -->
                <input id="file_uploaded_urls" name="thumb" type="hidden" multiple="true" value="<?php echo $article['thumb']?>">
              </div>
            </div>

            <!-- 标题颜色 begin -->
            <div class="form-group" id="title_color"></div>
            <!-- 标题颜色 end -->
            <!-- 前端栏目 begin -->
            <div class="form-group" id="home_navs"></div>
            <!-- 前端栏目 end -->
            <!-- 来源 begin -->
            <div class="form-group" id="origin"></div>
            <!-- 来源 end -->

            <div class="form-group">
              <label for="inputPassword3" class="col-sm-2 control-label">内容:</label>
              <div class="col-sm-5">
                <textarea class="input js-editor" id="editor" name="content" rows="20" ><?php echo $article['content']?></textarea>
              </div>
            </div>
            <div class="form-group">
              <label for="inputPassword3" class="col-sm-2 control-label">描述:</label>
              <div class="col-sm-9">
                <input value="<?php echo $article['description']?>" type="text" class="form-control" name="description" id="inputPassword3" placeholder="描述">
              </div>
            </div>
            <div class="form-group">
              <label for="inputPassword3" class="col-sm-2 control-label">关键字:</label>
              <div class="col-sm-5">
                <input value="<?php echo $article['keywords']?>" type="text" class="form-control" name="keywords" id="inputPassword3" placeholder="请填写关键词">
              </div>
            </div>
            <input type="hidden" name="news_id" value="<?php echo $article['news_id']?>"/>

            <div class="form-group">
              <div class="col-sm-offset-2 col-sm-10">
                <button type="button" class="btn btn-default" id="btn_edit_submit">提交</button>
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
<script>
  // 完美解决PHP带过来的数据在js模板编译中执行逻辑判断
  var titleFontColor = '<?php echo $article['title_font_color'];?>';
  var catid = '<?php echo $article['catid'];?>';
  var copyfrom = '<?php echo $article['copyfrom'];?>';
</script>
<script id="title_color_tpl" type="text/html">
  <label for="inputname" class="col-sm-2 control-label">标题颜色:</label>
  <div class="col-sm-5">
    <select class="form-control" name="title_font_color">
      <option value="">==请选择颜色==</option>
      <%for (var i = 0; i < titleColor.length; i++) {%>
        <%if (titleColor[i].value == titleFontColor) {%>
          <option value="<%=titleColor[i].value%>" selected><%=titleColor[i].label%></option>
        <%} else {%>
          <option value="<%=titleColor[i].value%>"><%=titleColor[i].label%></option>
        <%}%>
      <%}%>
    </select>
  </div>
</script>
<script id="home_navs_tpl" type="text/html">
  <label for="inputname" class="col-sm-2 control-label">所属栏目:</label>
  <div class="col-sm-5">
    <select class="form-control" name="catid">
      <%for (var i = 0; i < homeNavs.length; i++) {%>
        <%if (homeNavs[i].menu_id == catid) {%>
          <option value="<%=homeNavs[i].menu_id%>" selected><%=homeNavs[i].name%></option>
        <%} else {%>
          <option value="<%=homeNavs[i].menu_id%>"><%=homeNavs[i].name%></option>
        <%}%>
      <%}%>
    </select>
  </div>
</script>
<script id="origin_tpl" type="text/html">
  <label for="inputname" class="col-sm-2 control-label">来源:</label>
  <div class="col-sm-5">
    <select class="form-control" name="copyfrom">
      <%for (var i = 0; i < origin.length; i++) {%>
        <%if (origin[i].value == copyfrom) {%>
          <option value="<%=origin[i].value%>" selected><%=origin[i].label%></option>
        <%} else {%>
          <option value="<%=origin[i].value%>"><%=origin[i].label%></option>
        <%}%>
      <%}%>
    </select>
  </div>
</script>
<script src="<?php echo base_url();?>static/js/admin/article.js"></script>
<?php include VIEWPATH.'admin/include/footer.php';?>