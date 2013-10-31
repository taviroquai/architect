<ul id="<?=$_id?>" class="nav nav-list" title="Tree View">
    <?php foreach ($tree['nodes'] as $lvl1) { ?>
    <?php if (!empty($lvl1['nodes'])) { ?>
    <li><label class="tree-toggler nav-header"><?=$lvl1['label']?></label>
        <ul class="nav nav-list tree">
            <?php foreach ($lvl1['nodes'] as $lvl2) { ?>
            <?php if (!empty($lvl2['nodes'])) { ?>
            <li><label class="tree-toggler nav-header"><?=$lvl2['label']?></label>
                <ul class="nav nav-list tree">
                    <?php foreach ($lvl2['nodes'] as $lvl3) { ?>
                    <?php if (empty($lvl3['nodes'])) { ?>
                    <li>
                        <a href="<?=empty($lvl3['href']) ? '#' : $lvl3['href']?>">
                        <?=$lvl3['label']?>
                        </a>
                    </li>
                    <?php } ?>
                    <?php } ?>
                    <li class="divider"></li>
                </ul>
            </li>
            <?php } ?>
            <?php if (empty($lvl2['nodes'])) { ?>
            <li>
                <a href="<?=empty($lvl2['href']) ? '#' : $lvl2['href']?>">
                    <?=$lvl2['label']?>
                </a>
            </li>
            <?php } ?>
            <?php } ?>
            <li class="divider"></li>
        </ul>
    </li>
    <?php } ?>
    <?php if (empty($lvl1['nodes'])) { ?>
    <li>
        <a href="<?=empty($lvl1['href']) ? '#' : $lvl1['href']?>">
        <?=$lvl1['label']?>
        </a>
    </li>
    <?php } ?>
    <?php } ?>
    <li class="divider"></li>
</ul>
<script type="text/javascript">
jQuery(function ($) {
	$('label.tree-toggler').click(function () {
		$(this).parent().children('ul.tree').toggle(300);
	});
});
</script>