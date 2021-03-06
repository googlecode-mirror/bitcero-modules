<?php
ob_start();
?>
<table class="outer" cellspacing="0" cellpadding="4" width="99%" id="existing_meta">
<?php foreach($page_metas as $meta => $value): ?>
<tr valign="top" class="even">
	<td width="100">
		<input type="text" name="meta_name[]" value="<?php echo $meta; ?>" />
	</td>
	<td>
		<textarea name="meta_value[]" style="width: 99%; height: 70px;"><?php echo $value; ?></textarea>
	</td>
</tr>
<?php endforeach; ?>
</table>
<br />
<table cellspacing="0" cellpadding="4" width="99%" class="outer">
<tr><th colspan="2"><?php _e('Custom Fields','qpages'); ?></th></tr>
<tr valign="top" class="even">
	<td width="100">
		<?php if(count($available_metas)>0): ?>
		<select name="dmeta_name" id="dmeta_sel">
			<?php foreach($available_metas as $meta): ?>
			<option value="<?php echo $meta; ?>"><?php echo $meta; ?></option>
			<?php endforeach; ?>
		</select>
		<input type="text" name="dmeta_name" id="dmeta" value="" size="30" style="display: none;" />
		<br />		
		<a href="javascript:;" id="add_field_name"><?php _e('Add New','qpages'); ?></a>
		<?php else: ?>
		<input type="text" name="dmeta_name" id="dmeta" value="" size="30" />
		<?php endif; ?>
	</td>
	<td>
		<textarea name="dmeta_value" id="dvalue" style="width: 95%; height: 60px;"></textarea>
	</td>
</tr>
<tr class="foot">
	<td>&nbsp;</td>
	<td><input type="button" id="add_field" value="<?php _e('Add Field','qpages'); ?>" />
</tr>
</table>
<?php
$meta_data = ob_get_clean();
