<h1 class="rmc_titles"><span style="background-position: left -32px;">&nbsp;</span><?php _e('Albums Management','galleries'); ?></h1>

<form name="frmSets" id="frm-sets" method="POST" action="sets.php">
<div class="gs_options">
    <?php $nav->display(false); ?>
	<select name="op" id="select-op-top">
		<option value="" selected="selected"><?php _e('Bulk actions...','galleries'); ?></option>
		<option value="public"><?php _e('Set as public','galleries'); ?></option>
		<option value="private"><?php _e('Set as private','galleries'); ?></option>
		<option value="privatef"><?php _e('Public for friends','galleries'); ?></option>
		<option value="delete"><?php _e('Delete','galleries'); ?></option>
	</select>
	<input type="button" id="op-top" value="<?php _e('Apply','galleries'); ?>" />
    &nbsp; &nbsp;
    <input type="hidden" name="sort" value="<?php echo $sort; ?>" />
    <input type="hidden" name="mode" value="<?php echo $mode; ?>" /> &nbsp;
    <?php _e('Search:','galleries'); ?>
    <input type="text" name="search" value="<?php echo $search ?>" size="20" />
    <input type="submit" class="formButton" value="<?php _e('Search','galleries'); ?>" />
    &nbsp; &nbsp;
    <a href="sets.php"><?php _e('Show all','galleries'); ?></a>
