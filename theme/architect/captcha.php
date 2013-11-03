<div id="<?=$_id?>">
    <input type="text" name="_captcha" 
       style="visibility: hidden" value="<?=$code?>" />
    <?php $this->slot('content', function($item) { ?>
        <?=$item?>
    <?})?>
</div>

