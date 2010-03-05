<h1 class="rmc_titles"><span style="background-position: left -32px;">&nbsp;</span><?php _e('Images Management','admin_galleries'); ?></h1>

<form name="frmNav" id="frmNav" method="get" action="images.php">
	<table class="outer" width="100%" cellspacing="1">
		<tr class="evenSearch">
			<td colspan="2">
				<table cellspacing="1" cellpadding="2" border="0">
					<tr class="searchImages">
						<td>
						<?php _e('Results number.','admin_galleries'); ?> <input type="text" size="5" name="limit" value="<?php echo $limit; ?>" />
						&nbsp; <?php _e('Search:','admin_galleries'); ?> <input type="text" size="20" name="search" value="<?php echo $search; ?>" />
						</td>
						<td><?php _e('Entre','admin_galleries'); ?> <?php echo $tpl->get_var('mindate_field'); ?>
						<?php _e('y','admin_galleries'); ?> <?php echo $tpl->get_var('maxdate_field'); ?></td>
						<td><?php echo $tpl->get_var('user_field'); ?></td>
						<td><input type="submit" value="<?php _e('Apply','admin_galleries'); ?>" class="formButton" /></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>
<form name="frmImgs"  method="POST" if="frm-images" action="images.php">
<div class="gs_options">
	<select name="op" id="select-op-top">
		<option value=""><?php _e('Bulk actions...','admin_galleries'); ?></option>
		<option value="public"><?php _e('Set as public','admin_galleries'); ?></option>
		<option value="private"><?php _e('Set as private','admin_galleries'); ?></option>
		<option value="privatef"><?php _e('Set as public for friends','admin_galleries'); ?></option>
		<option value="delete"><?php _e('Delete','admin_galleries'); ?></option>
	</select>
	<input type="button" id="op-top" value="<?php _e('Apply','admin_galleries'); ?>" />
