<h1 class="rmc_titles"><span style="background-position: 0px -64px;">&nbsp;</span><?php echo sprintf(__('%s Custom Fields','admin_works'), $work->title()); ?></h1>

<div class="descriptions">
    <?php _e('Custom fields allows you to store additional data with your work. This data can be used later in your themes, plugins or another xoops component.','admin_works'); ?>
</div>
<br />
<div id="pw-right-table">
    
    <form method="post" action="works.php" id="frm-metas" name="frmmetas">
    <div class="pw_options">
        <select name="op" id="bulk-top">
            <option value=""><?php _e('Bulk actions...','admin_works'); ?></option>
            <option value="delmeta"><?php _e('Delete','admin_works'); ?></option>
        </select>
        <input type="button" value="<?php _e('Apply','admin_works'); ?>" onclick="before_submit('frm-metas');" />
    </div>
    <table class="outer" cellspacing="0">
        <thead>
        <tr>
            <th width="20"><input type="checkbox" id="checkall" onclick="$('#frm-metas').toggleCheckboxes(':not(#checkall)');" /></th>
            <th align="left"><?php _e('Name','admin_works'); ?></th>
            <th align="left"><?php _e('Value','admin_works'); ?></th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th width="20"><input type="checkbox" id="checkall2" onclick="$('#frm-metas').toggleCheckboxes(':not(#checkall2)');" /></th>
            <th align="left"><?php _e('Name','admin_works'); ?></th>
            <th align="left"><?php _e('Value','admin_works'); ?></th>
        </tr>
        </tfoot>
        <tbody>
        <?php if(empty($metas)): ?>
        <tr class="even">
            <td align="center" colspan="3"><?php _e('There are not metas for this work','admin_works'); ?></td>
        </tr>
        <?php endif; ?>
        <?php foreach($metas as $meta): ?>
        <tr class="<?php echo tpl_cycle("even,odd"); ?>" valign="top">
            <td align="center"><input type="checkbox" name="ids[]" id="items-<?php echo $meta['id_meta']; ?>" value="<?php echo $meta['id_meta']; ?>" /></td>
            <td><strong><?php echo $meta['name']; ?></strong></td>
            <td><?php echo TextCleaner::getInstance()->specialchars($meta['value']); ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <div class="pw_options">
        <select name="opb" id="bulk-bottom">
            <option value=""><?php _e('Bulk actions...','admin_works'); ?></option>
            <option value="delmeta"><?php _e('Delete','admin_works'); ?></option>
        </select>
        <input type="button" value="<?php _e('Apply','admin_works'); ?>" onclick="before_submit('frm-metas');" />
    </div>
    <?php echo $xoopsSecurity->getTokenHTML(); ?>
    <input type="hidden" name="id" value="<?php echo $work->id(); ?>" />
    </form>
    
</div>
<div id="pw-left-form">
    <h3>Add Custom Field</h3>
    <form name="frmFields" id="frm-fields" method="post" accept="works.php">
    <label for="cf-name"><?php _e('Field name','admin_works'); ?></label>
    <input type="text" name="name" id="cf-name" value="" />
    <label for="cf-value"><?php _e('Field value','admin_works'); ?></label>
    <textarea name="value" id="cf-value"></textarea>
    <input type="submit" value="<?php _e('Add Custom Field','admin_works'); ?>" />
    <?php echo $xoopsSecurity->getTokenHTML(); ?>
    <input type="hidden" name="op" value="savemeta" />
    <input type="hidden" name="id" value="<?php echo $work->id(); ?>" />
    </form>
    <div class="descriptions">
        <?php _e('To edit some custom field, please input an existing name and the new value. Custom field will be updated automatically.','admin_works'); ?>
    </div>
</div>