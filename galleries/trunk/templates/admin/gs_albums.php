<h1 class="rmc_titles"><span style="background-position: left -32px;">&nbsp;</span><?php _e('Albums Management','admin_galleries'); ?></h1>
<form name="frmnav" method="POST" action="sets.php">
<table class="outer" cellspacing="1" width="100%">
	<tr class="even">
		<td align="center">
			<input type="text" name="limit" value="<?php echo $limit; ?>" size="5" />
			<input type="submit" class="formButton" value="<?php _e('Actualizar','admin_galleries'); ?>" />
			<input type="hidden" name="sort" value="<?php echo $sort; ?>" />
			<input type="hidden" name="mode" value="<?php echo $mode; ?>" /> &nbsp;
			<?php _e('Search:','admin_gallerires'); ?>
			<input type="text" name="search" value="<?php echo $search ?>" size="20" />
			<input type="submit" class="formButton" value="<?php _e('Buscar','admin_galleries'); ?>" />
			&nbsp; &nbsp;
			<a href="sets.php"><?php _e('Show all','admin_galleries'); ?></a>
		</td>
	</tr>
</table>
</form>

<form name="frmSets" id="frm-sets" method="POST" action="sets.php">
<div class="gs_options">
    <?php $nav->display(false); ?>
	<select name="op" id="select-op-top">
		<option value="" selected="selected">Bulk actions...</option>
		<option value="public">Set as public</option>
		<option value="private">Set as private</option>
		<option value="privatef">Public for friends</option>
		<option value="delete">Delete</option>
	</select>
	<input type="button" id="op-top" value="<?php _e('Apply','admin_galleries'); ?>" />
