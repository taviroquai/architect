<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        
        <?php $this->slot('css', function($item) { ?>
        <link href="<?php echo $item; ?>" rel="stylesheet" />
        <?})?>
        
        <?php $this->slot('js', function($item) { ?>
        <script src="<?php echo $item; ?>" type="text/javascript"></script>
        <?})?>
        
    </head>
    <body>
        
        <?php $this->slot('content', function($item) { ?>
        <div><?php echo $item; ?></div>
        <?})?>
        
    </body>
</html>