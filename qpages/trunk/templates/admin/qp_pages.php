<h1 class="rmc_titles"><span style="background-position: -32px 0;">&nbsp;</span><?php _e('Pages','qpages'); ?></h1>

<form name="frmSearch" method="get" action="pages.php" style="margin: 0;">
<table width="100%" cellspacing="1" cellpadding="3" border="0">
	<tr class="even">
	<td align="left">
			<?php _e('Search:','qpages'); ?>
			<input type="text" name="keyw" value="<?php echo $keyw ?>" size="30" />
	</td>
	<td align="center">
		<?php _e('Category','qpages'); ?>
		<select name="cat" onchange="submit();">
			<?php foreach($categories as $cat): ?>
				<option value="<?php echo $cat['id']; ?>"<?php if($cat['id']==$catego): ?> selected="selected"<?php endif; ?>><?php echo $cat['nombre']; ?></option>
			<?php endforeach; ?>
		</select>
	</td>
	<td align="center">
		<{$lang_results}>
		<input type="text" size="10" name="limit" value="<{$limit}>" />
		<input type="submit" name="sbt" value="<{$lang_submit}>" class="formButton" />
	</td>
	<td align="center">
		<a href="pages.php?limit=<{$limit}>"><{$lang_showall}></a>
	</td>
	<td align="right">
		
	</td></tr>
</table></form>

<form name="modPages" method="post" action="pages.php">
<table width="100%" border="0" cellspacing="1" cellpadding="0" class="outer">
  <tr class="head" align="center">
  	<th align="center" width="30"><input type="checkbox" name="chekall" value="1" onchange="xoopsCheckAll('modPages', 'chekall');" /></th>
    <th width="30"><{$lang_id}></th>
    <th width="26">&nbsp;</th>
    <th><{$lang_title}></th>
    <th><{$lang_modif}></th>
	<th><{$lang_inmenu}></th>
    <th><{$lang_reads}></th>
    <th><{$lang_access}></th>
    <th><{$lang_order}></th>
	<th><{$lang_options}></th>
  </tr>
  <{foreach item=page from=$paginas}>
  <tr class="<{cycle values="even,odd"}>" align="center">
  	<td><input type="checkbox" name="pages[]" value="<{$page.id}>" /></td>
    <td><strong><{$page.id}></strong></td>
    <td>
    	<img src="../images/page<{if $page.type}>_go<{/if}>.png" alt="" />
    </td>
    <td align="left"><a href="<{$page.link}>"><{$page.titulo}></a></td>
    <td><{$page.modificada}></td>
	<td><strong><{$page.menu}></strong></td>
    <td align="center"><{$page.lecturas}></td>
    <td><{$page.estado}></td>
    <td><input type="text" size="5" value="<{$page.order}>" name="porder[<{$page.id}>]" style="text-align: center;" /></td>
	<td><a href="pages.php?op=edit<{if $page.type}>link<{/if}>&amp;id=<{$page.id}>&amp;cat=<{$catego}>" title="<{$lang_edit}>"><{$lang_edit}></a> &bull;
	<a href="pages.php?op=delete&amp;id=<{$page.id}>&amp;cat=<{$catego}>" title="<{$lang_delete}>"><{$lang_delete}></a></td>
  </tr>
  <{/foreach}>
  <tr class="foot">
  	<td align="center"><img src="<{$xoops_url}>/images/root.gif" align="absmiddle" /></td>
	<td colspan="9">
		<input type="hidden" name="op" value="" />
		<{if $acceso<0}>
			<input type="button" name="action" value="<{$lang_privatize}>" class='formButton' onclick="document.forms['modPages'].elements['op'].value='privatize'; submit();" />
			<input type="button" name="action" value="<{$lang_publicate}>" class='formButton' onclick="document.forms['modPages'].elements['op'].value='publicate'; submit();" />
		<{elseif $acceso==1}>
			<input type="button" name="action" value="<{$lang_privatize}>" class='formButton' onclick="document.forms['modPages'].elements['op'].value='privatize'; submit();" />
		<{elseif $acceso==0}>
			<input type="button" name="action" value="<{$lang_publicate}>" class='formButton' onclick="document.forms['modPages'].elements['op'].value='publicate'; submit();" />
		<{/if}>
		<input type="button" value="<{$lang_savechanges}>" class="formButtonBlue" onclick="document.forms['modPages'].elements['op'].value='savechanges'; submit();" />
		<input type="button" value="<{$lang_linked}>" class="formButtonGreen" onclick="document.forms['modPages'].elements['op'].value='linked'; submit();" />
		<input type="hidden" name="cat" value="<{$catego}>" />
		<input type="hidden" name="page" value="<{$pactual}>" />
		<input type="hidden" name="limit" value="<{$limit}>" />
	</td>
  </tr>
</table>
<table width="100%" cellspacing="1" cellpadding="3" border="0">
	<tr>
	<td align="right">
		<{foreach item=pag from=$pages}>
			<{if $pag.id=='anterior'}>
				<a href="<{$pag.link}>">&laquo; <{$lang_prev}></a>
			<{elseif $pag.id=='siguiente'}>
				<a href="<{$pag.link}>"><{$lang_next}> &raquo;</a>
			<{else}>
				<{if $pag.id==$pactual}>
					<span class="nppageactual"><{$pag.id}></span>
				<{else}>
				<a href="<{$pag.link}>"><{$pag.id}></a>
				<{/if}>
			<{/if}>
		<{/foreach}>
	</td>
	</tr>
</table>
</form>
