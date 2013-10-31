<div id="<?=$_id?>" title="Poll">
<?php if (!p('poll1')) { ?>
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
        <input type="submit" name="poll1" value="Vote" class="btn" />
    </form>
<?php } else { ?>
    <h4>Results</h4>
    <div id="poll1" style="width: 100%; height: 300px"></div>
    <em>Powered by Morris and Rafael</em>
    <script type="text/javascript">
        jQuery(function($) {
            var data = <?=json_encode($data)?>;
            Morris.Bar({
                element: 'poll1',
                data: data,
                xkey: 'x',
                ykeys: <?=json_encode($ykeys)?>,
                labels: <?=json_encode($labels)?>
            });
        });
    </script>
<?php } ?>
</div>