</div>
<table class="outer" cellspacing="0" width="100%">
	<thead>
	<tr class="head" align="center">
		<th width="20">
			<input type="checkbox" name="checkall" id="checkall" onclick='$("#frm-sets").toggleCheckboxes(":not(#checkall)");' />
		</th>
		<th width="30"><a href="./sets.php?pag=<?php echo $page; ?>&amp;limit=<?php echo $limit; ?>&amp;search=<?php echo $search; ?>&amp;sort=id_set&amp;mode=<?php if($sort=='id_set'): ?><?php if($mode==1):?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>"><?php _e('ID','galleries'); ?></a></th>
		<th><a href="./sets.php?pag=<?php echo $page; ?>&amp;limit=<?php echo $limit; ?>&amp;search=<?php echo $search; ?>&amp;sort=title&amp;mode=<?php if($sort=='id_set'): ?><?php if($mode==1):?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>"><?php _e('Title','galleries'); ?></a></th>
		<th><a href="./sets.php?pag=<?php echo $page; ?>&amp;limit=<?php echo $limit; ?>&amp;search=<?php echo $search; ?>&amp;sort=owner&amp;mode=<?php if($sort=='id_set'): ?><?php if($mode==1):?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>"><?php _e('Owner','galleries'); ?></a></th>
		<th><a href="./sets.php?pag=<?php echo $page; ?>&amp;limit=<?php echo $limit; ?>&amp;search=<?php echo $search; ?>&amp;sort=public&amp;mode=<?php if($sort=='id_set'): ?><?php if($mode==1):?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>"><?php _e('Visibility','galleries'); ?></a></th>
		<th><a href="./sets.php?pag=<?php echo $page; ?>&amp;limit=<?php echo $limit; ?>&amp;search=<?php echo $search; ?>&amp;sort=date&amp;mode=<?php if($sort=='id_set'): ?><?php if($mode==1):?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>"><?php _e('Date','galleries'); ?></a></th>
		<th><a href="./sets.php?pag=<?php echo $page; ?>&amp;limit=<?php echo $limit; ?>&amp;search=<?php echo $search; ?>&amp;sort=pics&amp;mode=<?php if($sort=='id_set'): ?><?php if($mode==1):?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>"><?php _e('Pictures','galleries'); ?></a></th>
	</tr>
	</thead>
	<tfoot>
	<tr class="head" align="center">
		<th width="20">
			<input type="checkbox" name="checkall2" id="checkall2" onclick='$("#frm-sets").toggleCheckboxes(":not(#checkall2)");' />
		</th>
		<th width="30"><a href="./sets.php?pag=<?php echo $page; ?>&amp;limit=<?php echo $limit; ?>&amp;search=<?php echo $search; ?>&amp;sort=id_set&amp;mode=<?php if($sort=='id_set'): ?><?php if($mode==1):?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>"><?php _e('ID','galleries'); ?></a></th>
		<th><a href="./sets.php?pag=<?php echo $page; ?>&amp;limit=<?php echo $limit; ?>&amp;search=<?php echo $search; ?>&amp;sort=title&amp;mode=<?php if($sort=='id_set'): ?><?php if($mode==1):?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>"><?php _e('Title','galleries'); ?></a></th>
		<th><a href="./sets.php?pag=<?php echo $page; ?>&amp;limit=<?php echo $limit; ?>&amp;search=<?php echo $search; ?>&amp;sort=owner&amp;mode=<?php if($sort=='id_set'): ?><?php if($mode==1):?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>"><?php _e('Owner','galleries'); ?></a></th>
		<th><a href="./sets.php?pag=<?php echo $page; ?>&amp;limit=<?php echo $limit; ?>&amp;search=<?php echo $search; ?>&amp;sort=public&amp;mode=<?php if($sort=='id_set'): ?><?php if($mode==1):?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>"><?php _e('Visibility','galleries'); ?></a></th>
		<th><a href="./sets.php?pag=<?php echo $page; ?>&amp;limit=<?php echo $limit; ?>&amp;search=<?php echo $search; ?>&amp;sort=date&amp;mode=<?php if($sort=='id_set'): ?><?php if($mode==1):?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>"><?php _e('Date','galleries'); ?></a></th>
		<th><a href="./sets.php?pag=<?php echo $page; ?>&amp;limit=<?php echo $limit; ?>&amp;search=<?php echo $search; ?>&amp;sort=pics&amp;mode=<?php if($sort=='id_set'): ?><?php if($mode==1):?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>"><?php _e('Pictures','galleries'); ?></a></th>
	</tr>
	</tfoot>
	<tbody>
	<?php if(empty($sets)): ?>
		<tr class="even"><td colspan="7" align="center"><?php _e('There are not albums yet!','galleries'); ?></td></tr>
	<?php endif; ?>
	<?php foreach($sets as $set): ?>
	<tr class="<?php echo tpl_cycle('even,odd'); ?>" align="center" valign="top">
		<td><input type="checkbox" name="ids[]" id="item-<?php echo $set['id']; ?>" value="<?php echo $set['id']; ?>" /></td>
		<td ><strong><?php echo $set['id']; ?></strong></td>
		<td align="left">
			<a href="<?php echo $set['url']; ?>"><?php echo $set['title']; ?></a>
			<span class="rmc_options">
			<a href="sets.php?op=edit&amp;id=<?php echo $set['id']; ?>&amp;<?php echo $query; ?>"><?php _e('Edit','galleries'); ?></a> | 
			<a href="javascript:;" class="gs_delete_option" id="delete-<?php echo $set['id']; ?>"><?php _e('Delete','galleries'); ?></a>
			</span>
		</td>
		<td><?php echo $set['owner']; ?></td>
		<td><?php if($set['public']==2): ?><img src="<?php echo XOOPS_URL; ?>/modules/galleries/images/public.png" title="<?php _e('Publico','galleries'); ?>" /><?php elseif($set['public']==1): ?><img src="<?php echo XOOPS_URL; ?>/modules/galleries/images/faccess.png" title="<?php _e('Public for friends','galleries'); ?>" /><?php else: ?><img src="<?php echo XOOPS_URL; ?>/modules/galleries/images/private.png" title="<?php _e('Private','galleries'); ?>" /><?php endif; ?></td>
		<td><?php echo $set['date']; ?></td>
		<td><?php echo $set['pics']; ?></td>
	</tr>
	<?php endforeach; ?>
	</tbody>
</table>
<div class="gs_options">
    <?php $nav->display(false); ?>
	<select name="opb" id="select-op-bottom">
		<option value="" selected="selected"><?php _e('Bulk actions...','galleries'); ?></option>
        <option value="public"><?php _e('Set as public','galleries'); ?></option>
        <option value="private"><?php _e('Set as private','galleries'); ?></option>
        <option value="privatef"><?php _e('Public for friends','galleries'); ?></option>
        <option value="delete"><?php _e('Delete','galleries'); ?></option>
	</select>
	<input type="button" id="op-bottom" value="<?php _e('Apply','galleries'); ?>" />
</div>
<?php echo $xoopsSecurity->getTokenHTML(); ?>
<input type="hidden" name="pag" value="<?php echo $page ?>" />
</form>
