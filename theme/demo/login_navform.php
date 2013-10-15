<form class="navbar-form pull-right"  method="post"
      action="<?=empty($loginUrl) ? '' : $loginUrl?>">
  <input class="span2" type="text" name="email" placeholder="Email" 
         value="admin@domain.com">
  <input class="span2" type="password" name="password" placeholder="Password"
         value="123456">
  <input type="hidden" name="login" value="1" />
  <button type="submit" class="btn">Sign in</button>
</form>