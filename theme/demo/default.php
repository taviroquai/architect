<!-- Main hero unit for a primary marketing message or call to action -->
<div class="hero-unit">
  <h1><?=t('TITLE')?></h1>
  
  <h2>Quick Start</h2>
  <ol>
      <li>Create a new folder in <strong>module/enable/hello</strong></li>
      <li>Create a new file in <strong>module/enable/hello/config.php</strong></li>
      <li>Add the following content
          <pre>
&lt;?php

app()->addRoute('/hello', function() {
    $message = '&lt;h1&gt;Hello World!&lt;h1&gt;';
    app()->addContent($message);
});
          </pre>
      </li>
      <li>Go to <a href="<?=app()->url('/hello')?>">hello</a> page</li>
      <li>Also take a look at <a href="<?=app()->url('/demo')?>">demo</a> page</li>
  </ol>
</div>