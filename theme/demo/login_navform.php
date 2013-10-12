<form class="navbar-form pull-right" action="<?=empty($loginUrl) ? '' : $loginUrl?>" method="post">
  <input class="span2" type="text" name="email" placeholder="Email">
  <input class="span2" type="password" name="password" placeholder="Password">
  <input type="hidden" name="login" value="1" />
  <button type="submit" class="btn">Sign in</button>
</form>