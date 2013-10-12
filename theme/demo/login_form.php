<h2>Log in</h2>
<form action="<?=$loginUrl?>" method="post">
	<label>Email</label>
  	<input type="text" name="email" placeholder="Email" value="<?=empty($email) ? '' : $email?>">
    <label>Password</label>
  	<input type="password" name="password" placeholder="Password">
  	<label></label>
  	<button type="submit" class="btn">Sign in</button>
</form>