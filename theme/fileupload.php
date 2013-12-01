<div id="<?=$_id?>" class="fileupload fileupload-new" data-provides="fileupload">
    <div class="fileupload-new thumbnail" style="width: 50px; height: 50px;">
        <img src="<?=$default_img?>" />
    </div>
    <div class="fileupload-preview fileupload-exists thumbnail" 
         style="width: 50px; height: 50px;"></div>
    <span class="btn btn-file">
        <span class="fileupload-new">Select image</span>
        <span class="fileupload-exists">Change</span>
        <input type="file" name="<?=$name?>" />
    </span>
    <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">
        Remove
    </a>
    <?php $this->slot('content', function($item) { ?>
        <?=$item?>
    <?php })?>
</div>
