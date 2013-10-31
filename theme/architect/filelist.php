<ul id="<?=$_id?>" class="nav nav-list" title="File List">
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
<script type="text/javascript">
jQuery(function($) {
    $('.file').click(function(e) {
        e.preventDefault();
        alert($(this).attr('data-path'));
    });
});
</script>