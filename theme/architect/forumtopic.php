<div id="<?=$_id?>" title="Forum Topic">
    <div>
        <?php if (empty($posts)) { ?>
        <p>There are no posts yet.</p>
        <?php } else { ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Post</th>
                    <th class="span3">Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($posts as $item) { ?>
                <tr>
                    <td>
                        <div class="post"><?=$item->body?></div>
                    </td>
                    <td><?=$item->datetime?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php } ?>
    </div>
    <?php if (!empty($topic->id)) { ?>
        <h4>Add Post</h4>
        <form method="post">
            <textarea style="width: 100%" rows="5" name="post[body]"
                      placeholder="Text body"></textarea>
            <input type="hidden" name="post[id_topic]" value="<?=$topic->id?>" />
            <button type="submit" class="btn">Send</button>
        </form>
    <?php } ?>
    <?php $this->slot('content', function($item) { ?>
        <?=$item?>
    <?})?>
</div>