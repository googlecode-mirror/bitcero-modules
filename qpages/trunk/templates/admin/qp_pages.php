<h1 class="rmc_titles"><span style="background-position: -32px 0;">&nbsp;</span><?php _e('Pages','qpages'); ?></h1>

<form name="frmSearch" method="get" action="pages.php" style="margin: 0;">
<div class="qp_options">
    <?php _e('Search:','qpages'); ?>
    <input type="text" name="keyw" value="<?php echo $keyw ?>" size="30" /> &#160; 
    <?php _e('Category','qpages'); ?>
    <select name="cat" onchange="submit();">
        <?php foreach($categories as $cat): ?>
            <option value="<?php echo $cat['id']; ?>"<?php if($cat['id']==$catego): ?> selected="selected"<?php endif; ?>><?php echo $cat['nombre']; ?></option>
        <?php endforeach; ?>
    </select>
    <input type="submit" name="sbt" value="<?php _e('Apply','qpages'); ?>" class="formButton" />
</div>
</form>

<form name="modPages" method="post" action="pages.php">
<table width="100%" border="0" cellspacing="1" cellpadding="0" class="outer">
    <thead>
    <tr class="head" align="center">
  	    <th width="20"><input<?php if(empty($categories)): ?> disabled="disabled"<?php endif; ?> type="checkbox" id="checkall" onclick='$("#frm-categos").toggleCheckboxes(":not(#checkall)");' /></th>
        <th width="30"><?php _e('ID','qpages'); ?></th>
        <th width="26">&nbsp;</th>
        <th><?php _e('Title','qpages'); ?></th>
        <th><?php _e('Modified','qpages'); ?></th>
        <th><?php _e('Views','qpages'); ?></th>
        <th><?php _e('Visibility','qpages'); ?></th>
        <th><?php _e('Diplay order','qpages'); ?></th>
    </tr>
    </thead>
    <tbody>
    <?php if(empty($paginas)): ?>
        <tr class="even">
            <td align="center" colspan="8"><?php _e('There are not pages registered yet!','qpages'); ?></td>
        </tr>
    <?php endif; ?>
    <?php foreach($paginas as $page): ?>
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
    <?php endforeach; ?>
    </tbody>
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
