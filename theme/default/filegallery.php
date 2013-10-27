<h3>File Gallery Demo</h3>
<div class="gallery">
    <?php if ($path != $base) { ?>
    <div class="item pull-left thumbnail">
        <div class="thumb">
            <a href="<?=u($url, array($param => $parent))?>">
                <i class="icon-4x icon-backward"></i>
            </a>
        </div>
        <span>.. <?=$parent?></span>
    </div>
    <?php } ?>
    <?php foreach ($files as $item) { ?>
    <div class="item pull-left thumbnail">
        <div class="thumb">
        <?php if (is_dir($item)) {
            $folder = str_replace($base, '', $item);
        ?>
        <a href="<?=u($url, array($param => $folder))?>" 
           class="directory" title="<?=basename($item)?>">
            <i class="icon-4x icon-folder-open"></i>
        </a>
        <?php } else { ?>
        <a href="#" class="file" title="<?=basename($item)?>"
           data-path="<?=$item?>">
            <?php if (getimagesize($item)) { ?>
            <img src="<?=u('/demo', array('img' => $item))?>" />
            <?php } else { ?>
            <i class="icon-4x icon-file"></i>
            <?php } ?>
        </a>
        <?php } ?>
        </div>
        <span><?=basename($item)?></span>
    </div>
    <?php } ?>
</div>
<div class="clearfix"></div>
<script type="text/javascript">
jQuery(function($) {
    $('.file').click(function(e) {
        e.preventDefault();
        alert($(this).attr('data-path'));
    });
});
</script>