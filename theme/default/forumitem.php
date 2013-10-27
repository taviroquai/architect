<h3>Forum Item Demo</h3>
<div id="forum">
    <?php if (empty($topics)) { ?>
    <p>There are no topics yet.</p>
    <?php } else { ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Topic</th>
                <th class="span3">Posts</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($topics as $item) { ?>
            <tr>
                <td>
                    <a href="<?=u($url, array($param => $item->alias))?>">
                        <i class="icon-white icon-folder-open"></i>
                        <?=$item->title?>
                    </a>
                    <div class="clearfix"></div>
                    <?php $keywords = explode(',', $item->keywords); ?>
                    <?php foreach ($keywords as $word) { ?>
                    <span class="label label-important"><?=$word?></span>
                    <?php } ?>
                </td>
                <td>
                    <?=$item->total_posts?>
                    <i class="icon-white icon-comment"></i>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php } ?>
</div>
<h4>Add Topic</h4>
<form method="post">
    <label>Title</label>
    <input type="text" name="title" value="" placeholder="Title" />
    <textarea style="width: 100%" rows="5" name="body"
              placeholder="Text body"></textarea>
    <label>Keywords</label>
    <input type="text" name="keywords" value="" placeholder="keyword1,keyword2" />
    <input type="hidden" name="id_forum" value="<?=$forum->id?>" />
    <label></label>
    <input type="submit" name="topic" value="Send" class="btn" />
</form>