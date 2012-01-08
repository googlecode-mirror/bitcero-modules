<h1 class="rmc_titles dt_titles"><span style="background-position: left -32px;">&nbsp;</span><?php echo sprintf(__('Categories Management','dtransport')); ?></h1>

<div class="dt_table">
    <div class="dt_row">
        <div class="dt_cell width-300 form_add_cat">
            <h3><?php _e('Add Category','dtransport'); ?></h3>
            <form name="frmAdd" id="frm-add" method="post" action="categories.php">
            <label for="cat-name"><?php _e('Category name','dtransport'); ?></label>
            <input type="text" name="name" id="cat-name" size="30" value="" class="required" />
            <label for="cat-nameid"><?php _e('Short name','dtransport'); ?></label>
            <input type="text" name="nameid" id="cat-nameid" size="30" value="" />
            <label for="cat-desc"><?php _e('Description','dtransport'); ?></label>
            <textarea cols="30" rows="5" name="desc"></textarea>
            <label for="cat-parent"><?php _e('Root category','dtransport'); ?></label>
            <select name="parent" id="cat-parent">
                <option value="0" selected="selected"><?php _e('Select category...','dtransport'); ?></option>
                <?php foreach($categories as $cat): ?>
                <option value="<?php echo $cat['id']; ?>"><?php echo str_repeat("&#8212;", $cat['indent']); ?><?php echo $cat['name']; ?></option>
                <?php endforeach; ?>
            </select>
            <input type="submit" value="<?php _e('Add Category','dtransport'); ?>" />
            <input type="hidden" name="action" value="save" />
            <input type="hidden" name="active" value="1" />
            <?php echo $xoopsSecurity->getTokenHTML("XT"); ?>
            </form>
        </div>
        <div class="dt_cell">
            <form name="frmcat" id="frm-categories" method="POST" action="categories.php">
            <div class="dt_options">
                <select name="action" id="bulk-top">
                    <option value="" selected="selected"><?php _e('Bulk actions...','dtransport'); ?></option>
                    <option value="active"><?php _e('Activate','dtransport'); ?></option>
                    <option value="desactive"><?php _e('Deactivate','dtransport'); ?></option>
                    <option value="delete"><?php _e('Delete','dtransport'); ?></option>
                </select>
                <input type="button" id="the-op-top" value="<?php _e('Apply','docs'); ?>" onclick="before_submit('frm-categories');" />
            </div>
            <table width="100%" class="outer" cellspacing="1">
                <thead>
                <tr class="head" align="center">
                    <th width="20"><input type="checkbox" id="checkall" onclick='$("#frm-categories").toggleCheckboxes(":not(#checkall)");' /></th>
                    <th><?php _e('ID','dtransport'); ?></th>
                    <th align="left"><?php _e('Category name','dtransport'); ?></th>
                    <th align="left"><?php _e('Description','dtransport'); ?></th>
                    <th><?php _e('Active','dtransport'); ?></th>
                </tr>
                </thead>
                <tfoot>
                <tr class="head" align="center">
                    <th width="20"><input type="checkbox" id="checkall2" onclick='$("#frm-categories").toggleCheckboxes(":not(#checkall2)");' /></th>
                    <th><?php _e('ID','dtransport'); ?></th>
                    <th align="left"><?php _e('Category name','dtransport'); ?></th>
                    <th align="left"><?php _e('Description','dtransport'); ?></th>
                    <th><?php _e('Active','dtransport'); ?></th>
                </tr>
                </tfoot>
                <tbody>
                <?php foreach($categories as $cat): ?>
                <tr class="<?php echo tpl_cycle('even,odd'); ?>" align="center" valign="top">
                    <td><input type="checkbox" name="ids[]" id="item-<?php echo $cat['id']; ?>" value="<?php echo $cat['id']; ?>" /></td>
                    <td width="20"><strong><?php echo $cat['id']; ?></strong></td>
                    <td align="left">
                        <a href="items.php?cat=<?php echo $cat['id']; ?>"><?php echo str_repeat("&#8212;", $cat['indent']); ?> <?php echo $cat['name']; ?></a>
                        <span class="rmc_options">
                        <a href="categories.php?action=edit&amp;id=<?php echo $cat['id']; ?>"><?php _e('Edit','dtransport'); ?></a> |
                        <a href="#" onclick="dt_check_delete(<?php echo $cat['id']; ?>,'frm-categories');"><?php _e('Delete','dtransport'); ?></a>
                        </span>
                    </td>
                    <td align="left"><?php echo $cat['description']; ?></td>
                    <td>
                        <img src="../images/<?php if($cat['active']): ?>ok<?php else: ?>no<?php endif; ?>.png" />
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <div class="dt_options">
                <select name="actionb" id="bulk-bottom">
                    <option value="" selected="selected"><?php _e('Bulk actions...','dtransport'); ?></option>
                    <option value="active"><?php _e('Activate','dtransport'); ?></option>
                    <option value="desactive"><?php _e('Deactivate','dtransport'); ?></option>
                    <option value="delete"><?php _e('Delete','dtransport'); ?></option>
                </select>
                <input type="button" id="the-op-bottom" value="<?php _e('Apply','docs'); ?>" onclick="before_submit('frm-categories');" />
            </div>
            <?php echo $xoopsSecurity->getTokenHTML(); ?>
            </form>
        </div>
    </div>
</div>
