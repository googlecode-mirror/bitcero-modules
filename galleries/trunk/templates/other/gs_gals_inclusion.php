<script type='text/javascript'>
$(function(){
$(".pic_qview a").colorbox(lb_params);
$(".gs_gsl_item a").colorbox(lb_params);
});
</script>
<div class="gs_gal_container">
<?php foreach($images as $img): ?>
    <div class="gs_gsl_item">
        <a href="<?php echo $img['image']; ?>" rel="gs_gallery"><img src="<?php echo $img['thumbnail']; ?>" alt="<?php echo $img['title']; ?>" /></a>
    </div>
<?php endforeach; ?>
    <div class="gs_clearer"></div>
    <?php if($full=='true'): ?><?php echo $nav->render(false); ?><?php endif; ?>
    <a href="<?php echo $set['link']; ?>" class="gs_view_gal"><?php _e('View Gallery','galleries'); ?></a>
</div>
