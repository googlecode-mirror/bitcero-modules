<h1 class="rmc_titles"><span style="background-position: left -32px;">&nbsp;</span><?php _e('Images Management','galleries'); ?></h1>

<form name="frmImgs"  method="POST" id="frm-images" action="images.php">
<div class="gs_options">
    <?php $nav->display(false); ?>
	<select name="op" id="select-op-top">
		<option value=""><?php _e('Bulk actions...','galleries'); ?></option>
        <option value="redo" class="redo-thumbs"><?php _e('Generate Thumbnails','galleries'); ?></option>
		<option value="public"><?php _e('Set as public','galleries'); ?></option>
		<option value="private"><?php _e('Set as private','galleries'); ?></option>
		<option value="privatef"><?php _e('Set as public for friends','galleries'); ?></option>
		<option value="delete"><?php _e('Delete','galleries'); ?></option>
	</select>
	<input type="button" id="op-top" value="<?php _e('Apply','galleries'); ?>" /> &nbsp; &nbsp;
    <a href="#" class="gs_filter_options"><?php _e('Filter Options','galleries'); ?></a>
</div>
<div id="gs-filters">
    <table>
        <tr>
            <td>
                        <?php _e('Search:','galleries'); ?> <input type="text" size="20" name="search" value="<?php echo $search; ?>" />
                        </td>
                        <td><?php _e('Entre','galleries'); ?> <?php echo $tpl->get_var('mindate_field'); ?>
                        <?php _e('y','galleries'); ?> <?php echo $tpl->get_var('maxdate_field'); ?></td>
                        <td><?php echo $tpl->get_var('user_field'); ?></td>
                        <td><input type="submit" value="<?php _e('Apply','galleries'); ?>" class="formButton" /></td>
        </tr>
    </table>
</div>
<table width="100%" class="outer" cellspacing="1">
	<thead>
	<tr align="center">
		<th width="20"><input type="checkbox" name="checkall" id="checkall" onclick='$("#frm-images").toggleCheckboxes(":not(#checkall)");' /></th>
		<th width="30"><a href="./images.php?pag=<?php echo $page; ?>&amp;limit=<?php echo $limit; ?>&amp;owner=<?php echo $owner; ?>&amp;search=<?php echo $search; ?>&amp;sort=id_image&amp;mode=<?php if($sort=='id_image'): ?><?php if($mode==1): ?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>"><?php _e('ID','galleries'); ?></a></th>
		<th>&nbsp;</th>
		<th><a href="./images.php?pag=<?php echo $page; ?>&amp;limit=<?php echo $limit; ?>&amp;owner=<?php echo $owner; ?>&amp;search=<?php echo $search; ?>&amp;sort=title&amp;mode=<?php if($sort=='title'): ?><?php if($mode==1): ?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>"><?php _e('Title','galleries'); ?></a></th>
		<th><a href="./images.php?pag=<?php echo $page; ?>&amp;limit=<?php echo $limit; ?>&amp;owner=<?php echo $owner; ?>&amp;search=<?php echo $search; ?>&amp;sort=public&amp;mode=<?php if($sort=='public'): ?><?php if($mode==1): ?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>"><?php _e('Visibility','galleries'); ?></a></th>
		<th><a href="./images.php?pag=<?php echo $page; ?>&amp;limit=<?php echo $limit; ?>&amp;owner=<?php echo $owner; ?>&amp;search=<?php echo $search; ?>&amp;sort=created&amp;mode=<?php if($sort=='created'): ?><?php if($mode==1): ?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>"><?php _e('Creation','galleries'); ?></a></th>
		<th><a href="./images.php?pag=<?php echo $page; ?>&amp;limit=<?php echo $limit; ?>&amp;owner=<?php echo $owner; ?>&amp;search=<?php echo $search; ?>&amp;sort=owner&amp;mode=<?php if($sort=='owner'): ?><?php if($mode==1): ?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>"><?php _e('Owner','galleries'); ?></a></th>
	</tr>
	</thead>
	
	<tfoot>
	<tr align="center">
        <th width="20"><input type="checkbox" name="checkall2" id="checkall2" onclick='$("#frm-images").toggleCheckboxes(":not(#checkall2)");' /></th>
        <th width="30"><a href="./images.php?pag=<?php echo $page; ?>&amp;limit=<?php echo $limit; ?>&amp;owner=<?php echo $owner; ?>&amp;search=<?php echo $search; ?>&amp;sort=id_image&amp;mode=<?php if($sort=='id_image'): ?><?php if($mode==1): ?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>"><?php _e('ID','galleries'); ?></a></th>
        <th>&nbsp;</th>
        <th><a href="./images.php?pag=<?php echo $page; ?>&amp;limit=<?php echo $limit; ?>&amp;owner=<?php echo $owner; ?>&amp;search=<?php echo $search; ?>&amp;sort=title&amp;mode=<?php if($sort=='title'): ?><?php if($mode==1): ?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>"><?php _e('Title','galleries'); ?></a></th>
        <th><a href="./images.php?pag=<?php echo $page; ?>&amp;limit=<?php echo $limit; ?>&amp;owner=<?php echo $owner; ?>&amp;search=<?php echo $search; ?>&amp;sort=public&amp;mode=<?php if($sort=='public'): ?><?php if($mode==1): ?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>"><?php _e('Visibility','galleries'); ?></a></th>
        <th><a href="./images.php?pag=<?php echo $page; ?>&amp;limit=<?php echo $limit; ?>&amp;owner=<?php echo $owner; ?>&amp;search=<?php echo $search; ?>&amp;sort=created&amp;mode=<?php if($sort=='created'): ?><?php if($mode==1): ?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>"><?php _e('Creation','galleries'); ?></a></th>
        <th><a href="./images.php?pag=<?php echo $page; ?>&amp;limit=<?php echo $limit; ?>&amp;owner=<?php echo $owner; ?>&amp;search=<?php echo $search; ?>&amp;sort=owner&amp;mode=<?php if($sort=='owner'): ?><?php if($mode==1): ?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>"><?php _e('Owner','galleries'); ?></a></th>
    </tr>
	</tfoot>
	
	<tbody>
	<?php if(empty($images)): ?>
	<tr class="even"><td colspan="7" align="center"><?php _e('There are not images created yet!','galleries'); ?></td></tr>
	<?php endif; ?>
	<?php foreach($images as $img): ?>
	<tr class="<?php echo tpl_cycle('even,odd'); ?>" align="center" valign="top">
		<td><input type="checkbox" name="ids[]" value="<?php echo $img['id']; ?>" id="item-<?php echo $img['id']; ?>" /></td>
		<td><strong><?php echo $img['id']; ?></strong></td>
		<td width="50" class="listimg"><a href="<?php echo $img['link']; ?>"><img src="<?php echo $img['image']; ?>" alt="" style="width: 50px;" /></a></td>
		<td align="left"><a href="<{$img.link}>"><strong><?php echo $img['title']; ?></strong></a>
			<?php if($img['desc']!=''): ?><span class="imgDesc"><br /><?php echo $img['desc']; ?></span><?php endif; ?>
			<span class="rmc_options">
				<a href="./images.php?op=edit&amp;id=<?php echo $img['id']; ?>&amp;pag=<?php echo $page; ?>&amp;limit=<?php echo $limit; ?>&amp;search=<?php echo $search; ?>&amp;owner=<?php echo $owner; ?>&amp;sort=<?php echo $sort; ?>&amp;mode=<?php echo $mode; ?>"><?php _e('Edit','galleries'); ?></a> | 
                <a href="#" class="regenerate"><?php _e('Re do Thumbnail','galleries'); ?></a> |
                <a href="javascript:;" class="gs_delete_option" id="delete-<?php echo $img['id']; ?>"><?php _e('Delete','galleries'); ?></a>
			</span>
		</td>
		<td><?php if($img['public']==2): ?><img src="<?php echo XOOPS_URL; ?>/modules/galleries/images/public.png" title="<?php _e('Public','galleries'); ?>" /><?php elseif($img['public']==1): ?><img src="<?php echo XOOPS_URL; ?>/modules/galleries/images/faccess.png" title="<?php _e('Public for friends','galleries'); ?>" /><?php else: ?><img src="<?php echo XOOPS_URL; ?>/modules/galleries/images/private.png" title="<?php _e('Private','galleries'); ?>" /><?php endif; ?></td>
		<td nowrap="nowrap"><?php echo $img['created']; ?></td>
		<td><?php echo $img['owner']; ?></td>
	</tr>
	<?php endforeach; ?>
	</tbody>
