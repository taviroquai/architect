<h3>Forum Demo</h3>
<div id="forum">
    <?php if (empty($categories)) { ?>
    <p>There are not categories yet.</p>
    <?php } else { ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Forum</th>
                <th class="span3">Topics</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $item) { ?>
            <tr>
                <td>
                    <a href="<?=u($url, array($param => $item->alias))?>">
                        <i class="icon-white icon-folder-open"></i>
                        <?=$item->title?>
                    </a>
                    <div class="description"><?=$item->description?></div>
                    <div class="clearfix"></div>
                    <?php $keywords = explode(',', $item->keywords); ?>
                    <?php foreach ($keywords as $word) { ?>
                    <span class="label label-important"><?=$word?></span>
                    <?php } ?>
                </td>
                <td>
                    <?=$item->total_topics?>
                    <i class="icon-white icon-comment"></i>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php } ?>
</div>
