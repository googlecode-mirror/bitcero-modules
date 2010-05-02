<h1 class="rmc_titles"><span style="background-position: -32px 0;">&nbsp;</span><?php _e('Categories','qpages'); ?></h1>

<div id="qp-two-cols">
	<div id="qp-col-right">
	
		<form name="frm_categos" id="frm-categos" method="post" action="cats.php">
		<div class="qp_options">
			<select name="op">
				<option value="">Bulk actions</option>
				<option value="delete"><?php _e('Delete','qpages'); ?></option>
			</select>
			<input type="button" value="<?php _e('Apply','qpages'); ?>" />
		</div>
		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="outer">
			<thead>
			<tr class="head" align="center">
				<th width="20"><input<?php if(empty($categories)): ?> disabled="disabled"<?php endif; ?> type="checkbox" id="checkall" onclick='$("#frm-categos").toggleCheckboxes(":not(#checkall)");' /></th>
    			<th width="30"><?php _e('ID','qpages'); ?></th>
    			<th align="left"><?php _e('Name','qpages'); ?></th>
    			<th align="left"><?php _e('Description','qpages'); ?></th>
    			<th><?php _e('Pages','qpages'); ?></th>
  			</tr>
  			</thead>
  			<tfoot>
			<tr class="head" align="center">
				<th width="20"><input<?php if(empty($categories)): ?> disabled="disabled"<?php endif; ?> type="checkbox" id="checkall2" onclick='$("#frm-categos").toggleCheckboxes(":not(#checkall2)");' /></th>
    			<th width="30"><?php _e('ID','qpages'); ?></th>
    			<th align="left"><?php _e('Name','qpages'); ?></th>
    			<th align="left"><?php _e('Description','qpages'); ?></th>
    			<th><?php _e('Pages','qpages'); ?></th>
  			</tr>
  			</tfoot>
  			<tbody>
  			<?php if(empty($categories)): ?>
  			<tr class="even">
  				<td align="center" colspan="4"><?php _e('There are not pages registered yet!','qpages'); ?></td>
  			</tr>
  			<?php endif; ?>
			<?php foreach($categories as $cat): ?>
			<tr class="<?php echo tpl_cycle("even,odd"); ?>" valign="top">
				<td><input type="checkbox" name="ids[]" id="item-<?php echo $cat['id_cat']; ?>" value="<?php echo $cat['id_cat']; ?>" /></td>
    			<td align="center"><strong><?php echo $cat['id_cat']; ?></strong></td>
    			<td>
    				<strong><?php echo $cat['nombre']; ?></strong>
    				<span class="rmc_options">
    					<a href="?op=edit&amp;id=<?php echo $cat['id_cat']; ?>"><?php _e('Edit','qpages'); ?></a> |
    					<a href="?op=delete&amp;id=<{$cat.id_cat}>"><{$lang_delete}></a>
    				</span>
    			</td>
    			<td align="left">
    				<?php echo $cat['descripcion']; ?>
    			</td>
    			<td align="center"><?php echo $cat['posts']; ?></td>
			</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		<div class="qp_options">
			<select name="opb">
				<option value="">Bulk actions</option>
				<option value="delete"><?php _e('Delete','qpages'); ?></option>
			</select>
			<input type="button" value="<?php _e('Apply','qpages'); ?>" />
		</div>
		</form>
	
	</div>
	
	<div id="qp-col-left">
		<h3><?php _e('Add Category','qpages'); ?></h3>
		<form name="frm_new_cat" id="frm-cat" method="post" action="cats.php">
			<label for="cat-name"><?php _e('Category name','qpages'); ?></label>
			<input type="text" size="30" name="nombre" id="cat-name" value="" />
			<label for="cat-desc"><?php _e('Category description','qpages'); ?></label>
			<textarea name="descripcion" id="cat-desc"></textarea>
			<label for="cat-parent"><?php _e('Parent category','qpages'); ?></label>
			<select name="parent">
				<option value="0" selected="selected"><?php _e('Select category...','qpages'); ?></option>
				<?php foreach($categories as $cat): ?>
				<option value="<?php echo $cat['id_cat']; ?>"><?php echo $cat['nombre']; ?></option>
				<?php endforeach; ?>
			</select>
			<input type="submit" value="<?php _e('Add Category','qpages'); ?>" />
			<input type="hidden" name="op" value="save" />
		</form>
	</div>
	
</div>
