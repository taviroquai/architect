<div id="<?=$_id?>" title="Forum">
    <div>
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
                        <a href="<?=u($url.'/'.$item->alias)?>">
                            <i class="icon-folder-open"></i>
                            <?=$item->title?>
                        </a>
                        <div class="description"><?=$item->description?></div>
                        <div class="clearfix"></div>
                        <div class="pull-right">
                            <?php $keywords = explode(',', $item->keywords); ?>
                            <?php foreach ($keywords as $word) { ?>
                            <span class="label label-important"><?=$word?></span>
                            <?php } ?>
                        </div>
                        <div class="clearfix"></div>
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
    <?php $this->slot('content', function($item) { ?>
        <?=$item?>
    <?})?>
</div>
