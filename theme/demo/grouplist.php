<ul>
    <?php while ($item = $stm->fetchObject()) { ?>
    <li>
        <a draggable="true" href="#" 
           data-ui="group/<?=$item->id?>">
            <?=$item->name?>
        </a>
    </li>
    <?php } ?>
</ul>