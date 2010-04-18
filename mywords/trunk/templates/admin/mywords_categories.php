<?php RMTemplate::get()->add_script(RMCURL.'/include/js/jquery.validate.min.js'); ?>
<h1 class="rmc_titles mw_titles"><span style="background-position: left 0;">&nbsp;</span><?php _e('Categories','mywords'); ?></h1>
<div class="mw_all_container">
    <div class="form_options">
        <h3 class="form_titles"><?php _e('Add Category','mywords'); ?></h3>
        <form name="addcat" id="addcat" method="post" action="categories.php" class="validate form">
        <label>
            <span><?php _e('Category Name','mywords'); ?></span>
            <input type="text" name="name" id="name" class="required formInput title" value="<?php echo $name; ?>" />
        </label>
        <label>
            <span><?php _e('Category Slug','mywords'); ?></span>
            <input type="text" name="shortcut" id="shortcut" class="formInput" value="<?php echo $shortcut; ?>" />
            <em><?php _e('The “slug” is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.','mywords'); ?></em>
        </label>
        <label>
            <span><?php _e('Category Parent','mywords'); ?></span>
            <select name="parent" id="parent" class="formInput">
                <option value=""<?php if($parent==''): ?> selected="selected"<?php endif; ?>><?php _e('None','mywords'); ?></option>
                <?php foreach($categories as $cat): ?>
                    <option value="<?php echo $cat['id_cat']; ?>"<?php echo $parent==$cat['id_cat'] ? 'selected="selected"' : ''; ?>><?php echo preg_replace('!^!m',str_repeat("&#8212;",$cat['indent']),' '.$cat['name']); ?></option>
                <?php endforeach; ?>
            </select>
            <em><?php _e('Categories, unlike tags, can have a hierarchy. You might have a Jazz category, and under that have children categories for Bebop and Big Band. Totally optional.','mywords'); ?></em>
        </label>
        <label>
            <span><?php _e('Category description','mywords'); ?></span>
            <textarea name="desc" id="desc" class="mw_desc_category"><?php echo $desc ?></textarea>
        </label>
        
        <?php
        	// Event to allow plugins to add new options
        	RMEvents::get()->run_event('mywords.newcategory_form', null);
        ?>
        
            <p class="submit"><input type="submit" class="button default" value="<?php _e('Add Category','mywords'); ?>" /></p>
            <input type="hidden" name="op" id="op" value="save" />
            <?php echo $xoopsSecurity->getTokenHTML(); ?>
        </form>
    </div>
    
    <div id="tableItems">
    <form name="tblCats" id="tblCats" method="post" action="categories.php">
        <?php echo isset($nav) ? $nav->render(false) : ''; ?>
        <select name="op" id="cat-op">
            <option value="" selected="selected"><?php _e('Bulk actions','mywords'); ?></option>
            <option value="delete"><?php _e('Delete','mywords'); ?></option>
        </select>
        <input type="submit" class="button" value="<?php _e('Apply','mywords'); ?>" />
        <table class="outer" cellspacing="0" style="margin: 5px 0;">
            <thead>
            <tr align="center">
                <th scope="col" width="20"><input type="checkbox" name="checkall" id="checkall" onclick='$("#tblCats").toggleCheckboxes(":not(#checkall)");' /></th>
                <th><?php _e('Name','mywords'); ?></th>
                <th><?php _e('Description','mywords'); ?></th>
                <th><?php _e('Slug','mywords'); ?></th>
                <th><?php _e('Posts','mywords'); ?></th>
            </tr>
            </thead>
            <tfoot>
            <tr align="center">
                <th scope="col"><input type="checkbox" name="checkall2" id="checkall2" onclick='$("#tblCats").toggleCheckboxes(":not(#checkall2)");' /></th>
                <th><?php _e('Name','mywords'); ?></th>
                <th><?php _e('Description','mywords'); ?></th>
                <th><?php _e('Slug','mywords'); ?></th>
                <th><?php _e('Posts','mywords'); ?></th>
            </tr>
            </tfoot>
            <?php if(empty($categories)): ?>
            <tr class="even">
                <td colspan="5"><?php _e('There are not categories registered yet!','mywords'); ?></td>
            </tr>
            <?php endif; ?>
            <?php foreach($categories as $cat): ?>
                <tr id="item-<?php echo $cat['id_cat']; ?>" class="<?php echo tpl_cycle("even,odd"); ?> iedit"<?php if($new==$cat['id_cat']): ?> id="thenew"<?php endif; ?>>
                    <td valign="top"><?php if($cat['id_cat']): ?><input type="checkbox" name="cats[]" id="cat-<?php echo $cat['id_cat']; ?>" value="<?php echo $cat['id_cat'] ?>" /><?php endif; ?></td>
                    <td nowrap="nowrap">
                        <strong><a href="categories.php?op=edit&amp;id=<?php echo $cat['id_cat']?>"><?php echo str_repeat("&#8212;",$cat['indent']).' '.$cat['name']; ?></a></strong>
                        <span class="mw_options">
                        <a href="categories.php?op=edit&amp;id=<?php echo $cat['id_cat']?>"><?php _e('Edit','mywords'); ?></a>
                        <?php if($cat['id_cat']!=1): ?> |
                        <a href="javascript:;" onclick="return cat_del_confirm('<?php echo $cat['name']; ?>',<?php echo $cat['id_cat']; ?>);"><?php _e('Delete','mywords'); ?></a><?php endif; ?>
                        </span>
                    </td>
                    <td valign="top" class="mw_cat_description"><?if ($cat['description']!=''): ?><?php echo $cat['description']; ?><?php else: ?>&nbsp;<?php endif; ?></td>
                    <td align="center" valign="top"><?php echo $cat['shortname']?></td>
                    <td align="center" valign="top"><?php echo $cat['posts']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php echo isset($nav) ? $nav->render(false) : ''; ?>
         <select name="op2">
            <option value="" selected="selected"><?php _e('Bulk actions','mywords'); ?></option>
            <option value="delete"><?php _e('Delete','mywords'); ?></option>
        </select>
        <input type="submit" class="button" value="<?php _e('Apply','mywords'); ?>" />
        <?php echo $xoopsSecurity->getTokenHTML(); ?>
    </form><br />
    </div>
    
</div>
