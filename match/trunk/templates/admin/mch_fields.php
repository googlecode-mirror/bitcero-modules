<h1 class="rmc_titles"><span style="background-position: -32px 0;">&nbsp;</span><?php _e('Fields','match'); ?></h1>

<div id="mch-champform">
<form action="fields.php" method="post" name="frmNField">
    <h3><?php _e('Add Field','match'); ?></h3>
    <label for="name"><?php _e('Field Name:','match'); ?></label>
    <input type="text" name="name" id="name" value="" size="30" />
    <?php echo $editor->render(); ?>
    <input type="submit" value="<?php _e('Add Field','match'); ?>" />
    <input type="hidden" name="action" value="save" />
    <?php echo $xoopsSecurity->getTokenHTML(); ?>
</form>
</div>

<div id="mch-champtable">
    <form name="frmFields" id="frm-fields" method="POST" action="fields.php">
    <div class="mch_options">
        <?php $nav->display(false); ?>
        <select name="action" id="bulk-top">
            <option value=""><?php _e('Bulk actions...','match'); ?></option>
            <option value="delete"><?php _e('Delete','match'); ?></option>
        </select>
        <input type="button" id="the-op-top" value="<?php _e('Apply','match'); ?>" onclick="before_submit('frm-fields');" />
    </div>
    <table class="outer" cellspacing="1">
        <thead>
        <tr class="head" align="center">
            <th width="20"><input type="checkbox" id="checkall" onclick='$("#frm-fields").toggleCheckboxes(":not(#checkall)");' /></th>
            <th width="30"><?php _e('ID','match'); ?></th>
            <th align="left"><?php _e('Name','match'); ?></th>
            <th><?php _e('Short name','match'); ?></th>
            <th><?php _e('Description','match'); ?></th>
        </tr>
        </thead>
        
        <tfoot>
        <tr class="head" align="center">
            <th width="20"><input type="checkbox" id="checkall2" onclick='$("#frm-fields").toggleCheckboxes(":not(#checkall2)");' /></th>
            <th width="30"><?php _e('ID','match'); ?></th>
            <th align="left"><?php _e('Name','match'); ?></th>
            <th><?php _e('Short name','match'); ?></th>
            <th><?php _e('Description','match'); ?></th>
        </tr>
        </tfoot>
        
        <tbody>
        <?php if(empty($fields)): ?>
        <tr align="center" class="even">
            <td colspan="6"><?php _e('There are not fields created yet!','match'); ?></td>
        </tr>
        <?php endif; ?>
        <?php foreach($fields as $field): ?>
        <tr align="center" class="<?php echo tpl_cycle('even,odd'); ?>" valign="top">
            <td><input type="checkbox" name="ids[]" value="<?php echo $field['id']; ?>" id="item-<?php echo $field['id']; ?>" /></td>
            <td><strong><?php echo $field['id']; ?></strong></td>
            <td align="left"><a href="<?php echo $field['link']; ?>"><?php echo $field['name']; ?></a>
            <span class="rmc_options">
                <a href="./fields.php?action=edit&amp;id=<?php echo $field['id']; ?>"><?php _e('Edit','match'); ?></a> | 
                <a href="javascript:;" onclick="select_option(<?php echo $field['id']; ?>,'delete','frm-fields');"><?php _e('Delete','match'); ?></a>
            </span>
            </td>
            <td align="center"><?php echo $field['nameid']; ?></td>
            <td align="center"><?php echo $field['description']; ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <div class="mch_options">
        <?php $nav->display(false); ?>
        <select name="actionb" id="bulk-bottom">
            <option value=""><?php _e('Bulk actions...','match'); ?></option>
            <option value="delete"><?php _e('Delete','match'); ?></option>
        </select>
        <input type="button" id="the-op-bottom" value="<?php _e('Apply','match'); ?>" onclick="before_submit('frm-fields');" />
    </div>
    <?php echo $xoopsSecurity->getTokenHTML(); ?>
    </form>
</div>