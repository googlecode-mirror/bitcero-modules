<h1 class="rmc_titles dt_titles"><span style="background-position: left -32px;">&nbsp;</span><?php echo $loc; ?></h1>

<form name="frmItems" id="frm-items" method="POST" action="items.php">
<div class="dt_table">
    <div class="dt_row">
        <div class="dt_cell">
            <?php _e('Search download:','dtransport'); ?>
            <input type="text" name="search" value="<?php echo $search; ?>" size="15" />
            <input type="submit" value="<?php _e('Search Now!','dtransport'); ?>" /> 
        </div>
        <div class="dt_cell">
            <?php _e('Category:','dtransport'); ?>
            <select name="cat" onchange="submit()">
                <option value="0"><?php _e('Select...','dtransport'); ?></option>
                <?php foreach($categories as $cat): ?>
                    <option value="<?php echo $cat['id']; ?>"<?php if($cat['id']==$catid): ?> selected="selected"<?php endif; ?>><?php echo $cat['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="dt_cell">
            <?php echo $navpage; ?>
        </div>
    </div>
</div>

<div class="dt_options">

    <select name="action" id="bulk-top">
        <option value="" selected="selected"><?php _e('Bulk actions...','dtransport'); ?></option>
        <option value="bulk_approve"><?php _e('Approved','dtransport'); ?></option>
        <option value="bulk_unapproved"><?php _e('Not approved','dtransport'); ?></option>
        <option value="bulk_featured"><?php _e('Featured','dtransport'); ?></option>
        <option value="bulk_unfeatured"><?php _e('Not featured','dtransport'); ?></option>
        <option value="bulk_daily"><?php _e('Daily','dtransport'); ?></option>
        <option value="bulk_undaily"><?php _e('Not daily','dtransport'); ?></option>
        <option value="bulk_secure"><?php _e('Protected','dtransport'); ?></option>
        <option value="bulk_nosecure"><?php _e('Not protected','dtransport'); ?></option>
        <option value="bulk_delete"><?php _e('Delete','dtransport'); ?></option>
    </select>
    <input type="button" id="the-op-top" value="<?php _e('Apply','dtransport'); ?>" onclick="before_submit('frm-items');" />

</div>

<table width="100%" class="outer items" cellspacing="1">
    <thead>
	<tr class="head" align="center">
		<th width="20"><input type="checkbox" id="checkall" onclick="$('#frm-items').toggleCheckboxes(':not(#checkall,.featured,.approved,.daily)');" /></th>
		<th width="20"><?php _e('ID','dtransport'); ?></th>
		<th><?php _e('Name','dtransport'); ?></th>
		<th><?php _e('Tipo','dtransport'); ?></th>
		<th><?php _e('Approved','dtransport'); ?></th>
		<th><img src="../images/shots.png" alt="<?php _e('Screenshots','dtransport'); ?>" /></th>
		<th><?php _e('Featured','dtransport'); ?></th>
		<th><?php _e('Daily','dtransport'); ?></th>
        <th colspan="7"><?php _e('Options','dtransport'); ?></th>
	</tr>
    </thead>
    <tfoot>
    <tr class="head" align="center">
        <th width="20"><input type="checkbox" id="checkall2" onclick="$('#frm-items').toggleCheckboxes(':not(#checkall,.featured,.approved,.daily)');" /></th>
        <th width="20"><?php _e('ID','dtransport'); ?></th>
        <th><?php _e('Name','dtransport'); ?></th>
        <th><?php _e('Tipo','dtransport'); ?></th>
        <th><?php _e('Approved','dtransport'); ?></th>
        <th><img src="../images/shots.png" alt="<?php _e('Screenshots','dtransport'); ?>" /></th>
        <th><?php _e('Featured','dtransport'); ?></th>
        <th><?php _e('Daily','dtransport'); ?></th>
        <th colspan="7"><?php _e('Options','dtransport'); ?></th>
    </tr>
    </tfoot>
    <tbody>
	<?php foreach($items as $item): ?>
	<tr class="<?php echo tpl_cycle("even,odd"); ?>" align="center" valign="top" id="row-<?php echo $item['id']; ?>">
		<td><input type="checkbox" name="ids[]" value="<?php echo $item['id']; ?>" id="item-<?php echo $item['id']; ?>" /></td>
		<td align="center"><strong><?php echo $item['id']; ?></strong></td>
		<td align="left">
            <span title="<?php if($item['password']): _e('Protected with password','dtransport'); elseif($item['secure']): _e('Protected','dtransport'); endif; ?>" class="item_name"<?php if($item['secure']): ?> style="background: url(../images/<?php if($item['password']): ?>pass<?php else: ?>lockb<?php endif; ?>.png) no-repeat right; padding-right: 20px;"<?php endif; ?>>
            <?php if(!$type && $item['approved']): ?>
			    <a href="<?php echo $item['link']; ?>"><?php echo $item['name']; ?></a>
			<?php else: ?>
				<?php echo $item['name']; ?>
			<?php endif; ?>
            </span>
			<span style="display:block; font-size: 0.9em; margin-top: 4px; color: #999;">
				<?php echo sprintf(__('Last modification on %s by %s','dtransport'), $item['modified'], $item['uname']); ?>
			</span>
		</td>
		<td align="center" class="secure_status"><?php echo $item['secure']?__('Protected','dtransport'):__('Normal','dtransport'); ?></td>
		<td align="center"><input type="checkbox" class="approved" id="approved-<?php echo $item['id']; ?>" name="approved<?php echo $item['id']; ?>"<?php echo $item['approved']?' checked="checked"':''; ?> /></td>
		<td align="center"><?php echo $item['screens']; ?></td>
		<td><input type="checkbox" class="featured" id="featured-<?php echo $item['id']; ?>" name="featured<?php echo $item['id']; ?>"<?php echo $item['featured']?' checked="checked"':''; ?> /></td>
		<td><input type="checkbox" class="daily" id="daily-<?php echo $item['id']; ?>" name="daily<?php echo $item['id']; ?>"<?php echo $item['daily']?' checked="checked"':''; ?> /></td>
        <td class="dt_item_opts" style="padding: 0px; vertical-align: middle; background: #ebebeb;">
            <a href="./items.php?action=edit&amp;id=<?php echo $item['id']; ?>&amp;pag=<?php echo $page; ?>&amp;search=<?php echo $search; ?>&amp;cat=<?php echo $cat; ?>&amp;type=<?php echo $type; ?>" style="background: url(../images/edit.png) no-repeat center;" title="<?php _e('Edit','dtransport'); ?>"><span><?php _e('Edit','dtransport'); ?></span></a>
        </td>
        <td class="dt_item_opts" style="padding: 0px; vertical-align: middle; background: #ebebeb;">
            <a href="./items.php?action=delete&amp;id=<?php echo $item['id']; ?>&amp;pag=<?php echo $page; ?>&amp;search=<?php echo $search; ?>&amp;cat=<?php echo $cat; ?>&amp;type=<?php echo $type; ?>" style="background: url(../images/trash.png) no-repeat center;" title="<?php _e('Delete','dtransport'); ?>"><span><?php _e('Delete','dtransport'); ?></span></a>
        </td>
        <td class="dt_item_opts" style="padding: 0px; vertical-align: middle; background: #ebebeb;">
            <a href="#" class="<?php if(!$item['password']): echo $item['secure'] ? 'unlock' : 'lock'; endif; ?>" style="<?php if($item['password']): ?>opacity: .30;-moz-opacity: .30;filter:alpha(opacity:30);<?php endif; ?>background: url(../images/<?php echo $item['secure']?'unlock.png':'lock.png'; ?>) no-repeat center;" title="<?php $item['secure'] ? _e('Not protected download','dtransport') : _e('Protected download','dtransport'); ?>"><span><?php $item['secure'] ? _e('Unlock','dtransport') : _e('Lock','dtransport'); ?></span></a>
        </td>
        <?php if($type!='edit'): ?>
        <td class="dt_item_opts" style="padding: 0px; vertical-align: middle; background: #ebebeb;"><a href="./screens.php?item=<?php echo $item['id']; ?>" style="background: url(../images/shots.png) no-repeat center;" title="<?php _e('Images','dtransport'); ?>"><span><?php _e('Images','dtransport'); ?></span></a></td>
        <td class="dt_item_opts" style="padding: 0px; vertical-align: middle; background: #ebebeb;"><a href="./features.php?item=<?php echo $item['id']; ?>" style="background: url(../images/features.png) no-repeat center;" title="<?php _e('Features','dtransport'); ?>"><span><?php _e('Features','dtransport'); ?></span></a></td>
        <td class="dt_item_opts" style="padding: 0px; vertical-align: middle; background: #ebebeb;"><a href="files.php?item=<?php echo $item['id']; ?>" style="background: url(../images/files.png) no-repeat center;" title="<?php _e('Files','dtransport'); ?>"><span><?php _e('Files','dtransport'); ?></span></a></td>
        <td class="dt_item_opts" style="padding: 0px; vertical-align: middle; background: #ebebeb;"><a href="logs.php?item=<?php echo $item['id']; ?>" style="background: url(../images/logs.png) no-repeat center;" title="<?php _e('Logs','dtransport'); ?>"><span><?php _e('Logs','dtransport'); ?></span></a></td>
        <?php endif; ?>
	</tr>
	<?php endforeach; ?>
    </tbody>
</table>

<div class="dt_options">

    <select name="actionb" id="bulk-bottom">
        <option value="" selected="selected"><?php _e('Bulk actions...','dtransport'); ?></option>
        <option value="bulk_approve"><?php _e('Approved','dtransport'); ?></option>
        <option value="bulk_unapproved"><?php _e('Not approved','dtransport'); ?></option>
        <option value="bulk_featured"><?php _e('Featured','dtransport'); ?></option>
        <option value="bulk_unfeatured"><?php _e('Not featured','dtransport'); ?></option>
        <option value="bulk_daily"><?php _e('Daily','dtransport'); ?></option>
        <option value="bulk_undaily"><?php _e('Not daily','dtransport'); ?></option>
        <option value="bulk_secure"><?php _e('Protected','dtransport'); ?></option>
        <option value="bulk_nosecure"><?php _e('Not protected','dtransport'); ?></option>
        <option value="bulk_delete"><?php _e('Delete','dtransport'); ?></option>
    </select>
    <input type="button" id="the-op-bottom" value="<?php _e('Apply','dtransport'); ?>" onclick="before_submit('frm-items');" />

</div>

<?php echo $xoopsSecurity->getTokenHTML(); ?>
<input type="hidden" name="page" value="<?php echo $page; ?>"/>
<input type="hidden" name="limit" value="<?php echo $limit; ?>"/>
<input type="hidden" name="type" value="<?php echo $type; ?>"/>
</form>

<div id="status-bar">
    <?php _e('Applying changes, please wait a second...','dtransport'); ?>
</div>
