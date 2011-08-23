<h1 class="rmc_titles"><?php _e('Categories Management','exmbb'); ?></h1>

<div class="exmbb_2cols">
<div class="exmbb_colleft">
    <h3><?php _e('Add Category','exmbb'); ?></h3>
    <form name="frmNewCat" id="frm-new-categos" method="post" action="categos.php">
    <label for="cat-title"><?php _e('Name','exmbb'); ?></label>
    <input type="text" name="title" id="cat-title" value="" />
    <label for="cat-desc"><?php _e('Description','exmbb'); ?></label>
    <textarea name="desc" id="cat-desc" cols="30" rows="5"></textarea>
    <label><?php _e('Groups with access','exmbb'); ?></label>
    <div class="groups">
    <?php echo $groups->render(); ?>
    </div>
    <label>
        <input type="checkbox" name="showdesc" value="1" checked="checked" />
        <?php _e('Show description','exmbb'); ?>
    </label>
    <label>
        <input type="checkbox" name="status" value="1" checked="checked" />
        <?php _e('Activate category','exmbb'); ?>
    </label>
    <input type="hidden" name="action" value="save" />
    <?php echo $xoopsSecurity->getTokenHTML(); ?>
    <input type="submit" value="<?php _e('Create Category','exmbb'); ?>" />
    </form>
</div>
<div class="exmbb_colright">
    <form name="frmCats" id="frm-categories" action="categos.php" method="post">
    <div class="exmbb_options">
        <select name="action" id="bulk-top">
            <option value=""><?php _e('Bulk actions...','exmbb'); ?></option>
            <option value="approve"><?php _e('Activate','exmbb'); ?></option>
            <option value="draft"><?php _e('Disable','exmbb'); ?></option>
            <option value="public"><?php _e('Delete','exmbb'); ?></option>
        </select>
        <input type="button" id="the-op-top" value="<?php _e('Apply','exmbb'); ?>" onclick="before_submit('frm-categories');" />
        &nbsp; &nbsp;
        <a href="resources.php"><?php _e('Show All','docs'); ?></a>
    </div>
            
    <table class="outer" cellspacing="1" cellpadding="0" width="100%">
        <thead>
            <tr class="head" align="center">
                <th width="20">
                        <input type="checkbox" name="checkall" onchange="xoopsCheckAll('frmCats','checkall');" />
                </th>
                <th width="30"><?php _e('ID','exmbb'); ?></th>
                <th align="left"><?php _e('Name','exmbb'); ?></th>
                <th><?php _e('Active','exmbb'); ?></th>
                <th align="left"><?php _e('Descripcion','exmbb'); ?></th>
            </tr>
        </thead>
        <tfoot>
            <tr class="head" align="center">
                <th width="20">
                        <input type="checkbox" name="checkall" onchange="xoopsCheckAll('frmCats','checkall');" />
                </th>
                <th width="50"><?php _e('ID','exmbb'); ?></th>
                <th align="left"><?php _e('Name','exmbb'); ?></th>
                <th><?php _e('Active','exmbb'); ?></th>
                <th align="left"><?php _e('Descripcion','exmbb'); ?></th>
            </tr>
        </tfoot>
        
        <tbody>
            <?php if(empty($categos)): ?>
            <tr class="even">
                <td colspan="4" align="center"><?php _e('There are not categories created yet!','exmbb'); ?></td>
            </tr>
            <?php endif; ?>
            
            <?php foreach($categos as $cat): ?>
            <tr class="<?php echo tpl_cycle("even,odd"); ?>" align="left" valign="top">
                <td align="center"><input type="checkbox" name="ids[]" id="item-<?php echo $cat['id']; ?>" value="<?php echo $cat['id']; ?>" /></td>
                <td align="center"><?php echo $cat['id']; ?></td>
                <td align="left">
                <strong><a href="forums.php?catid=<?php echo $cat['id']; ?>"><?php echo $cat['title']; ?></a></strong>
                <span class="rmc_options">
                    <a href="categos.php?action=edit&amp;id=<?php echo $cat['id']; ?>"><?php _e('Edit','exmbb'); ?></a> |
                    <a href="#" class="delete_cat" id="cat-<?php echo $cat['id']; ?>"><?php _e('Delete','exmbb'); ?></a>
                </span>
                </td>
                <td align="center"><img src="../images/<?php if($cat['status']): ?>ok.png<?php else: ?>no.png<?php endif; ?>" border="0" alt="" /></td>
                <td><?php echo $cat['desc']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </form>
</div>
</div>
        
