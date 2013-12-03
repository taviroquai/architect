<table class="table">
    <thead>
        <tr>
        <?php foreach ($columns as $col) { ?>
        <th><?=empty($col['label']) ? '' : $col['label']?></th>
        <?php } ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($records as $record) { ?>
        <tr>
            <?php foreach ($columns as $col) { ?>
                <?php switch ($col['type']) {
                    case 'action':
                        $v = $this->createActionButton($col, $record);
                        echo '<td style="width: 30px">'.$v.'</td>';
                        break;
                    default:
                        $v = $this->createCellValue($col, $record);
                        echo '<td>'.$v.'</td>';
                } ?>
            <?php } ?>
        </tr>
        <?php } ?>
    </tbody>
</table>
<?php $this->slot('content', function($item) { ?>
    <?=$item?>
<?php })?>
