<?php RMTemplate::get()->add_script(RMCURL.'/include/js/jquery.validate.min.js'); ?>
<h1 class="rmc_titles mw_titles"><span style="background-position: left 0;">&nbsp;</span><?php _e('Categories','admin_mywords'); ?></h1>
<div class="mw_all_container">
    <div class="form_options">
        <h3 class="form_titles"><?php _e('Add Category','admin_mywords'); ?></h3>
        <form name="addcat" id="addcat" method="post" action="categories.php" class="validate form">
        <label>
            <span><?php _e('Category Name','admin_mywords'); ?></span>
            <input type="text" name="name" id="name" class="required formInput title" value="<?php echo $name; ?>" />
        </label>
        <label>
            <span><?php _e('Category Slug','admin_mywords'); ?></span>
            <input type="text" name="shortcut" id="shortcut" class="formInput" value="<?php echo $shortcut; ?>" />
            <em><?php _e('The “slug” is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.','admin_mywords'); ?></em>
        </label>
        <label>
            <span><?php _e('Category Parent','admin_mywords'); ?></span>
            <select name="parent" id="parent" class="formInput">
                <option value=""<?php if($parent==''): ?> selected="selected"<?php endif; ?>><?php _e('None','admin_mywords'); ?></option>
                <?php foreach($categories as $cat): ?>
                    <option value="<?php echo $cat['id_cat']; ?>"<?php echo $parent==$cat['id_cat'] ? 'selected="selected"' : ''; ?>><?php echo preg_replace('!^!m',str_repeat("&#8212;",$cat['indent']),' '.$cat['name']); ?></option>
                <?php endforeach; ?>
            </select>
            <em><?php _e('Categories, unlike tags, can have a hierarchy. You might have a Jazz category, and under that have children categories for Bebop and Big Band. Totally optional.','admin_mywords'); ?></em>
        </label>
        <label>
            <span><?php _e('Category description','admin_mywords'); ?></span>
            <textarea name="desc" id="desc" class="mw_desc_category"><?php echo $desc ?></textarea>
        </label>
        
        <?php
        	// Event to allow plugins to add new options
        	RMEventsApi::get()->run_event('mw_newcategory_form', null);
        ?>
        
            <p class="submit"><input type="submit" class="button default" value="<?php _e('Add Category','admin_mywords'); ?>" /></p>
            <input type="hidden" name="op" id="op" value="save" />
            <?php echo $xoopsSecurity->getTokenHTML(); ?>
        </form>
    </div>
    
    <div id="tableItems">
    <form name="tblCats" id="tblCats" method="post" action="categories.php">
        <?php echo isset($nav) ? $nav->render() : ''; ?>
        <select name="op">
            <option value="" selected="selected"><?php _e('Bulk actions','admin_mywords'); ?></option>
            <option value="delete"><?php _e('Delete','admin_mywords'); ?></option>
        </select>
        <input type="submit" class="button" value="<?php _e('Apply','admin_mywords'); ?>" />
        <table class="outer" cellspacing="0" style="margin: 5px 0;">
            <thead>
            <tr align="center">
                <th scope="col" width="20"><input type="checkbox" name="checkall" id="checkall" onclick='$("#tblCats").toggleCheckboxes(":not(#checkall)");' /></th>
                <th><?php _e('Name','admin_mywords'); ?></th>
                <th><?php _e('Description','admin_mywords'); ?></th>
                <th><?php _e('Slug','admin_mywords'); ?></th>
                <th><?php _e('Posts','admin_mywords'); ?></th>
            </tr>
            </thead>
            <tfoot>
            <tr align="center">
                <th scope="col"><input type="checkbox" name="checkall2" id="checkall2" onclick='$("#tblCats").toggleCheckboxes(":not(#checkall2)");' /></th>
                <th><?php _e('Name','admin_mywords'); ?></th>
                <th><?php _e('Description','admin_mywords'); ?></th>
                <th><?php _e('Slug','admin_mywords'); ?></th>
                <th><?php _e('Posts','admin_mywords'); ?></th>
            </tr>
            </tfoot>
            <?php if(empty($categories)): ?>
            <tr class="even">
                <td colspan="5"><?php _e('There are not categories registered yet!','admin_mywords'); ?></td>
            </tr>
            <?php endif; ?>
            <?php foreach($categories as $cat): ?>
                <tr id="item-<?php echo $cat['id_cat']; ?>" class="<?php echo tpl_cycle("even,odd"); ?> iedit"<?php if($new==$cat['id_cat']): ?> id="thenew"<?php endif; ?>>
                    <td valign="top"><?php if($cat['id_cat']): ?><input type="checkbox" name="cats[]" id="cats[]" value="<?php echo $cat['id_cat'] ?>" /><?php endif; ?></td>
                    <td nowrap="nowrap">
                        <strong><a href="categories.php?op=edit&amp;id=<?php echo $cat['id_cat']?>"><?php echo str_repeat("&#8212;",$cat['indent']).' '.$cat['name']; ?></a></strong>
                        <span class="mw_options">
                        <a href="categories.php?op=edit&amp;id=<?php echo $cat['id_cat']?>"><?php _e('Edit','admin_mywords'); ?></a>
                        <?php if($cat['id_cat']!=1): ?> |
                        <a href="categories.php?op=delete&amp;id=<?php echo $cat['id_cat']?>" onclick="return msg('<?php echo $cat['name']?>');"><?php _e('Delete','admin_mywords'); ?></a><?php endif; ?>
                        </span>
                        <div class="mw_hidden" id="inline_<?php echo $cat['id_cat']; ?>">
                            <div class="name"><?php echo $cat['name']?></div>
                            <div class="shortcut"><?php echo $cat['shortname']?></div>
                            <div class="idcat"><?php echo $cat['id_cat']?></div>
                        </div>
                    </td>
                    <td valign="top" class="mw_cat_description"><?if ($cat['description']!=''): ?><?php echo $cat['description']?><?php else: ?>&nbsp;<?php endif; ?></td>
                    <td align="center" valign="top"><?php echo $cat['shortname']?></td>
                    <td align="center" valign="top"><?php echo $cat['posts']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php if($pages): ?>
            <span class="tplPages">
            <{foreach item=pag from=$pages}>
                <{if $pag.current}>
                    <span class="current"><{$pag.cap}></span>
                <{else}>
                    <a href="categories.php?page=<{$pag.id}>"><{$pag.cap}></a>
                <{/if}>
            <{/foreach}>
            </span>
        <?php endif; ?>
         <select name="op2">
            <option value="" selected="selected"><?php _e('Bulk actions','admin_mywords'); ?></option>
            <option value="delete"><?php _e('Delete','admin_mywords'); ?></option>
        </select>
        <input type="submit" class="button" value="<?php _e('Apply','admin_mywords'); ?>" />
    </form><br />
    </div>
    
