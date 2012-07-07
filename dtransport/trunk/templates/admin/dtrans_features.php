<h1 class="rmc_titles dt_titles"><span style="background-position: left -32px;">&nbsp;</span><?php echo sprintf(__('Features of "%s"','dtransport'), $sw->getVar('name')); ?></h1>

<form name="frmfeat" id="frm-feats" method="POST" action="features.php">
<table width="100%" class="outer" cellspacing="1">
	<tr class="head" align="center">
		<th width="20"><input type="checkbox" name="checkAll" onclick="xoopsCheckAll('frmfeat','checkAll')" /></th>
		<th><?php _e('ID','dtransport'); ?></th>
		<th><?php _e('Title','dtransport'); ?></th>
		<th><?php _e('Created','dtransport'); ?></th>
		<th><?php _e('Modified','dtransport'); ?></th>
		<th><{$lang_options}></th>
	</tr>
	<?php foreach($features as $feature): ?>
	<tr class="<{cycle values='even,odd'}>">
		<td><input type="checkbox" name="ids[]" id="item-<?php echo $feature['id']; ?>" value="<?php echo $feature['id']; ?>" /></td>
		<td align="center" width="20"><strong><?php echo $feature['id']; ?></strong></td>
		<td><?php echo $feature['title']; ?></td>
		<td align="center"><?php echo $feature['created']; ?></td>
		<td align="center"><?php echo $feature['modified']; ?></td>
		<td align="center">
            <a href="features.php?action=edit&amp;id=<?php echo $feature['id']; ?>&amp;item=<?php echo $item; ?>"><?php _e('Edit','dtransport'); ?></a> |
            <a href="features.php?action=delete&amp;id=<?php echo $feature['id']; ?>&amp;item=<?php echo $item; ?>"><?php _e('Delete','dtransport'); ?></a>
        </td>
	</tr>		
    <?php endforeach; ?>
</table>
<input type="hidden" name="item" value="<?php echo $item; ?>" />
</form>