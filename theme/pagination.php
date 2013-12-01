<div id="<?=$_id?>" class="pagination pagination-centered" title="Pagination">
    <ul>
        <?php if ($this->current > 1) { ?>
            <li>
                <a href="<?=$this->getUrl($this->current - 1)?>">&laquo;</a>
            </li>
        <?php } ?>
        <?php foreach ($this->items as $i => $item) { 
            if ($this->current == $i) $item->class = 'active';
        ?>
        <li class="<?=empty($item->class) ? '' : $item->class?>">
            <a href="<?=$item->url?>"><?=$item->text?></a>
        </li>
        <?php } ?>
        <?php if ($this->current < $this->total) { ?>
            <li>
                <a href="<?=$this->getUrl($this->current + 1)?>">&raquo;</a>
            </li>
        <?php } ?>
    </ul>
    <?php $this->slot('content', function($item) { ?>
        <?=$item?>
    <?php })?>
</div>