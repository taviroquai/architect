<table class="table">
    <thead>
        <tr>
        <?php foreach ($columns as $col) { ?>
        <th><?=empty($col['label']) ? '' : $col['label']?></th>
        <?php } ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($rows as $row) { ?>
        <tr>
            <?php foreach ($row as $item) { ?>
                <?=$item?>
            <?php } ?>
        </tr>
        <?php } ?>
    </tbody>
</table>
<?php if (method_exists($this, 'render')) { ?>
<?php $this->render('content', function($item) { ?>
<div><?=$item?></div>
<?php }) ?>
<?php } ?>
