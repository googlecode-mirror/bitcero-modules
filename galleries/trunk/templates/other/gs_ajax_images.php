<div class="gs_options">
    <?php if($search!=''): ?>
    <?php echo sprintf(__('Search results for: %s','galleries'), '<strong>'.$search.'</strong>'); ?> &nbsp; | &nbsp;
    <strong><a href="#" onclick="gsController.load_galleries(1,''); return false;"><?php _e('Show all','galleries'); ?></a></strong> &nbsp; | &nbsp;
    <input type="hidden" name="search" id="search-inp" value="<?php echo $search; ?>" />
    <?php endif; ?>
    <?php _e('Search:','galleries'); ?>
    <input name="srh" id="gs-search-gk" type="textbox" size="10" value="<?php echo $search; ?>" />
    <input type="button" id="gs-search-gb" value="<?php _e('Go!','galleries'); ?>" />
</div>
<div class="gs_ajax_images">
    <?php foreach($images as $img): ?>
    <div class="gs_img_item">
        <img src="<?php echo $img['thumbnail']; ?>" title="<?php echo $img['title']; ?>" class="img-<?php echo $img['id']; ?>" />
        <span class="image"><?php echo $img['image']; ?></span>
        <span class="thumbnail"><?php echo $img['thumbnail']; ?></span>
        <span class="user"><?php echo $img['thumbuser']; ?></span>
        <span class="search"><?php echo $img['thumbsrh']; ?></span>
        <span class="desc"><?php echo $img['desc']; ?></span>
    </div>
    <?php endforeach; ?>
</div>
<?php echo $nav->render(false); ?>
<?php echo $xoopsSecurity->getTokenHTML(); ?>