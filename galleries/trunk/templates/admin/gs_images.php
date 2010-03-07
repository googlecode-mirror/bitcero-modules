<h1 class="rmc_titles"><span style="background-position: left -32px;">&nbsp;</span><?php _e('Images Management','admin_galleries'); ?></h1>

<form name="frmNav" id="frmNav" method="get" action="images.php">
	<table class="outer" width="100%" cellspacing="1">
		<tr class="even">
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
		<th width="30"><a href="./images.php?pag=<?php echo $page; ?>&amp;limit=<?php echo $limit; ?>&amp;owner=<?php echo $owner; ?>&amp;search=<?php echo $search; ?>&amp;sort=id_image&amp;mode=<?php if($sort=='id_image'): ?><?php if($mode==1): ?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>"><?php _e('ID','admin_galleries'); ?></a></th>
		<th>&nbsp;</th>
		<th><a href="./images.php?pag=<?php echo $page; ?>&amp;limit=<?php echo $limit; ?>&amp;owner=<?php echo $owner; ?>&amp;search=<?php echo $search; ?>&amp;sort=title&amp;mode=<?php if($sort=='title'): ?><?php if($mode==1): ?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>"><?php _e('Title','admin_galleries'); ?></a></th>
		<th><a href="./images.php?pag=<?php echo $page; ?>&amp;limit=<?php echo $limit; ?>&amp;owner=<?php echo $owner; ?>&amp;search=<?php echo $search; ?>&amp;sort=public&amp;mode=<?php if($sort=='public'): ?><?php if($mode==1): ?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>"><?php _e('Visibility','admin_galleries'); ?></a></th>
		<th><a href="./images.php?pag=<?php echo $page; ?>&amp;limit=<?php echo $limit; ?>&amp;owner=<?php echo $owner; ?>&amp;search=<?php echo $search; ?>&amp;sort=created&amp;mode=<?php if($sort=='created'): ?><?php if($mode==1): ?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>"><?php _e('Creation','admin_galleries'); ?></a></th>
		<th><a href="./images.php?pag=<?php echo $page; ?>&amp;limit=<?php echo $limit; ?>&amp;owner=<?php echo $owner; ?>&amp;search=<?php echo $search; ?>&amp;sort=owner&amp;mode=<?php if($sort=='owner'): ?><?php if($mode==1): ?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>"><?php _e('Owner','admin_galleries'); ?></a></th>
	</tr>
	</thead>
	
	<tfoot>
	<tr align="center">
        <th width="20"><input type="checkbox" name="checkall2" id="checkall2" onclick='$("#frm-images").toggleCheckboxes(":not(#checkall2)");' /></th>
        <th width="30"><a href="./images.php?pag=<?php echo $page; ?>&amp;limit=<?php echo $limit; ?>&amp;owner=<?php echo $owner; ?>&amp;search=<?php echo $search; ?>&amp;sort=id_image&amp;mode=<?php if($sort=='id_image'): ?><?php if($mode==1): ?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>"><?php _e('ID','admin_galleries'); ?></a></th>
        <th>&nbsp;</th>
        <th><a href="./images.php?pag=<?php echo $page; ?>&amp;limit=<?php echo $limit; ?>&amp;owner=<?php echo $owner; ?>&amp;search=<?php echo $search; ?>&amp;sort=title&amp;mode=<?php if($sort=='title'): ?><?php if($mode==1): ?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>"><?php _e('Title','admin_galleries'); ?></a></th>
        <th><a href="./images.php?pag=<?php echo $page; ?>&amp;limit=<?php echo $limit; ?>&amp;owner=<?php echo $owner; ?>&amp;search=<?php echo $search; ?>&amp;sort=public&amp;mode=<?php if($sort=='public'): ?><?php if($mode==1): ?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>"><?php _e('Visibility','admin_galleries'); ?></a></th>
        <th><a href="./images.php?pag=<?php echo $page; ?>&amp;limit=<?php echo $limit; ?>&amp;owner=<?php echo $owner; ?>&amp;search=<?php echo $search; ?>&amp;sort=created&amp;mode=<?php if($sort=='created'): ?><?php if($mode==1): ?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>"><?php _e('Creation','admin_galleries'); ?></a></th>
        <th><a href="./images.php?pag=<?php echo $page; ?>&amp;limit=<?php echo $limit; ?>&amp;owner=<?php echo $owner; ?>&amp;search=<?php echo $search; ?>&amp;sort=owner&amp;mode=<?php if($sort=='owner'): ?><?php if($mode==1): ?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>"><?php _e('Owner','admin_galleries'); ?></a></th>
    </tr>
	</tfoot>
	
	<tbody>
	<?php if(empty($images)): ?>
	<tr class="even"><td colspan="7" align="center"><?php _e('There are not images created yet!','admin_galleries'); ?></td></tr>
	<?php endif; ?>
	<?php foreach($images as $img): ?>
	<tr class="<?php echo tpl_cycle('even,odd'); ?>" align="center" valign="top">
		<td><input type="checkbox" name="ids[]" value="<?php echo $img['id']; ?>" id="item-<?php echo $img['id']; ?>" /></td>
		<td><strong><?php echo $img['id']; ?></strong></td>
		<td width="50" class="listimg"><a href="<?php echo $img['link']; ?>"><img src="<?php echo $img['image']; ?>" alt="" style="width: 50px;" /></a></td>
		<td align="left"><a href="<{$img.link}>"><strong><?php echo $img['title']; ?></strong></a>
			<?php if($img['desc']!=''): ?><span class="imgDesc"><br /><?php echo $img['desc']; ?></span><?php endif; ?>
			<span class="rmc_options">
				<a href="./images.php?op=edit&amp;id=<{$img.id}>&amp;pag=<?php echo $page; ?>&amp;limit=<?php echo $limit; ?>&amp;search=<?php echo $search; ?>&amp;owner=<?php echo $owner; ?>&amp;sort=<{$sort}>&amp;mode=<{$mode}>"><{$lang_edit}></a> &bull; <a href="./images.php?op=delete&amp;ids=<{$img.id}>&amp;pag=<?php echo $page; ?>&amp;limit=<?php echo $limit; ?>&amp;search=<?php echo $search; ?>&amp;sort=<{$sort}>&amp;mode=<{$mode}>"><{$lang_del}></a>
			</span>
		</td>
		<td><?php if($img['public']==2): ?><img src="<?php echo XOOPS_URL; ?>/modules/galleries/images/public.png" title="<?php _e('Public','admin_galleries'); ?>" /><?php elseif($img['public']==1): ?><img src="<?php echo XOOPS_URL; ?>/modules/galleries/images/faccess.png" title="<?php _e('Public for friends','admin_galleries'); ?>" /><?php else: ?><img src="<?php echo XOOPS_URL; ?>/modules/galleries/images/private.png" title="<?php _e('Private','admin_galleries'); ?>" /><?php endif; ?></td>
		<td nowrap="nowrap"><?php echo $img['created']; ?></td>
		<td><?php echo $img['owner']; ?></td>
	</tr>
	<?php endforeach; ?>
	</tbody>
</table>
<div class="gs_options">
    <select name="op" id="select-op-bottom">
        <option value=""><?php _e('Bulk actions...','admin_galleries'); ?></option>
        <option value="public"><?php _e('Set as public','admin_galleries'); ?></option>
        <option value="private"><?php _e('Set as private','admin_galleries'); ?></option>
        <option value="privatef"><?php _e('Set as public for friends','admin_galleries'); ?></option>
        <option value="delete"><?php _e('Delete','admin_galleries'); ?></option>
    </select>
    <input type="button" id="op-bottom" value="<?php _e('Apply','admin_galleries'); ?>" />
</div>
<input type="hidden" name="op" id="op" />
<input type="hidden" name="pag" value="<?php echo $page; ?>" />
<input type="hidden" name="limit" value="<?php echo $limit; ?>" />
<input type="hidden" name="search" value="<?php echo $search; ?>" />
<input type="hidden" name="owner" value="<?php echo $owner; ?>" />
<input type="hidden" name="sort" value="<{$sort}>" />
<input type="hidden" name="mode" value="<{$mode}>" />
<?php echo $xoopsSecurity->getTokenHTML(); ?>
</form>
