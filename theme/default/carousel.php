<h3>Carousel Demo</h3>
<div id="myCarousel" class="carousel slide" 
     style="width: 100%; height: 250px;">
    <ol class="carousel-indicators">
      <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
      <li data-target="#myCarousel" data-slide-to="1"></li>
      <li data-target="#myCarousel" data-slide-to="2"></li>
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
       href="#myCarousel" data-slide="prev">&lsaquo;</a>
    <a class="carousel-control right" 
       href="#myCarousel" data-slide="next">&rsaquo;</a>
</div>
<em>Powered by Bootstrap Carousel</em>
<h4>PHP</h4>
<pre>
c(BASE_URL.'theme/demo/carousel/style.css', 'css');
$carousel = app()->createCarousel();
$carousel->addItem('&lt;img src="'.BASE_URL.'theme/demo/img/carousel1.jpg" /&gt;', 1);
c($carousel);
</pre>
<h4>JS</h4>
<pre>
$('.carousel').carousel();
</pre>
<script type="text/javascript">
    jQuery(function($) {
        $('.carousel').carousel();
    });
</script>