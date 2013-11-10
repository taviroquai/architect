<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        
        <?php $this->slot('css', function($item) { ?>
        <link href="<?=$item?>" rel="stylesheet" />
        <?})?>
        
        <?php $this->slot('js', function($item) { ?>
        <script src="<?=$item?>" type="text/javascript"></script>
        <?})?>
        
    </head>
    <body>
        
        <?php $this->slot('content', function($item) { ?>
        <div><?=$item?></div>
        <?})?>
        
    </body>
</html>