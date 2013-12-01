<select name="<?=$property?>">
    <?php foreach ($items as $item) { ?>
    <option value="<?=$item['id']?>" 
            <?=in_array($item['id'], $selected) ? 'selected="selected"' : ''?>>
            <?=$item[$prop_label]?>
    </option>
    <?php } ?>
</select>