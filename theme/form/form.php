<form action="<?=$action?>" method="post">
<?php $this->slot('content', function($item) { ?>
    <?=$item?>
<?})?>
</form>