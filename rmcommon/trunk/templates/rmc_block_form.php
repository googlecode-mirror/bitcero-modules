<div id="block-config-form">
    <fieldset class="left">
        <legend><?php _e('Block General Settings','rmcommon'); ?></legend>
        <label for="bk-name"><?php _e('Block Title','rmcommon'); ?></label>
        <input type="text" name="bk_name" size="50" value="<?php echo $block->getVar('name'); ?>" />
        <div class="left">
        <label for="bk-pos"><?php _e('Block position','rmcommon'); ?></label>
        <select name="bk_pos" id="bk-pos">
            <?php foreach($positions as $pos): ?>
            <option value="<? echo $pos['id_position']; ?>"<?php echo $block->getVar('canvas')==$pos['id_position']?' selected="selected"' : ''; ?>><?php echo $pos['name']; ?></option>
            <?php endforeach; ?>
        </select>
        </div>
        <div class="left">
            <label for="bk-weight"><?php _e('Block weight','rmcommon'); ?></label>
            <input type="text" name="bk_weight" id="bk-weight" value="<?php echo $block->getVar('weight'); ?>" />
        </div>
        <div class="left">
            <label for="bk-visible"><?php _e('Visible','rmcommon'); ?></label>
            <label style="display: inline;"><input type="radio" value="1" name="bk_visible" id="bk-visible" checked="<?php echo $block->getVar('visible')==1?'checked':''; ?>" /> <?php _e('Yes','rmcommon'); ?></label>
            <label style="display: inline;"><input type="radio" value="0" name="bk_visible" checked="<?php echo $block->getVar('visible')==0?'checked':''; ?>" /> <?php _e('No','rmcommon'); ?></label>
        </div>
        <div class="left">
            <label for="bk-cache"><?php _e('Cache lifetime','rmcommon'); ?></label>
            <select size="1" name="bk_cache" id="bk-cache">
                <option value="0" selected="selected"><?php _e('No Cache','rmcommon'); ?></option>
                <option value="30"><?php _e('30 seconds','rmcommon'); ?></option>
                <option value="60"><?php _e('1 minute','rmcommon'); ?></option>
                <option value="300"><?php _e('5 minutes','rmcommon'); ?></option>
                <option value="1800"><?php _e('30 minutes','rmcommon'); ?></option>
                <option value="3600"><?php _e('1 hour','rmcommon'); ?></option>
                <option value="18000"><?php _e('5 hours','rmcommon'); ?></option>
                <option value="86400"><?php _e('1 day','rmcommon'); ?></option>
                <option value="259200"><?php _e('3 days','rmcommon'); ?></option>
                <option value="604800"><?php _e('1 week','rmcommon'); ?></option>
                <option value="2592000"><?php _e('1 month','rmcommon'); ?></option>
            </select>
        </div>
        <label for="bk-canvas"><?php _e('Visible in','rmcommon'); ?></label>
        <?php echo $canvas->render(); ?>
    </fieldset>
    <fieldset class="right">
        <legend><?php _e('Block Custom Options','rmcommon'); ?></legend>
    </fieldset>
</div>