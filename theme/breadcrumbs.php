<ul id="<?=$_id?>" class="breadcrumb" title="Breadcrumbs">
    <?php 
    $i = 0;
    while ($i < count($items)) {
        $item = $items[$i];
        if ($i < count($items)-1) $divider = ' <span class="divider">/</span>';
        else $divider = '';

        if ($item->active) { ?>
        <li class="active"><?=$item->text?><?=$divider?></li>
        <?php } else { ?>
        <li><a href="<?=$item->url?>"><?=$item->text?></a><?=$divider?></li>
    <?php 
        }
        $i++;
    } ?>
</ul>
