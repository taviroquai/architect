<h3>Tree View Demo</h3>
<ul class="nav nav-list">
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
<h4>PHP</h4>
<pre>
$treeview = app()->createTreeView();
$root = array('label' => 'root', 'nodes' => array());
$root['nodes'][] = array('label' => 'level 1', 'nodes' => array());
$root['nodes'][0][nodes][] = array('label' => 'level 1.1');
$root['nodes'][] = array('label' => 'level 2');
$treeview->set('tree', $root);
c($treeview);
</pre>
<h4>Default Template</h4>
<pre>
theme/default/treeview.php
</pre>
<script type="text/javascript">
jQuery(function ($) {
	$('label.tree-toggler').click(function () {
		$(this).parent().children('ul.tree').toggle(300);
	});
});
</script>