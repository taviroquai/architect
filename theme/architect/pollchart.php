<h3>Poll Demo</h3>
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
    <label>Click Vote to see the results</label>
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
<h4>PHP</h4>
<pre>
$poll = app()->createPoll();
$poll->setVotes("Candidate 1", 100);
$poll->setVotes("Candidate 2", 200);
$poll->set('labels', array('Votes'));
c($poll);
</pre>
<h4>Default Template</h4>
<pre>theme/default/linechart.php</pre>
