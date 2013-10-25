<h3>Download attachment Demo</h3>
<a href="<?=$url?>">Download</a>
<h4>PHP</h4>
<pre>
if (g('dl')) {
    app()->download(BASE_PATH.'/theme/default/img/'.g('dl'));
}
</pre>