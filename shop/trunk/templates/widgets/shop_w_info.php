<div class="w_info">
    <label class="block"><?php _e('Price:','shop'); ?></label>
    <input type="text" name="price" id="p-price" size="30" />
    <label class="block"><?php _e('Type:','shop'); ?></label>
    <label><input type="radio" value="0" name="type" id="p-type"<?php echo $edit ? ($product->getVar('type')==0?' checked="checked"': '') : ' checked="checked"'; ?> /> <?php _e('Normal','shop'); ?></label>
    <label><input type="radio" value="1" name="type" id="p-type"<?php echo $edit ? ($product->getVar('type')==1?' checked="checked"': '') : ''; ?> /> <?php _e('Digital','shop'); ?></label>
    <label class="block"><?php _e('Available:','shop'); ?></label>
    <label><input type="radio" value="1" name="available" id="p-available"<?php echo $edit ? ($product->getVar('available')==1?' checked="checked"': '') : ' checked="checked"'; ?> /> <?php _e('Yes','shop'); ?></label>
    <label><input type="radio" value="0" name="available" id="p-type"<?php echo $edit ? ($product->getVar('available')==0?' checked="checked"': '') : ''; ?> /> <?php _e('No','shop'); ?></label>
</div>