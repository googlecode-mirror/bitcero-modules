<form name="frmNav" method="post" action="tags.php">
	<table class="outer" width="100%" cellspacing="1">
		<tr class="even">
			<td align="center">
				<input type="text" size="5" name="limit" value="<?php echo $limit; ?>" />
				<input type="submit" class="formButton" value="<?php _e('Apply','admin_galleries'); ?>" /> &nbsp;
				<?php echo _e('Search:','admin_galleries'); ?>
				<input type="text" size="20" name="search" value="<?php echo $search; ?>" />
				<input type="submit" class="formButton" value="<?php _e('Apply','admin_galleries'); ?>" />
				&nbsp; &nbsp;
				<a href="tags.php"><?php _e('Show all','admin_galleries'); ?></a>
			</td>
		</tr>
	</table>
</form>
<form name="frmTags" method="post" action="tags.php">
<table width="100%" class="outer" cellspacing="1">
	<tr>
		<th colspan="<{$columns*6}>"><{$lang_exist}></th>
	</tr>
	<{assign var="i" value=1}>
	<tr class="head" align="center">
	<{foreach item=head from=$header}>
	<{if $i>$columns}>
	</tr>
	<tr>
	<{assign var="i" value=1}>
	<{/if}>
		<td width="20"><{if $i==1}><input type="checkbox" name="checkall" onchange="xoopsCheckAll('frmTags','checkall');" /><{else}>&nbsp;<{/if}></td>
		<td width="30"><{$lang_id}></td>
		<td><{$lang_tag}></td>
		<td><{$lang_pics}></td>
		<td><{$lang_options}></td>
		<{if $i<$columns}><td>&nbsp;</td><{/if}>
	<{assign var="i" value=$i+1}>
	<{/foreach}>
	</tr>

	<{assign var="i" value=1}>
	<tr class='even'>
	<{foreach item=tag from=$tags}>
	<{if $i>$columns}>
	</tr>
	<tr class="<{cycle values='odd,even'}>">
	<{assign var="i" value=1}>
	<{/if}>
		<td align="center"><input type="checkbox" name="ids[]" value="<{$tag.id}>" /></td>
		<td align="center"><strong><{$tag.id}></strong></td>
		<td align="left"><a href="<{$tag.url}>"><strong><{$tag.name}></strong></a></td>
		<td align="center"><{$tag.pics}></td>
		<td align="center"><a href="./tags.php?op=edit&amp;ids=<{$tag.id}>&amp;pag=<{$pag}>&amp;limit=<{$limit}>&amp;search=<{$search}>"><{$lang_edit}></a> &bull; <a href="./tags.php?op=delete&amp;ids=<{$tag.id}>&amp;pag=<{$pag}>&amp;limit=<{$limit}>&amp;search=<{$search}>"><{$lang_del}></a></td>
		<{if $i<$columns}><td>&nbsp;</td><{/if}>
	<{assign var="i" value=$i+1}>
	<{/foreach}>
	</tr>
	<tr class="foot">
		<td align="right"><img src="<{$xoops_url}>/images/root.gif" /></td>
		<td colspan="<{$columns*6}>">
			<input type="submit" class="formButton" value="<{$lang_edit}>" onclick="document.forms['frmTags'].op.value='edit'"/>
			<input type="submit" class="formButton" value="<{$lang_del}>" onclick="document.forms['frmTags'].op.value='delete'"/>
	
		</td>

	</tr>
</table>
<input type="hidden" name="op" />
<input type="hidden" name="pag" value="<{$pag}>" />
<input type="hidden" name="limit" value="<{$limit}>" />
<input type="hidden" name="search" value="<{$search}>" />
</form>