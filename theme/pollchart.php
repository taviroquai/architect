<div id="<?=$_id?>" title="Poll">
    <?php if (!$show_votes) { ?>
        <form method="post">
            <?php foreach ($data as $item) { ?>
            <label class="checkbox">
                <input type="radio" name="x" value="1" />
                <?=$item['x']?>
            </label>
            <?php } ?>
            <label class="checkbox">
                <input type="radio" name="x" value="1" checked />
                Other
            </label>
            <input type="submit" name="<?=$input_name?>" value="Vote" class="btn" />
        </form>
    <?php } else { ?>
        <div id="<?=$_id?>_result"></div>
        <script type="text/javascript">
            jQuery(function($) {
                var data = <?=json_encode($data)?>;
                Morris.Bar({
                    element: '<?=$_id?>_result',
                    data: data,
                    xkey: 'x',
                    ykeys: <?=json_encode($ykeys)?>,
                    labels: <?=json_encode($labels)?>
                });
            });
        </script>
    <?php } ?>
    <?php $this->slot('content', function($item) { ?>
        <?=$item?>
    <?php })?>
</div>
