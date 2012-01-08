<h1 class="rmc_titles dt_titles"><span style="background-position: left -32px;">&nbsp;</span><?php echo sprintf(__('Categories Management','dtransport')); ?></h1>

<form name="frmcat" id="frm-categories" method="POST" action="categories.php">
<table width="100%" class="outer" cellspacing="1">
    <thead>
	<tr class="head" align="center">
		<th width="20"><input type="checkbox" id="checkall" onclick='$("#frm-categories").toggleCheckboxes(":not(#checkall)");' /></th>
		<th align="left"><?php _e('ID','dtransport'); ?></th>
		<th><?php _e('Category name','dtransport'); ?></th>
		<th><?php _e('Active','dtransport'); ?></th>
	</tr>
    </thead>
    <tfoot>
    <tr class="head" align="center">
        <th width="20"><input type="checkbox" id="checkall2" onclick='$("#frm-categories").toggleCheckboxes(":not(#checkall2)");' /></th>
        <th align="left"><?php _e('ID','dtransport'); ?></th>
        <th><?php _e('Category name','dtransport'); ?></th>
        <th><?php _e('Active','dtransport'); ?></th>
    </tr>
    </tfoot>
    <tbody>
	<?php foreach($categories as $cat): ?>
	<tr class="<?php echo tpl_cycle('even,odd'); ?>" align="center">
		<td><input type="checkbox" name="ids[]" id="item-<?php echo $cat['id']; ?>" value="<?php echo $cat['id']; ?>" /></td>
		<td width="20"><strong><?php echo $cat['id']; ?></strong></td>
		<td align="left"> <a href="./items.php?cat=<{$cat.id}>"><?php echo str_repeat("&#8212;", $cat['indent']); ?> <{$cat.name}></a></td>
		<td><{if $cat.active}><img src="<{$xoops_url}>/modules/dtransport/images/ok.png" border="0" /><{else}><img src="<{$xoops_url}>/modules/dtransport/images/no.png" border="0" /><{/if}></td>
		<td><a href="./categories.php?op=edit&amp;id=<{$cat.id}>"><{$lang_edit}></a> &bull; <a href="./categories.php?op=delete&amp;id=<{$cat.id}>"><{$lang_del}></a></td>
	</tr>
	<?php endforeach; ?>
    </tbody>
	<tr class="foot">
		<td align="right"><img src="<{$xoops_url}>/images/root.gif" border=0" /></td>
		<td colspan="4">
			<input type="submit" class="formButtonBlue" value="<{$lang_activ}>" onclick="document.forms['frmcat'].elements['op'].value='active';" />
			<input type="submit" class="formButtonRed" value="<{$lang_desactiv}>" onclick="document.forms['frmcat'].elements['op'].value='desactive';" />
			<input type="submit" class="formButton" value="<{$lang_del}>" onclick="document.forms['frmcat'].elements['op'].value='delete';" />


		</td>
</table>
<input type="hidden" name="op" />
</form>
