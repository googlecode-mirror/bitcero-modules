<div class="gs_options">
    <?php _e('Search:','galleries'); ?>
    <input name="srh" type="textbox" size="10" value="" />
</div>
<div class="gs_ajax_gals">
    <?php foreach($galleries as $gal): ?>
    <div class="gs_item">
        <strong><?php echo $gal['title']; ?></strong>
    </div>
    <?php endforeach; ?>
</div>