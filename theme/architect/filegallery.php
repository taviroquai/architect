<div id="<?=$_id?>" class="gallery" title="File Gallery">
    <?php if ($path != $base) { ?>
    <div class="item pull-left thumbnail">
        <div class="thumb">
            <a href="<?=u($url, array($param => $parent))?>">
                <i class="icon-4x icon-backward"></i>
            </a>
        </div>
        <span>.. <?=$parent?></span>
    </div>
    <?php } ?>
    <?php foreach ($files as $item) { ?>
    <div class="item pull-left thumbnail">
        <div class="thumb">
        <?php if (is_dir($item)) {
            $folder = str_replace($base, '', $item);
        ?>
        <a href="<?=u($url, array($param => $folder))?>" 
           class="directory" title="<?=basename($item)?>">
            <i class="icon-4x icon-folder-open"></i>
        </a>
        <?php } else { ?>
        <a href="#" class="file" data-path="<?=$this->translatePath($item)?>">
            <?php if (getimagesize($item)) { ?>
            <img src="<?=$this->translatePath($item)?>" />
            <?php } else { ?>
            <i class="icon-4x icon-file"></i>
            <?php } ?>
        </a>
        <?php } ?>
        </div>
    </div>
    <?php } ?>
</div>
<div class="clearfix"></div>
<?php $this->slot('content', function($item) { ?>
    <?=$item?>
<?})?>
