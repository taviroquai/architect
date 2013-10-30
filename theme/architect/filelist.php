<h3>File Explorer Demo</h3>
<ul class="nav nav-list">
    <?php if ($path != $base) { ?>
    <li>
        <a class="label" href="<?=u($url, array($param => $parent))?>">
            <i class="icon icon-backward"></i>
            .. Back
        </a>
    </li>
    <?php } ?>
    <?php foreach ($files as $item) { ?>
    <li>
        <?php if (is_dir($item)) {
            $folder = str_replace($base, '', $item);
        ?>
        <a href="<?=u($url, array($param => $folder))?>" class="directory">
            <i class="icon icon-folder-open"></i>
            <?=basename($item)?>
        </a>
        <?php } else { ?>
        <a href="#" class="file" data-path="<?=$item?>">
            <?php if (getimagesize($item)) { ?>
            <i class="icon icon-picture"></i>
            <?php } else { ?>
            <i class="icon icon-file"></i>
            <?php } ?>
            <?=basename($item)?>
        </a>
        <?php } ?>
    </li>
    <?php } ?>
</ul>
<h4>PHP</h4>
<pre>
$explorer = app()->createFileExplorer();
$explorer->set('base', BASE_PATH.'/theme');
$explorer->set('url', '/demo');
c($explorer);
</pre>
<h4>Default Template</h4>
<pre>
theme/default/filelist.php
</pre>
<script type="text/javascript">
jQuery(function($) {
    $('.file').click(function(e) {
        e.preventDefault();
        alert($(this).attr('data-path'));
    });
});
</script>