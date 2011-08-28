<h1 class="rmc_titles"><?php _e('Categories Management','bxpress'); ?></h1>

<div class="bxpress_2cols">
<div class="bxpress_colleft">
    <h3><?php _e('Add Category','bxpress'); ?></h3>
    <form name="frmNewCat" id="frm-new-categos" method="post" action="categos.php">
    <label for="cat-title"><?php _e('Name','bxpress'); ?></label>
    <input type="text" name="title" id="cat-title" value="" />
    <label for="cat-desc"><?php _e('Description','bxpress'); ?></label>
    <textarea name="desc" id="cat-desc" cols="30" rows="5"></textarea>
    <label><?php _e('Groups with access','bxpress'); ?></label>
    <div class="groups">
    <?php echo $groups->render(); ?>
    </div>
    <label>
        <input type="checkbox" name="showdesc" value="1" checked="checked" />
        <?php _e('Show description','bxpress'); ?>
    </label>
    <label>
        <input type="checkbox" name="status" value="1" checked="checked" />
        <?php _e('Activate category','bxpress'); ?>
    </label>
    <input type="hidden" name="action" value="save" />
    <?php echo $xoopsSecurity->getTokenHTML(); ?>
    <input type="submit" value="<?php _e('Create Category','bxpress'); ?>" />
    </form>
</div>
<div class="bxpress_colright">
    <form name="frmCats" id="frm-categories" action="categos.php" method="post">
    <div class="bxpress_options">
        <select name="action" id="bulk-top">
            <option value=""><?php _e('Bulk actions...','bxpress'); ?></option>
            <option value="enable"><?php _e('Activate','bxpress'); ?></option>
            <option value="disable"><?php _e('Disable','bxpress'); ?></option>
            <option value="delete"><?php _e('Delete','bxpress'); ?></option>
        </select>
        <input type="button" id="the-op-top" value="<?php _e('Apply','bxpress'); ?>" onclick="before_submit('frm-categories');" />
        &nbsp; &nbsp;
        <a href="resources.php"><?php _e('Show All','bxpress'); ?></a>
    </div>
            
    <table class="outer" cellspacing="1" cellpadding="0" width="100%">
        <thead>
            <tr class="head" align="center">
                <th width="20">
                        <input type="checkbox" id="checkall" onchange="$('#frm-categories').toggleCheckboxes(':not(#checkall)');" />
                </th>
                <th width="20"><?php _e('ID','bxpress'); ?></th>
                <th align="left"><?php _e('Name','bxpress'); ?></th>
                <th><?php _e('Active','bxpress'); ?></th>
                <th align="left"><?php _e('Descripcion','bxpress'); ?></th>
            </tr>
        </thead>
        <tfoot>
            <tr class="head" align="center">
                <th width="20">
                        <input type="checkbox" id="checkall2" onchange="$('#frm-categories').toggleCheckboxes(':not(#checkall2)');" />
                </th>
                <th width="20"><?php _e('ID','bxpress'); ?></th>
                <th align="left"><?php _e('Name','bxpress'); ?></th>
                <th><?php _e('Active','bxpress'); ?></th>
                <th align="left"><?php _e('Descripcion','bxpress'); ?></th>
            </tr>
        </tfoot>
        
        <tbody>
            <?php if(empty($categos)): ?>
            <tr class="even">
                <td colspan="5" align="center"><?php _e('There are not categories created yet!','bxpress'); ?></td>
            </tr>
            <?php endif; ?>
            
            <?php foreach($categos as $cat): ?>
            <tr class="<?php echo tpl_cycle("even,odd"); ?>" align="left" valign="top">
                <td align="center"><input type="checkbox" name="ids[]" id="item-<?php echo $cat['id']; ?>" value="<?php echo $cat['id']; ?>" /></td>
                <td align="center"><?php echo $cat['id']; ?></td>
                <td align="left">
                <strong><a href="forums.php?catid=<?php echo $cat['id']; ?>"><?php echo $cat['title']; ?></a></strong>
                <span class="rmc_options">
                    <a href="categos.php?action=edit&amp;id=<?php echo $cat['id']; ?>"><?php _e('Edit','bxpress'); ?></a> |
                    <a href="#" class="delete_cat" id="cat-<?php echo $cat['id']; ?>" onclick="select_option(<?php echo $cat['id']; ?>,'delete','frm-categories');"><?php _e('Delete','bxpress'); ?></a>
                </span>
                </td>
                <td align="center"><img src="../images/<?php if($cat['status']): ?>ok.png<?php else: ?>no.png<?php endif; ?>" border="0" alt="" /></td>
                <td><?php echo $cat['desc']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="bxpress_options">
        <select name="actionb" id="bulk-bottom">
            <option value=""><?php _e('Bulk actions...','bxpress'); ?></option>
            <option value="enable"><?php _e('Activate','bxpress'); ?></option>
            <option value="disable"><?php _e('Disable','bxpress'); ?></option>
            <option value="delete"><?php _e('Delete','bxpress'); ?></option>
        </select>
        <input type="button" id="the-op-bottom" value="<?php _e('Apply','bxpress'); ?>" onclick="before_submit('frm-categories');" />
        &nbsp; &nbsp;
        <a href="resources.php"><?php _e('Show All','bxpress'); ?></a>
    </div>
    <?php echo $xoopsSecurity->getTokenHTML(); ?>
    </form>
</div>
</div>
        
