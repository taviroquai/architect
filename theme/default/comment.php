<h3>Comment Form Demo</h3>
<div id="send_comment">
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
</div>
<h4>PHP</h4>
<pre>
$form = app()->createCommentForm();
c($form);
</pre>