</table>
<div class="gs_options">
    <?php $nav->display(false); ?>
    <select name="opb" id="select-op-bottom">
        <option value=""><?php _e('Bulk actions...','galleries'); ?></option>
        <option value="redo" class="redo-thumbs"><?php _e('Generate Thumbnails','galleries'); ?></option>
        <option value="public"><?php _e('Set as public','galleries'); ?></option>
        <option value="private"><?php _e('Set as private','galleries'); ?></option>
        <option value="privatef"><?php _e('Set as public for friends','galleries'); ?></option>
        <option value="delete"><?php _e('Delete','galleries'); ?></option>
    </select>
    <input type="button" id="op-bottom" value="<?php _e('Apply','galleries'); ?>" /> &nbsp; &nbsp;
    <?php echo $nav->get_showing(); ?>
    <div class="gs_visibility">
        <img src="<?php echo XOOPS_URL; ?>/modules/galleries/images/public.png" align="absmiddle" /><?php _e('Public','galleries'); ?> |
        <img src="<?php echo XOOPS_URL; ?>/modules/galleries/images/private.png" align="absmiddle" /><?php _e('Private','galleries'); ?> |
        <img src="<?php echo XOOPS_URL; ?>/modules/galleries/images/faccess.png" align="absmiddle" /><?php _e('Public for friends','galleries'); ?> 
    </div>
</div>
<input type="hidden" name="pag" value="<?php echo $page; ?>" />
<input type="hidden" name="limit" value="<?php echo $limit; ?>" />
<input type="hidden" name="search" value="<?php echo $search; ?>" />
<input type="hidden" name="owner" value="<?php echo $owner; ?>" />
<input type="hidden" name="sort" value="<?php echo $sort; ?>" />
<input type="hidden" name="mode" value="<?php echo $mode; ?>" />
<?php echo $xoopsSecurity->getTokenHTML(); ?>
</form>
