<div id="<?=$_id?>" title="Post Comment">
    <form method="post">
        <label>Name</label>
        <input type="text" name="comment[name]" value="<?=$name?>" />
        <label>Email</label>
        <input type="text" name="comment[email]" value="<?=$email?>" />
        <label>Comment</label>
        <textarea name="comment[body]" class="span4" rows="6"><?=$body?></textarea>
        
        <?php $this->render('content', function($item) { ?>
        <div><?=$item?></div>
        <?php }) ?>
        
        <label></label>
        <button type="submit" class="btn">Send</button>
    </form>
</div>

