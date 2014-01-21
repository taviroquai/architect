<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        
        <?php $this->render('css', function($item) { ?>
        <link href="<?php echo $item; ?>" rel="stylesheet" />
        <?})?>
        
        <?php $this->render('js', function($item) { ?>
        <script src="<?php echo $item; ?>" type="text/javascript"></script>
        <?})?>
        
    </head>
    <body>
        
        <?php $this->render('content', function($item) { ?>
        <div><?php echo $item; ?></div>
        <?})?>
        
    </body>
</html>