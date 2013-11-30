<div id="<?=$_id?>" class="gallery" title="File Gallery">
    <?php foreach ($files as $item) { ?>
        <?php if (!is_dir($item) && getimagesize($item)) { ?>
        <div class="item pull-left thumbnail">
            <div class="thumb">
                <a href="<?=$this->translatePath($item).'/'.basename($item)?>" 
                   class="file" rel="lightbox-<?=$_id?>">
                    <img src="<?=$this->translatePath($item).'/thumb/'.basename($item);?>" />
                </a>
            </div>
        </div>
        <?php } ?>
    <?php } ?>
</div>
<div class="clearfix"></div>
<?php $this->slot('content', function($item) { ?>
    <?=$item?>
<?php })?>
