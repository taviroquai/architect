<div  id="<?=$_id?>" class="carousel slide" title="Carousel">
    <ol class="carousel-indicators">
      <li data-target="#<?=$_id?>" data-slide-to="0" class="active"></li>
      <li data-target="#<?=$_id?>" data-slide-to="1"></li>
      <li data-target="#<?=$_id?>" data-slide-to="2"></li>
    </ol>
    <!-- Carousel items -->
    <div class="carousel-inner">
        <?php foreach ($items as $item) { ?>
          <div class="item<?=$item->active ? ' active' : ''?>">
            <div><?=$item->html?></div>
          </div>
        <?php } ?>
    </div>
    <!-- Carousel nav -->
    <a class="carousel-control left" 
       href="#<?=$_id?>" data-slide="prev">&lsaquo;</a>
    <a class="carousel-control right" 
       href="#<?=$_id?>" data-slide="next">&rsaquo;</a>
</div>
<script type="text/javascript">
jQuery(function($) {
    $('.carousel').carousel();
});
</script>
