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
              <i class="fa fa-dashboard"></i>  <a href="/admin.php?c=content">文章管理</a>
            </li>
            <li class="active">
              <i class="fa fa-table"></i>文章列表
            </li>
          </ol>
        </div>
      </div>
      <!-- /.row -->
      <div >
        <button id="add" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>添加 </button>
      </div>
      <div class="row">
        <form action="/admin.php" method="get">
          <div class="col-md-3">
            <div class="input-group">
              <span class="input-group-addon">栏目</span>
              <select class="form-control" name="catid">
                <option value='' >全部分类</option>
                <foreach name="webSiteMenu" item="sitenav">
                  <option value="{$sitenav.menu_id}" >{$sitenav.name}</option>
                </foreach>
              </select>
            </div>
          </div>
          <input type="hidden" name="c" value="content"/>
          <input type="hidden" name="a" value="index"/>
          <div class="col-md-3">
            <div class="input-group">
              <input class="form-control" name="title" type="text" value="" placeholder="文章标题" />
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
                  <th id="singcms-checkbox-all" width="10"><input type="checkbox"/></th>
                  <th width="14">排序</th><!--6.7-->
                  <th>id</th>
                  <th>标题</th>
                  <th>栏目</th>
                  <th>来源</th>
                  <th>封面图</th>
                  <th>时间</th>
                  <th>状态</th>
                  <th>操作</th>
                </tr>
                </thead>
                <tbody id="article_list">
                

                </tbody>
              </table>
              <nav>

              <ul class="pagination" id="pagination"></ul>

            </nav>
              <div>
                <button  id="button-listorder" type="button" class="btn btn-primary dropdown-toggle" ><span class="glyphicon glyphicon-resize-vertical" aria-hidden="true"></span>更新排序</button>
              </div>
            </form>
            <div class="input-group">
              <select class="form-control" name="position_id" id="select-push">
                <option value="0">请选择推荐位进行推送</option>
                <foreach name="positions" item="position">
                  <option value="{$position.id}">{$position.name}</option>
                </foreach>
              </select>
              <button id="singcms-push" type="button" class="btn btn-primary">推送</button>
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
<script id="article_list_tpl" type="text/html">
  <%for (var i = 0; i < articleList.length; i++) {%>
    <tr>
      <td>
        <input type="checkbox" name="pushcheck" value="<%=articleList[i].news_id%>">
      </td>
      <td>
        <input size="4" type="text" name='listorder[<%=articleList[i].news_id%>]' value="<%=articleList[i].listorder%>"/>
      </td><!--6.7-->
      <td><%=articleList[i].news_id%></td>
      <td><%=articleList[i].title%></td>
      <!-- <td>{$new.catid|getCatName=$webSiteMenu,###}</td> -->
      <td><%=articleList[i].catid%></td>
      <!-- <td>{$new.copyfrom|getCopyFromById}</td> -->
      <td><%=articleList[i].copyfrom%></td>
      <!-- <td>{$new.thumb|isThumb}</td> -->
      <td><%=articleList[i].thumb%></td>
      <!-- <td>{$new.create_time|date="Y-m-d H:i",###}</td> -->
      <td><%=articleList[i].create_time%></td>
      <!-- <td><span  attr-status="<if condition="$new['status'] eq 1">0<else/>1</if>"  attr-id="{$new.news_id}" class="sing_cursor singcms-on-off" id="singcms-on-off" >{$new.status|status}</span></td> -->
      <td>
        <span attr-status="" attr-id="<%=articleList[i].news_id%>" class="sing_cursor singcms-on-off" id="singcms-on-off" ><%=articleList[i].status%></span>
      </td>
      <td>
        <span class="sing_cursor glyphicon glyphicon-edit" aria-hidden="true" id="singcms-edit" attr-id="{$new.news_id}" ></span>
        <a href="javascript:void(0)" id="singcms-delete"  attr-id="{$new.news_id}"  attr-message="删除">
          <span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>
        </a>
        <a target="_blank" href="/index.php?c=detail&a=view&id={$new.news_id}" class="sing_cursor glyphicon glyphicon-eye-open" aria-hidden="true"  ></a>
      </td>
    </tr>
  <%}%>
</script>
<script src="<?php echo base_url();?>static/js/admin/article.js"></script>
<?php include VIEWPATH.'admin/include/footer.php'?>