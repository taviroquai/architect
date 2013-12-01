<?php foreach ($items as $item) { ?>
<label class="radio">
<input type="radio" name="<?=$property?>[<?=$item['id']?>]"
        <?=in_array($item['id'], $selected)?' checked="checked"':''?>>
        <?=$item[$prop_label]?>
</label>
<?php } ?>
