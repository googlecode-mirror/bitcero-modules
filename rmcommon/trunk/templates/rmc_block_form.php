<div id="block-config-form">
<form name="frmBkConfig" id="frm-block-config" method="post" action="blocks.php">
    <div class="th title"><?php echo sprintf(__('%s settings','rmcommon'), $block->getVar('name')); ?><span class="close" onclick="blocksAjax.close();"></span></div>
    <div class="content">
        <div class="bk_tab_titles">
            <span id="tab-general" class="selected"><?php _e('General Settings','rmcommon'); ?></span>
            <?php if($block_options || $block->getVar('type')=='custom'): ?><span id="tab-custom"><?php _e('Custom Options','rmcommon'); ?></span><?php endif; ?>
            <span id="tab-permissions" class=""><?php _e('Permissions','rmcommon'); ?></span>
        </div>
        <div class="bk_tab" id="general-content">
            <label for="bk-name" class="options"><?php _e('Block Title','rmcommon'); ?></label>
            <input type="text" name="bk_name" size="50" class="big" value="<?php echo $block->getVar('name'); ?>" />
            <div class="left">
            <label for="bk-pos" class="options"><?php _e('Block position','rmcommon'); ?></label>
            <select name="bk_pos" id="bk-pos">
                <?php foreach($positions as $pos): ?>
                <option value="<? echo $pos['id_position']; ?>"<?php echo $block->getVar('canvas')==$pos['id_position']?' selected="selected"' : ''; ?>><?php echo $pos['name']; ?></option>
                <?php endforeach; ?>
            </select>
            </div>
            <div class="right">
                <label for="bk-weight" class="options"><?php _e('Block weight','rmcommon'); ?></label>
                <input type="text" name="bk_weight" id="bk-weight" value="<?php echo $block->getVar('weight'); ?>" />
            </div>
            <div class="clearer"></div>
            <div class="left">
                <label for="bk-visible" class="options"><?php _e('Visible','rmcommon'); ?></label>
                <label style="display: inline;"><input type="radio" value="1" name="bk_visible" id="bk-visible" <?php echo $block->getVar('visible')==1?'checked="checked"':''; ?>/> <?php _e('Yes','rmcommon'); ?></label>
                <label style="display: inline;"><input type="radio" value="0" name="bk_visible" <?php echo $block->getVar('visible')==0?'checked="checked"':''; ?>/> <?php _e('No','rmcommon'); ?></label>
            </div>
            <div class="right">
                <label for="bk-cache" class="options"><?php _e('Cache lifetime','rmcommon'); ?></label>
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
            <div class="clearer"></div>
            <label for="bk-canvas" class="options"><?php _e('Visible in','rmcommon'); ?></label>
            <?php echo $canvas->render(); ?>
            <div class="clear">&nbsp;</div>
        </div>
        <?php if($block_options || $block->getVar('type')=='custom'): ?>
        <div class="bk_tab" id="custom-content">
            <?php echo $block_options; ?> 
            <?php if($block->getVar('type')=='custom'): ?>
            <textarea cols="45" rows="10" name="bk_content" id="bk-content" style="width: 97%; height: 300px;"><?php echo htmlspecialchars($block->getVar('content')); ?></textarea>
            <label for="c-type"><?php _e('Content type:','rmcommon'); ?></label>
            <select name="bk_ctype" id="c-type">
                <option value="TEXT"<?php echo $block->getVar('content_type')=='TEXT' ? ' selected="selected"' : ''; ?>><?php _e('Formatted text','rmcommon'); ?></option>
                <option value="HTML"<?php echo $block->getVar('content_type')=='HTML' ? ' selected="selected"' : ''; ?>><?php _e('HTML block','rmcommon'); ?></option>
                <option value="PHP"<?php echo $block->getVar('content_type')=='PHP' ? ' selected="selected"' : ''; ?>><?php _e('PHP block','rmcommon'); ?></option>
                <option value="XOOPS"<?php echo $block->getVar('content_type')=='XOOPS' ? ' selected="selected"' : ''; ?>><?php _e('XOOPS code','rmcommon'); ?></option>
            </select>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <div class="bk_tab" id="block-permissions">
            <label class="options"><?php _e('Read Permissions','rmcommon'); ?></label>
            <?php echo $groups->render(); ?>
        </div>
        
        <div class="bk_buttons">
            <input type="button" value="<?php _e('Cancel','rmcommon'); ?>" onclick="blocksAjax.close();" />
            <input type="submit" value="<?php _e('Save','rmcommon'); ?>" onclick="blocksAjax.sendConfig(); return false;" />
        </div>
        <?php echo $xoopsSecurity->getTokenHTML(); ?>
    </div>
    <input type="hidden" name="action" value="saveconfig" />
    <input type="hidden" name="bid" value="<?php echo $id; ?>" />
</form>
</div>