<?php if (!empty($onclick)) { ?>
<a class="<?=$class?>" onclick="<?=$onclick?>"><?=$label?></a>
<?php } else { ?>
<a class="<?=$class?>" href="<?=$action?>"><?=$label?></a>
<?php } ?>

