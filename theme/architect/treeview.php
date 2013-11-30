<?=$this->tree->saveXML()?>
<ul id="<?=$_id?>" class="nav nav-list" title="Tree View">
    <?php foreach ($this->tree->documentElement->childNodes as $lvl1) { ?>
    <?php if ($lvl1->hasChildNodes()) { ?>
    <li><label class="tree-toggler nav-header"><?=$lvl1->getAttribute('label')?></label>
        <ul class="nav nav-list tree">
            <?php foreach ($lvl1->childNodes as $lvl2) { ?>
            <?php if ($lvl2->hasChildNodes()) { ?>
            <li><label class="tree-toggler nav-header"><?=$lvl2->getAttribute('label')?></label>
                <ul class="nav nav-list tree">
                    <?php foreach ($lvl2->childNodes as $lvl3) { ?>
                    <?php if (!$lvl3->hasChildNodes()) { ?>
                    <li>
                        <a href="<?=$lvl3->hasAttribute('href') ? '#' : $lvl3->getAttribute('href')?>">
                        <?=$lvl3->getAttribute('label')?>
                        </a>
                    </li>
                    <?php } ?>
                    <?php } ?>
                    <li class="divider"></li>
                </ul>
            </li>
            <?php } ?>
            <?php if (!$lvl2->hasChildNodes()) { ?>
            <li>
                <a href="<?=$lvl2->hasAttribute('href') ? '#' : $lvl2->getAttribute('href')?>">
                    <?=$lvl2->getAttribute('label')?>
                </a>
            </li>
            <?php } ?>
            <?php } ?>
            <li class="divider"></li>
        </ul>
    </li>
    <?php } ?>
    <?php if (!$lvl1->hasChildNodes()) { ?>
    <li>
        <a href="<?=$lvl1->hasAttribute('href') ? '#' : $lvl1->getAttribute('href')?>">
        <?=$lvl1->getAttribute('label')?>
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
<?php $this->slot('content', function($item) { ?>
    <?=$item?>
<?php })?>