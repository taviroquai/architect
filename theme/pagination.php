<div id="<?=$_id?>" class="pagination pagination-centered" title="Pagination">
    <ul>
        <?php if ($current > 1) { ?>
            <li>
                <a href="<?=$previous_url?>">&laquo;</a>
            </li>
        <?php } ?>
        <?php foreach ($items as $i => $item) { 
            if ($current == $i) $item->class = 'active';
        ?>
        <li class="<?=empty($item->class) ? '' : $item->class?>">
            <a href="<?=$item->url?>"><?=$item->text?></a>
        </li>
        <?php } ?>
        <?php if ($current < $total) { ?>
            <li>
                <a href="<?=$next_url?>">&raquo;</a>
            </li>
        <?php } ?>
    </ul>
</div>