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
            <br /><span><?=basename($item)?></span>
        </a>
        <?php } else { ?>
        <a href="#" class="file" title="<?=basename($item)?>"
           data-path="<?=$item?>">
            <?php if (getimagesize($item)) { ?>
            <img src="<?=u('/demo', array('img' => $item))?>" />
            <?php } else { ?>
            <i class="icon-4x icon-file"></i>
            <br /><span><?=basename($item)?></span>
            <?php } ?>
        </a>
        <?php } ?>
        </div>
    </div>
    <?php } ?>
</div>
<div class="clearfix"></div>
<h4>PHP</h4>
<pre>
$tmpl = BASE_PATH.'/theme/default/filegallery.php';
$explorer = app()->createFileExplorer($tmpl);
$explorer->set('base', BASE_PATH.'/theme/demo/img');
$explorer->set('url', '/demo');
c($explorer);
</pre>
<h4>Default Template</h4>
<pre>
theme/default/filegallery.php
</pre>
<script type="text/javascript">
jQuery(function($) {
    $('.file').click(function(e) {
        e.preventDefault();
        alert($(this).attr('data-path'));
    });
});
</script>