</div>
<table class="outer" cellspacing="0" width="100%">
	<thead>
	<tr class="head" align="center">
		<th width="20">
			<input type="checkbox" name="checkall" id="checkall" onclick='$("#frm-sets").toggleCheckboxes(":not(#checkall)");' />
		</th>
		<th width="30"><a href="./sets.php?pag=<?php echo $page; ?>&amp;limit=<?php echo $limit; ?>&amp;search=<?php echo $search; ?>&amp;sort=id_set&amp;mode=<?php if($sort=='id_set'): ?><?php if($mode==1):?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>"><?php _e('ID','admin_galleries'); ?></a></th>
		<th><a href="./sets.php?pag=<?php echo $page; ?>&amp;limit=<?php echo $limit; ?>&amp;search=<?php echo $search; ?>&amp;sort=title&amp;mode=<?php if($sort=='id_set'): ?><?php if($mode==1):?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>"><?php _e('Title','admin_galleries'); ?></a></th>
		<th><a href="./sets.php?pag=<?php echo $page; ?>&amp;limit=<?php echo $limit; ?>&amp;search=<?php echo $search; ?>&amp;sort=owner&amp;mode=<?php if($sort=='id_set'): ?><?php if($mode==1):?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>"><?php _e('Owner','admin_gallerires'); ?></a></th>
		<th><a href="./sets.php?pag=<?php echo $page; ?>&amp;limit=<?php echo $limit; ?>&amp;search=<?php echo $search; ?>&amp;sort=public&amp;mode=<?php if($sort=='id_set'): ?><?php if($mode==1):?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>"><?php _e('Visibility','admin_gallerires'); ?></a></th>
		<th><a href="./sets.php?pag=<?php echo $page; ?>&amp;limit=<?php echo $limit; ?>&amp;search=<?php echo $search; ?>&amp;sort=date&amp;mode=<?php if($sort=='id_set'): ?><?php if($mode==1):?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>"><?php _e('Date','admin_gallerires'); ?></a></th>
		<th><a href="./sets.php?pag=<?php echo $page; ?>&amp;limit=<?php echo $limit; ?>&amp;search=<?php echo $search; ?>&amp;sort=pics&amp;mode=<?php if($sort=='id_set'): ?><?php if($mode==1):?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>"><?php _e('Pictures','admin_gallerires'); ?></a></th>
	</tr>
	</thead>
	<tfoot>
	<tr class="head" align="center">
		<th width="20">
			<input type="checkbox" name="checkall2" id="checkall2" onclick='$("#frm-sets").toggleCheckboxes(":not(#checkall2)");' />
		</th>
		<th width="30"><a href="./sets.php?pag=<?php echo $page; ?>&amp;limit=<?php echo $limit; ?>&amp;search=<?php echo $search; ?>&amp;sort=id_set&amp;mode=<?php if($sort=='id_set'): ?><?php if($mode==1):?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>"><?php _e('ID','admin_galleries'); ?></a></th>
		<th><a href="./sets.php?pag=<?php echo $page; ?>&amp;limit=<?php echo $limit; ?>&amp;search=<?php echo $search; ?>&amp;sort=title&amp;mode=<?php if($sort=='id_set'): ?><?php if($mode==1):?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>"><?php _e('Title','admin_galleries'); ?></a></th>
		<th><a href="./sets.php?pag=<?php echo $page; ?>&amp;limit=<?php echo $limit; ?>&amp;search=<?php echo $search; ?>&amp;sort=owner&amp;mode=<?php if($sort=='id_set'): ?><?php if($mode==1):?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>"><?php _e('Owner','admin_gallerires'); ?></a></th>
		<th><a href="./sets.php?pag=<?php echo $page; ?>&amp;limit=<?php echo $limit; ?>&amp;search=<?php echo $search; ?>&amp;sort=public&amp;mode=<?php if($sort=='id_set'): ?><?php if($mode==1):?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>"><?php _e('Visibility','admin_gallerires'); ?></a></th>
		<th><a href="./sets.php?pag=<?php echo $page; ?>&amp;limit=<?php echo $limit; ?>&amp;search=<?php echo $search; ?>&amp;sort=date&amp;mode=<?php if($sort=='id_set'): ?><?php if($mode==1):?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>"><?php _e('Date','admin_gallerires'); ?></a></th>
		<th><a href="./sets.php?pag=<?php echo $page; ?>&amp;limit=<?php echo $limit; ?>&amp;search=<?php echo $search; ?>&amp;sort=pics&amp;mode=<?php if($sort=='id_set'): ?><?php if($mode==1):?>0<?php else: ?>1<?php endif; ?><?php else: ?>0<?php endif; ?>"><?php _e('Pictures','admin_gallerires'); ?></a></th>
	</tr>
	</tfoot>
	<tbody>
	<?php if(empty($sets)): ?>
		<tr class="even"><td colspan="7" align="center"><?php _e('There are not albums yet!','admin_gallerires'); ?></td></tr>
	<?php endif; ?>
	<?php foreach($sets as $set): ?>
	<tr class="<?php echo tpl_cycle('even,odd'); ?>" align="center" valign="top">
		<td><input type="checkbox" name="ids[]" id="set-<?php echo $set['id']; ?>" value="<?php echo $set['id']; ?>" /></td>
		<td ><strong><?php echo $set['id']; ?></strong></td>
		<td align="left">
			<a href="<?php echo $set['url']; ?>"><?php echo $set['title']; ?></a>
			<span class="rmc_options">
			<a href="sets.php?op=edit&amp;id=<?php echo $set['id']; ?>&amp;<?php echo $query; ?>"><?php _e('Edit','admin_galleries'); ?></a> | 
			<a href="javascript:;" class="gs_delete_option" id="delete-<?php echo $set['id']; ?>"><?php _e('Delete','admin_galleries'); ?></a>
			</span>
		</td>
		<td><?php echo $set['owner']; ?></td>
		<td><?php if($set['public']==2): ?><img src="<?php echo XOOPS_URL; ?>/modules/galleries/images/public.png" title="<?php _e('Publico','admin_galleries'); ?>" /><?php elseif($set['public']==1): ?><img src="<?php echo XOOPS_URL; ?>/modules/galleries/images/faccess.png" title="<?php _e('Public for friends','admin_galleries'); ?>" /><?php else: ?><img src="<?php echo XOOPS_URL; ?>/modules/galleries/images/private.png" title="<?php _e('Private','admin_galleries'); ?>" /><?php endif; ?></td>
		<td><?php echo $set['date']; ?></td>
		<td><?php echo $set['pics']; ?></td>
	</tr>
	<?php endforeach; ?>
	</tbody>
</table>
<div class="gs_options">
    <?php $nav->display(false); ?>
	<select name="opb" id="select-op-bottom">
		<option value="" selected="selected">Bulk actions...</option>
		<option value="public">Set as public</option>
		<option value="private">Set as private</option>
		<option value="privatef">Public for friends</option>
		<option value="delete">Delete</option>
	</select>
	<input type="button" id="op-bottom" value="<?php _e('Apply','admin_galleries'); ?>" />
</div>
<?php echo $xoopsSecurity->getTokenHTML(); ?>
<input type="hidden" name="sort" value="<?php echo $sort; ?>" />
<input type="hidden" name="mode" value="<?php echo $mode ?>" />
<input type="hidden" name="pag" value="<?php echo $page ?>" />
<input type="hidden" name="limit" value="<?php echo $limit ?>" />
<input type="hidden" name="search" value="<?php echo $search ?>" />
</form>
