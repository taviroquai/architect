<ul id="<?=$_id?>" class="nav nav-list" title="File List">
    <?php if ($path != $base) { ?>
    <li>
        <a class="label" href="<?=$this->getLink($url, array($param => $parent))?>">
            <i class="icon icon-backward"></i>
            .. Back
        </a>
    </li>
    <?php } ?>
    <?php foreach ($files as $item) { ?>
    <li>
        <?php if (is_dir($item)) {
            $folder = str_replace($base, '', $item);
        ?>
        <a href="<?=$this->getLink($url, array($param => $folder))?>" 
           class="directory">
            <i class="icon icon-folder-open"></i>
            <?=basename($item)?>
        </a>
        <?php } else { ?>
        <a href="#" class="file" data-path="<?=$this->translatePath($item)?>">
            <?php if (getimagesize($item)) { ?>
            <i class="icon icon-picture"></i>
            <?php } else { ?>
            <i class="icon icon-file"></i>
            <?php } ?>
            <?=basename($item)?>
        </a>
        <?php } ?>
    </li>
    <?php } ?>
</ul>
<?php $this->slot('content', function($item) { ?>
    <?=$item?>
<?php })?>
