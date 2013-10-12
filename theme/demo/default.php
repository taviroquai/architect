<!-- Main hero unit for a primary marketing message or call to action -->
<div class="hero-unit">
  <h1><?=t('TITLE')?></h1>
  
  <p>Architect PHP Framework uses a pragmatic and modular Web development approuch. The idea is to create a small API but with the most common features in web development.</p>
  
  <h2>Quick Start</h2>
  <ol>
      <li>Create a new folder in <strong>module/enable/hello</strong></li>
      <li>Create a new file in <strong>module/enable/hello/config.php</strong></li>
      <li>Add the following content to config.php
          <pre>
&lt;?php

app()->addRoute('/hello', function() {
    $message = '&lt;h1&gt;Hello World!&lt;h1&gt;';
    app()->addContent($message);
});
          </pre>
      </li>
      <li>Go to <a href="<?=app()->url('/hello')?>">hello</a> page</li>
  </ol>
</div>