</div>
<table style="display: none"><tbody id="inlineedit">

    <tr id="quick-edit" class="inline-edit-row" style="display: none"><td colspan="5">
        <form method="post" action="categories.php" id="formQEdit" name="formQEdit">
        <fieldset><div class="inline-edit-col">
            <h4><{$lang_qedit}></h4>

            <label>
                <span class="title"><{$lang_name}></span>
                <span class="input-text-wrap"><input type="text" name="name" class="formInput" value="" /></span>
            </label>

            <label>
                <span class="title"><{$lang_shortcut}></span>
                <span class="input-text-wrap"><input type="text" name="shortcut" class="formInput" value="" /></span>
            </label>
            
            <label>
                <span class="title"><{$lang_mtype}></span>
                <span class="input-text-wrap">
                <select name="type" class="formInput">
                <option value=""<{if $type==''}> selected="selected"<{/if}>><{$lang_default}></option>
                <option value="gal"<{if $type=='gal'}> selected="selected"<{/if}>><{$lang_galleries}></option>
                <option value="vid"<{if $type=='vid'}> selected="selected"<{/if}>><{$lang_videos}></option>
                <option value="mus"<{if $type=='mus'}> selected="selected"<{/if}>><{$lang_music}></option>
                </select>
                </span>
            </label>

            <input type="hidden" name="op" id="op" value="qsaveedit" />
        <input type="hidden" name="id" id="id" value="" />
        </div></fieldset></form>

    <p class="buttons-inline submit">

                <a accesskey="c" href="javascript:;" title="Cancel" class="cancel buttonleft"><{$lang_cancel}></a>
                <a accesskey="s" href="javascript:;" title="Update Category" class="save buttonright"><{$lang_update}></a>
    </p>
    </td></tr>

    </tbody></table>