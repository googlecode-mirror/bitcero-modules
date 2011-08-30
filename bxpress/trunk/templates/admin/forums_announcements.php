<form name="frmAnnoun" method="post" action="announcements.php">
<table class="outer" cellspacing="1" width="100%">
	<tr>
		<th colspan="7"><{$lang_existing}></th>
	</tr>
	<tr class="head" align="center">
		<td width="20"><input type="checkbox" name="checkall" onchange="xoopsCheckAll('frmAnnoun','checkall');" /></td>
		<td width="50"><{$lang_id}></td>
		<td><{$lang_announcement}></td>
		<td><{$lang_expire}></td>
		<td><{$lang_where}></td>
		<td><{$lang_by}></td>
		<td><{$lang_options}></td>
	</tr>
	<{foreach item=anoun from=$announcements}>
		<tr class="<{cycle values="even,odd"}>" align="center">
			<td><input type="checkbox" name="announcements[<{$anoun.id}>]" value="<{$anoun.id}>" /></td>
			<td><strong><{$anoun.id}></strong></td>
			<td align="left"><{$anoun.text}></td>
			<td><{$anoun.expire}></td>
			<td><a href="<{$anoun.wherelink}>"><{$anoun.where}></a></td>
			<td><{$anoun.by}></td>
			<td><a href="?op=edit&amp;id=<{$anoun.id}>"><{$lang_edit}></a> &bull; 
			<a href="?op=delete&amp;id=<{$anoun.id}>"><{$lang_delete}></a></td>
		</tr>
	<{/foreach}>
	<tr class="foot">
		<td align="right"><img src="<{$xoops_url}>/images/root.gif" alt="" /></td>
		<td colspan="6" align="left">
			<input type="submit" value="<{$lang_deletebulk}>" class="formButton" onclick="return confirm('<{$lang_confdelbulk}>');" />
		</td>
	</tr>
</table>
<input type="hidden" name="op" value="delete" />
<{$token}>
</form>