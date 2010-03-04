<h1 class="rmc_titles"><span style="background-position: left -32px;">&nbsp;</span><?php _e('Customer Types','admin_works'); ?></h1>

<form name="frmTypes" method="POST" action="types.php">
<table width="100%" cellspacing="0" class="outer">
	<tr class="head" align="center">
		<th width="20"><input type="checkbox" name="checkAll" onclick="xoopsCheckAll('frmTypes','checkAll')" /></th>
		<th width="30"><{$lang_id}></th>
		<th><{$lang_type}></th>
		<th><{$lang_options}></th>
	</tr>
	<{foreach item=type from=$types}>
	<tr class="<{cycle values='even,odd'}>" align="center">
		<td><input type="checkbox" name="ids[]" value="<{$type.id}>" /></td>
		<td><strong><{$type.id}></strong></td>
		<td align="left"><{$type.type}></td>
		<td><a href="./types.php?op=edit&amp;ids=<{$type.id}>"><{$lang_edit}></a> &bull; <a href="./types.php?op=delete&amp;ids=<{$type.id}>"><{$lang_delete}></a></td>
	</tr>
	<{/foreach}>
	<tr class="foot">
		<td align="right"><img src="<{$xoops_url}>/images/root.gif" /></td>
		<td colspan="3">
			<input type="submit" class="formButtonRed" value="<{$lang_edit}>" onclick="document.forms['frmTypes'].op.value='edit';" />
			<input type="submit" class="formButton" value="<{$lang_delete}>" onclick="document.forms['frmTypes'].op.value='delete';" />
		</td>
	</tr>
</table>
<input type="hidden" name="op" />
</form>
