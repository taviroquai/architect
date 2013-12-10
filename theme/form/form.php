<form action="<?=$action?>" method="post">
<?php $this->render('content', function($item) { ?>
    <?=$item?>
<?php })?>
</form>