</div>
<table width="100%" class="outer" cellspacing="1">
	<thead>
	<tr class="head">
		<td colspan="7"><div id="gsPrivacyInfo">
			<img src="<?php echo XOOPS_URL; ?>/modules/galleries/images/public.png" align="absmiddle" /><?php _e('Public','admin_galleries'); ?> |
			<img src="<?php echo XOOPS_URL; ?>/modules/galleries/images/private.png" align="absmiddle" /><?php _e('Private','admin_galleries'); ?> |
			<img src="<?php echo XOOPS_URL; ?>/modules/galleries/images/faccess.png" align="absmiddle" /><?php _e('Public for friends','admin_galleries'); ?> 
		</div></td>
	</tr>
	</thead>
	
	<thead>
	<tr align="center">
		<th width="20"><input type="checkbox" name="checkall" id="checkall" onclick='$("#frm-images").toggleCheckboxes(":not(#checkall)");' /></th>
		<th width="30"><a href="./images.php?pag=<{$pag}>&amp;limit=<{$limit}>&amp;&amp;owner=<{$owner}>search=<{$search}>&amp;sort=id_image&amp;mode=<{if $sort=='id_image'}><{if $mode==1}>0<{else}>1<{/if}><{else}>0<{/if}>"><{$lang_id}></a></th>
		<th>&nbsp;</th>
		<th><a href="./images.php?pag=<{$pag}>&amp;limit=<{$limit}>&amp;owner=<{$owner}>&amp;search=<{$search}>&amp;sort=title&amp;mode=<{if $sort=='title'}><{if $mode==1}>0<{else}>1<{/if}><{else}>0<{/if}>"><{$lang_title}></a></th>
		<th><a href="./images.php?pag=<{$pag}>&amp;limit=<{$limit}>&amp;owner=<{$owner}>&amp;search=<{$search}>&amp;sort=public&amp;mode=<{if $sort=='public'}><{if $mode==1}>0<{else}>1<{/if}><{else}>0<{/if}>"><{$lang_public}></a></th>
		<th><a href="./images.php?pag=<{$pag}>&amp;limit=<{$limit}>&amp;owner=<{$owner}>&amp;search=<{$search}>&amp;sort=created&amp;mode=<{if $sort=='created'}><{if $mode==1}>0<{else}>1<{/if}><{else}>0<{/if}>"><{$lang_date}></a></th>
		<th><a href="./images.php?pag=<{$pag}>&amp;limit=<{$limit}>&amp;owner=<{$owner}>&amp;search=<{$search}>&amp;sort=owner&amp;mode=<{if $sort=='owner'}><{if $mode==1}>0<{else}>1<{/if}><{else}>0<{/if}>"><{$lang_owner}></a></th>
	</tr>
	</thead>
	
	<tfoot>
	<tr align="center">
		<th width="20"><input type="checkbox" name="checkall" id="checkall" onclick='$("#frm-images").toggleCheckboxes(":not(#checkall)");' /></th>
		<th width="30"><a href="./images.php?pag=<{$pag}>&amp;limit=<{$limit}>&amp;&amp;owner=<{$owner}>search=<{$search}>&amp;sort=id_image&amp;mode=<{if $sort=='id_image'}><{if $mode==1}>0<{else}>1<{/if}><{else}>0<{/if}>"><{$lang_id}></a></th>
		<th>&nbsp;</th>
		<th><a href="./images.php?pag=<{$pag}>&amp;limit=<{$limit}>&amp;owner=<{$owner}>&amp;search=<{$search}>&amp;sort=title&amp;mode=<{if $sort=='title'}><{if $mode==1}>0<{else}>1<{/if}><{else}>0<{/if}>"><{$lang_title}></a></th>
		<th><a href="./images.php?pag=<{$pag}>&amp;limit=<{$limit}>&amp;owner=<{$owner}>&amp;search=<{$search}>&amp;sort=public&amp;mode=<{if $sort=='public'}><{if $mode==1}>0<{else}>1<{/if}><{else}>0<{/if}>"><{$lang_public}></a></th>
		<th><a href="./images.php?pag=<{$pag}>&amp;limit=<{$limit}>&amp;owner=<{$owner}>&amp;search=<{$search}>&amp;sort=created&amp;mode=<{if $sort=='created'}><{if $mode==1}>0<{else}>1<{/if}><{else}>0<{/if}>"><{$lang_date}></a></th>
		<th><a href="./images.php?pag=<{$pag}>&amp;limit=<{$limit}>&amp;owner=<{$owner}>&amp;search=<{$search}>&amp;sort=owner&amp;mode=<{if $sort=='owner'}><{if $mode==1}>0<{else}>1<{/if}><{else}>0<{/if}>"><{$lang_owner}></a></th>
	</tr>
	</tfoot>
	
	<tbody>
	<?php if(empty($images)): ?>
	<tr class="even"><td colspan="7" align="center"><?php _e('Tehere are not images created yet!','admin_galleries'); ?></td></tr>
	<?php endif; ?>
	<?php foreach($images as $img): ?>
	<tr class="<{cycle values='even,odd'}>" align="center">
		<td><input type="checkbox" name="ids[]" value="<{$img.id}>" /></td>
		<td><strong><{$img.id}></strong></td>
		<td width="50" class="listimg"><a href="<{$img.link}>"><img src="<{$img.image}>" alt="" /></a></td>
		<td align="left"><a href="<{$img.link}>"><strong><{$img.title}></strong></a>
			<{if $img.desc!=''}><span class="imgDesc"><br /><{$img.desc}></span><{/if}></td>
		<td><{if $img.public==2}><img src="<{$xoops_url}>/modules/galleries/images/public.png" title="<{$lang_publicimg}>"/><{elseif $img.public==1}><img src="<{$xoops_url}>/modules/galleries/images/faccess.png" title="<{$lang_privatef}>" /><{else}><img src="<{$xoops_url}>/modules/galleries/images/private.png" title="<{$lang_nopublicimg}>" /><{/if}></td>
		<td nowrap="nowrap"><{$img.created}></td>
		<td><{$img.owner}></td>
		<td nowrap="nowrap"><a href="./images.php?op=edit&amp;id=<{$img.id}>&amp;pag=<{$pag}>&amp;limit=<{$limit}>&amp;search=<{$search}>&amp;owner=<{$owner}>&amp;sort=<{$sort}>&amp;mode=<{$mode}>"><{$lang_edit}></a> &bull; <a href="./images.php?op=delete&amp;ids=<{$img.id}>&amp;pag=<{$pag}>&amp;limit=<{$limit}>&amp;search=<{$search}>&amp;sort=<{$sort}>&amp;mode=<{$mode}>"><{$lang_del}></a></td>
	</tr>
	<?php endforeach; ?>
	</tbody>
</table>
<input type="hidden" name="op" id="op" />
<input type="hidden" name="pag" value="<{$pag}>" />
<input type="hidden" name="limit" value="<{$limit}>" />
<input type="hidden" name="search" value="<{$search}>" />
<input type="hidden" name="owner" value="<{$owner}>" />
<input type="hidden" name="sort" value="<{$sort}>" />
<input type="hidden" name="mode" value="<{$mode}>" />
<?php echo $xoopsSecurity->getTokenHTML(); ?>
</form>
<table class="outer" width="100%" cellspacing="1">
		<tr class="even">
			<td><{$lang_showing}></td>
			<td width="60%" align="right"><{$imgsNavPage}></td>
		</tr>
		
</table>
