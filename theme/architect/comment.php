<div id="<?=$_id?>" title="Post Comment">
    <form method="post">
        <label>Name</label>
        <input type="text" name="name" />
        <label>Email</label>
        <input type="text" name="email" />
        <label>Comment</label>
        <textarea name="comment" class="span4" rows="6"></textarea>
        <label></label>
        <button type="sybmit" class="btn">Send</button>
    </form>
    <?php $this->slot('content', function($item) { ?>
        <?=$item?>
    <?})?>
</div>

