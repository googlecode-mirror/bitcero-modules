<div class="shop_metas">
        <table class="outer" cellspacing="0" cellpadding="4" width="100%" id="existing_meta">
            <tr class="head"><td colspan="2"><?php _e('Custom Fields','shop'); ?></td></tr>
        <?php foreach($metas as $name => $value): ?>
        <tr valign="top" class="even">
            <td width="100">
                <input type="text" name="meta_name[]" value="<?php echo $name; ?>" />
            </td>
            <td>
                <textarea name="meta_value[]" style="width: 99%; height: 70px;"><?php echo $value; ?></textarea>
            </td>
        </tr>
        <?php endforeach; ?>
        </table>
        <br />
        <table cellspacing="0" cellpadding="4" width="100%" class="outer">
        <tr><th colspan="2"><?php _e('Add Custom Field','shop'); ?></th></tr>
        <tr valign="top" class="even">
            <td width="100">
                <label class="block"><?php _e('Name:','shop'); ?></label>
                <?php if(count($metas)>0): ?>
                <select name="dmeta_name" id="dmeta_sel">
                    <?php foreach($metas as $meta): ?>
                    <option value="<?php echo $meta; ?>"><?php echo $meta; ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="text" name="dmeta_name" id="dmeta" value="" size="30" style="display: none;" />
                <br />        
                <a href="javascript:;" id="add_field_name"><?php _e('Add New','shop'); ?></a>
                <?php else: ?>
                <input type="text" name="dmeta_name" id="dmeta" value="" size="30" />
                <?php endif; ?>
            </td>
            <td>
                <label class="block"><?php _e('Value:','shop'); ?></label>
                <textarea name="dmeta_value" id="dvalue" style="width: 95%; height: 60px;"></textarea>
            </td>
        </tr>
        <tr class="foot">
            <td>&nbsp;</td>
            <td><input type="button" id="add_field" value="Add Field" />
        </tr>
        </table>
</div>
