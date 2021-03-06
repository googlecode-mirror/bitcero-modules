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