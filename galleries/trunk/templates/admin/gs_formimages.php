<h1 class="rmc_titles"><span style="background-position: left -32px;">&nbsp;</span><?php _e('Add batch images','admin_galleries'); ?></h1>

<form name="frmImgs" id="frmImgs" method="POST" action="images.php" enctype="multipart/form-data" onsubmit="validateTags(); return document.tagsvalidation;">
<table class="outer" cellspacing="1">
	<tr class="<?php echo tpl_cycle('even,odd'); ?>"  valign="top">
		<td colspan="2"><strong><?php _e('User:','admin_galleries'); ?></strong> <?php echo $users_field; ?></td>
		<td><strong><?php _e('Assign to albums:','admin_galleries'); ?></strong><br />
			<select name="albums[]" multiple="multiple" size="5" />
			<?php foreach($sets as $set): ?>
				<option value="<?php echo $set['id']; ?>"><?php echo $set['title']; ?></option>
			<?php endforeach; ?>		
			</select>
		</td>
	</tr>
	<tr  class="<?php echo tpl_cycle('even,odd'); ?>">
		<td colspan="3">	
			<strong><?php _e('Tags','admin_galleries'); ?></strong><br />
			<input type="text" name="tags" size="50" />
		</td>
	</tr>

	<tr class="head">
		<th width="20"></th>
		<th><?php _e('Title','admin_galleries'); ?></th>
		<th><?php _e('Image file','admin_galleries'); ?></th>
	</tr>
	<?php for($i=1;$i<=$num_fields;$i++): ?>
	<tr class="<?php echo tpl_cycle('even,odd'); ?>">
		<td align="center"><strong><?php echo $i; ?></strong></td>
		<td><input type="text" name="title[<?php echo $i; ?>]" value="" size="50" /></td>
		<td><input type="file" name="image[<?php echo $i; ?>]" id="image[<?php echo $i; ?>]" value="" size="45"  />
			<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $file_size; ?>" />
		</td>
	</tr>
	<?php endfor; ?>
	<tr class="foot">
		<td align="right">
			&nbsp;
		</td>
		<td colspan="2">
			<input type="submit" class="formButtonOk" value="<?php _e('Create Images','admin_galleries'); ?>" onclick="document.forms['frmImgs'].op.value='savebulk'"/>
			<input type="button" class="formButton" value="<?php _e('Cancel','admin_galleries'); ?>" onclick="window.location='images.php?<?php echo $ruta; ?>';"/>
		</td>
	</tr>
</table>
<input type="hidden" name="num" value="<?php echo $num_fields; ?>" />
<input type="hidden" name="op" id="op" />
<input type="hidden" name="page" value="<?php echo $page; ?>" />
<input type="hidden" name="limit" value="<?php echo $limit; ?>" />
<input type="hidden" name="search" value="<?php echo $search; ?>" />
<input type="hidden" name="owner" value="<?php echo $owner; ?>" />
<input type="hidden" name="sort" value="<?php echo $sort; ?>" />
<input type="hidden" name="mode" value="<?php echo $mode; ?>" />
</form>
