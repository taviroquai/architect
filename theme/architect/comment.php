<div id="<?=$_id?>" title="Post Comment">
    <form method="post">
        <label>Name</label>
        <input type="text" name="comment[name]" />
        <label>Email</label>
        <input type="text" name="comment[email]" />
        <label>Comment</label>
        <textarea name="comment[body]" class="span4" rows="6"></textarea>
        <label></label>
        <button type="submit" class="btn">Send</button>
    </form>
    <?php $this->slot('content', function($item) { ?>
        <?=$item?>
    <?php })?>
</div>

