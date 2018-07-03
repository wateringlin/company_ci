<?php
  $this->load->model('Menu_model', 'menu');
  $navs = $this->menu->getAdminMenus();
  $controller_name = $this->router->fetch_class();
?>

<!-- Navigation -->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
  <!-- Brand and toggle get grouped for better mobile display -->
  <div class="navbar-header">
    
    <a class="navbar-brand" >singcms内容管理平台</a>
    
  </div>
  <!-- Top Menu Items -->
  <ul class="nav navbar-right top-nav">
    
    <li class="dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i>  <b class="caret"></b></a>
      <ul class="dropdown-menu">
        <li>
          <a href="/admin.php?c=admin&a=personal"><i class="fa fa-fw fa-user"></i> 个人中心</a>
        </li>
       
        <li class="divider"></li>
        <li>
          <a href="<?php echo site_url('admin/login/logout');?>"><i class="fa fa-fw fa-power-off"></i> 退出</a>
        </li>
      </ul>
    </li>
  </ul>
  <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
  <div class="collapse navbar-collapse navbar-ex1-collapse">
    <ul class="nav navbar-nav side-nav nav_list">
      
      <?php foreach ($navs as $nav):?>
      <li class="<?php echo $nav['c'] === $controller_name ? 'active' : ''?>">
        <a href="<?php echo site_url('admin/').$nav['c'].'/'.$nav['f'];?>">
          <i class="fa fa-fw fa-bar-char-o"></i><?php echo $nav['name'];?>
        </a>
      </li>
      <?php endforeach;?>
    </ul>
  </div>
  <!-- /.navbar-collapse -->
</nav>