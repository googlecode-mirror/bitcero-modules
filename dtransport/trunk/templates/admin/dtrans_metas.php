<div id="dt-metas-container" class="dt_table outer">
    <div class="dt_row">
        <div class="dt_cell th"><?php _e('Field name','dtransport'); ?></div>
        <div class="dt_cell th"><?php _e('Field value','dtransport'); ?></div>
    </div>
    <div class="dt_row">
        <div class="dt_cell">
            <input type="text" name="meta_name" id="meta-name" value="" style="display: <?php echo empty($metaNames) ? 'block' : 'none'; ?>" />
            <a href="#" id="cancel-name"<?php echo !empty($metaNames) ? ' style="display: none"' : ''; ?>><?php _e('Cancel','dtransport'); ?></a>
            <?php if(!empty($metaNames)): ?>
            <select name="selname" id="meta-sel-name">
                <option value=""><?php _e('Select field...','dtransport'); ?></option>
                <?php foreach($metaNames as $meta): ?>
                <option value="<?php echo $meta['name']; ?>"><?php echo $meta['name']; ?></option>
                <?php endforeach; ?>
            </select>
            <a href="#" id="new-meta-name" style="display: block;"><?php _e('New Field','dtransport'); ?></a>
            <?php endif; ?>
            <label class="error forname"></label>
        </div>
        <div class="dt_cell">
            <textarea rows="5" cols="40" name="meta_value" id="meta-value"></textarea>
            <label class="error forvalue"><?php _e('You must specify a value for this custom field!','dtransport'); ?></label>
        </div>
    </div>
    <div class="dt_row">
        <div class="dt_cell">&nbsp;</div>
        <div class="dt_cell"><input type="button" class="buttonOrange" id="add-meta" value="<?php _e('Add Meta','dtransport'); ?>" /></div>
    </div>
</div>
    <br />
<div id="the-fields" class="dt_table outer">
    <div class="dt_row">
        <div class="dt_cell head"><?php _e('Field name','dtransport'); ?></div>
        <div class="dt_cell head"><?php _e('Field value','dtransport'); ?></div>
    </div>
    <?php foreach($metas as $meta): ?>
    <div class="dt_row" id="field-<?php echo $meta['name']; ?>">
        <div class="dt_cell">
            <input type="text" name="dtMetas[<?php echo $meta['name']; ?>][name]" value="<?php echo $meta['name']; ?>" size="20" /><br />
            <a href="#" class="del-field" onclick="delete_field($(this)); return false;"><?php _e('Delete Field','dtransport'); ?></a>
        </div>
        <div class="dt_cell">
            <textarea name="dtMetas[<?php echo $meta['name']; ?>][value]" rows="3" cols="45"><?php echo $meta['value']; ?></textarea>
        </div>
    </div>
    <?php endforeach; ?>
</div>