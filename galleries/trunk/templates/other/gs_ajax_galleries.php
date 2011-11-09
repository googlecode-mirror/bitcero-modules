<div class="gs_options">
    <?php _e('Search:','galleries'); ?>
    <input name="srh" type="textbox" size="10" value="" />
</div>
<div class="gs_ajax_gals">
    <div class="gs_heads">
        <div><?php _e('ID','galleries'); ?></div>
        <div><?php _e('Gallery','galleries'); ?></div>
    </div>
    <?php foreach($galleries as $gal): ?>
    <div class="gs_item">
        <div class="gid"><?php echo $gal['id']; ?></div>
        <div class="gtitle">
            <?php echo $gal['title']; ?>
            <span class="gdate">(<?php _e('Created:','galleries'); ?> <?php echo $gal['date']; ?>)</span>
            <span class="goptions"></span>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php echo $nav->render(false); ?>
<?php echo $xoopsSecurity->getTokenHTML(); ?>