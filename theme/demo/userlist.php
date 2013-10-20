<ul>
    <?php while ($item = $stm->fetchObject()) { ?>
    <li><i class="icon-user"></i>
        <a draggable="true" href="#" 
           data-ui="user/<?=$item->id?>"><?=$item->email?></a>
    </li>
    <?php } ?>
</ul>