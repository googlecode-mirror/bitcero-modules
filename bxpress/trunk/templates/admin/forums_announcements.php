<h1 class="rmc_titles"><?php _e('Announcements Management','bxpress'); ?></h1>

<form name="frmAnnoun" id="frm-announ" method="post" action="announcements.php">
<table class="outer" cellspacing="1" width="100%">
    <thead>
	<tr class="head" align="center">
		<th width="20"><input type="checkbox" id="checkall" onchange="$('#frm-announ').toggleCheckboxes(':not(#checkall)');" /></th>
		<th width="50"><?php _e('ID','bxpress'); ?></th>
		<th><?php _e('Announcement','bxpress'); ?></th>
		<th><?php _e('Expire','bxpress'); ?></th>
		<th><?php _e('Location','bxpress'); ?></th>
		<th><?php _e('By','bxpress'); ?></th>
	</tr>
    </thead>
    <tfoot>
    <tr class="head" align="center">
		<th width="20"><input type="checkbox" id="checkall2" onchange="$('#frm-announ').toggleCheckboxes(':not(#checkall2)');" /></th>
		<th width="50"><?php _e('ID','bxpress'); ?></th>
		<th><?php _e('Announcement','bxpress'); ?></th>
		<th><?php _e('Expire','bxpress'); ?></th>
		<th><?php _e('Location','bxpress'); ?></th>
		<th><?php _e('By','bxpress'); ?></th>
	</tr>
    </tfoot>
    <tbody>
    <?php if(empty($announcements)): ?>
        <tr class="even">
            <td align="center" colspan="6"><?php _e('There are not announcements created yet!','bxpress'); ?></td>
        </tr>
    <?php endif; ?>
	<?php foreach($announcements as $item): ?>
		<tr class="<?php echo tpl_cycle("even,odd"); ?>" align="center" valign="top">
			<td><input type="checkbox" name="ids[]" id="item-<?php echo $item['id']; ?>" value="<?php echo $item['id']; ?>" /></td>
			<td><strong><?php echo $item['id']; ?></strong></td>
			<td align="left">
                <?php echo $item['text']; ?>
                <span class="rmc_options">
                    <a href="?op=edit&amp;id=<{$anoun.id}>"><{$lang_edit}></a> |
                    <a href="?op=delete&amp;id=<{$anoun.id}>"><{$lang_delete}></a>
                </span>
            </td>
			<td><?php echo $item['expire']; ?></td>
			<td><a href="<?php echo $item['wherelink']; ?>"><?php echo $item['where']; ?></a></td>
			<td><?php echo $item['by']; ?></td>
		</tr>
	<?php endforeach; ?>
    </tbody>
</table>
<input type="hidden" name="op" value="delete" />
<?php echo $xoopsSecurity->getTokenHTML(); ?>
</form>