<div class="well">
    <label>Pagination Demo</label>
    <div class="pagination pagination-centered">
        <ul>
            <?php
            $class = '';
            if ($this->current == 1) $class = 'disabled' ?>
            <li class="<?=$class?>"><a href="<?=$this->getUrl($this->current - 1)?>">&laquo;</a></li>
            <?php foreach ($this->items as $i => $item) { 
                if ($this->current == $i) $item->class = 'active';
            ?>
            <li class="<?=empty($item->class) ? '' : $item->class?>">
                <a href="<?=$item->url?>"><?=$item->text?></a>
            </li>
            <?php } ?>
            <?php
            $class = '';
            if ($this->current == $this->total) $class = 'disabled' ?>
            <li class="<?=$class?>"><a href="<?=$this->getUrl($this->current + 1)?>">&raquo;</a></li>
      </ul>
    </div>
</div>
