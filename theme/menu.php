<ul class="<?=$cssClass?>">
    <?php foreach ($items as $item) { ?>
    <li <?=!empty($item->cssClass) ? 'class="'.$item->cssClass.'"' : ''?>>
        <a href="<?=$item->url?>"><?=$item->text?></a>
    </li>
    <?php } ?>
</ul>