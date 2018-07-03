
<?php include VIEWPATH.'admin/include/header.php';?>

<style>
    .s_center {
      margin-left: auto;
      margin-right: auto;
    }
</style>
  <div class="s_center container col-lg-6">
    <form class="form-signin">
      <h2 class="form-signin-heading">请登录</h2>
      <label class="sr-only">用户名</label>
      <input type="text" class="form-control" name="username" placeholder="请填写用户名" required autofocus>
      <br/>
      <label class="sr-only">密码</label>
      <input type="password" name="password" id="inputPassword"   class="form-control" placeholder="请填写密码" required>
      <br/>
      <button type="button" class="btn btn-lg btn-primary btn-block" id="login">登录</button>
    </form>
  </div>

<!-- 加载登录js -->
<script src="<?php echo base_url();?>static/js/admin/login.js"></script>

<?php include VIEWPATH.'admin/include/footer.php';?>