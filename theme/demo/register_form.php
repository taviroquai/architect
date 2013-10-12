<h2>Register</h2>
<form action="<?=$registerUrl?>" method="post">
    <label>Email</label>
    <input name="email" type="text" value="<?=empty($email) ? '' : $email?>" placeholder="Email" /><br />
    <label>Password</label>
    <input name="password" type="password" placeholder="Password" /><br />
    <label>Confirm Password</label>
    <input name="password_confirm" type="password" placeholder="Confirm password" /><br />
    <button>Register</button>
    
    <!-- add captcha -->
    <?=app()->createCaptcha()?>
</form>
