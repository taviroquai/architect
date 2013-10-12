<?php

app()->addRoute('/hello', function() {
    $message = '<h1>Hello World!<h1>';
    app()->addContent($message);
});