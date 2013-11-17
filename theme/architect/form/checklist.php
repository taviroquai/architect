<?php foreach ($items as $item) { ?>
<label class="checkbox <?=$class?>">
<input type="checkbox" name="<?=$property?>[<?=$item['id']?>]"
        <?=in_array($item['id'], $selected)?' checked="checked"':''?>">
        <?=$item[$prop_label]?>
</label>
<?php } ?>
