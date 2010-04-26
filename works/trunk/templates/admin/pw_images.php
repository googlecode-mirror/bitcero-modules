<h1 class="rmc_titles"><span style="background-position: -64px -32px;">&nbsp;</span><?php echo $work->title(); ?> : <?php _e('Work Images','admin_works'); ?></h1>

<div id="pw-right-table">
	<form name="frmImgs" id="frm-images" method="POST" action="images.php">
	<div class="pw_options">
		<?php $nav->display(false); ?>
	    <select name="op" id="bulk-top">
	        <option value=""><?php _e('Bulk actions...','admin_works'); ?></option>
	        <option value="delete"><?php _e('Delete','admin_works'); ?></option>
	    </select>
	    <input type="button" id="the-op-top" value="<?php _e('Apply','admin_works'); ?>" onclick="before_submit('frm-categos');" />
	</div>
	<table class="outer" width="100%" cellspacing="0" />
	    <thead>
		<tr class="head" align="center">
			<th width="20"><input type="checkbox" id="checkall" onclick='$("#frm-images").toggleCheckboxes(":not(#checkall)");' /></th>
			<th width="20"><?php _e('ID','admin_works'); ?></th>
			<th width="50"><?php _e('Image','admin_works'); ?></th>
			<th align="left"><?php _e('Title','admin_works'); ?></th>
	        <th align="left"><?php _e('Description','admin_works'); ?></th>
		</tr>
	    </thead>
	    <tfoot>
	    <tr class="head" align="center">
	        <th width="20"><input type="checkbox" id="checkall" onclick='$("#frm-images").toggleCheckboxes(":not(#checkall)");' /></th>
	        <th width="20"><?php _e('ID','admin_works'); ?></th>
	        <th width="50"><?php _e('Image','admin_works'); ?></th>
	        <th align="left"><?php _e('Title','admin_works'); ?></th>
	        <th align="left"><?php _e('Description','admin_works'); ?></th>
	    </tr>
	    </tfoot>
	    <tbody>
	    <?php if(empty($images)): ?>
	    <tr class="head" align="center">
	        <td colspan="5"><?php _e('There are not images for this work yet!','admin_works'); ?></td>
	    </tr>
	    <?php endif; ?>
		<?php foreach($images as $img): ?>
		<tr class="<?php echo tpl_cycle('even,odd'); ?>" align="center" valign="top">
			<td><input type="checkbox" name="ids[]" id="item-<?php echo $img['id']; ?>" value="<?php echo $img['id']; ?>" /></td>
			<td><strong><?php echo $img['id']; ?></strong></td>
			<td><img src="<?php echo XOOPS_URL; ?>/uploads/works/ths/<?php echo $img['image']; ?>" style="width: 50px;" /></td>
			<td align="left">
				<strong><?php echo $img['title']; ?></strong>
				<span class="rmc_options">
					<a href="./images.php?op=edit&amp;id=<?php echo $img['id']; ?>&amp;work=<?php echo $work->id(); ?>&amp;page=<?php echo $page; ?>"><?php _e('Edit', 'admin_works'); ?></a> |
					<a href="javascript:;" onclick="select_option(<?php echo $img['id']; ?>,'delete','frm-images');"><?php _e('Delete', 'admin_works'); ?></a>
				</span>
			</td>
			<td align="left"><?php echo $img['desc']; ?></td>
		</tr>
		<?php endforeach; ?>
	    </tbody>
	</table>
	<div class="pw_options">
		<?php $nav->display(false); ?>
	    <select name="opb" id="bulk-bottom">
	        <option value=""><?php _e('Bulk actions...','admin_works'); ?></option>
	        <option value="delete"><?php _e('Delete','admin_works'); ?></option>
	    </select>
	    <input type="button" id="the-op-bottom" value="<?php _e('Apply','admin_works'); ?>" onclick="before_submit('frm-categos');" />
	</div>
	<input type="hidden" name="work" value="<?php echo $work->id(); ?>" />
	<input type="hidden" name="pag" value="<?php echo $page; ?>" />
	<?php echo $xoopsSecurity->getTokenHTML(); ?>
	</form>
</div>

<div id="pw-left-form">
	<h3><?php _e('Add image', 'admin_works'); ?></h3>
	<form name="frm_images" id="frm-images" method="post" action="images.php" enctype="multipart/form-data">
		<label for="title"><?php _e('Image title','admin_works'); ?></label>
		<input type="text" size="30" name="title" id="title" value="" class="required" />
		<label for="image-file"><?php _e('Image file','admin_works'); ?></label>
		<input type="file" name="image" id="image-file" />
		<label for="description"><?php _e('Image description','admin_works'); ?></label>
		<textarea name="desc" id="description"></textarea>
		<input type="hidden" name="op" value="save" />
		<input type="hidden" name="work" value="<?php echo $work->id(); ?>" />
		<input type="hidden" name="page" value="<?php echo $page; ?>" />
		<?php echo $form_fields; ?>
		<input type="submit" value="<?php _e('Add Image','admin_works'); ?>" />
		<?php echo $xoopsSecurity->getTokenHTML(); ?>
	</form>
</div>
