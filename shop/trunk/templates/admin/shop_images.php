<h1 class="rmc_titles"><span style="background-position: -64px -32px;">&nbsp;</span><?php echo $product->getVar('name'); ?> : <?php _e('Product Images','shop'); ?></h1>

<div id="shop-right-table">
	<form name="frmImgs" id="frm-images" method="POST" action="products.php">
	<div class="pw_options">
	    <select name="action" id="bulk-top">
	        <option value=""><?php _e('Bulk actions...','shop'); ?></option>
	        <option value="deleteimages"><?php _e('Delete','shop'); ?></option>
	    </select>
	    <input type="button" id="the-op-top" value="<?php _e('Apply','shop'); ?>" onclick="before_submit('frm-images');" />
	</div>
	<table class="outer" width="100%" cellspacing="0" />
	    <thead>
		<tr class="head" align="center">
			<th width="20"><input type="checkbox" id="checkall" onclick='$("#frm-images").toggleCheckboxes(":not(#checkall)");' /></th>
			<th width="20"><?php _e('ID','shop'); ?></th>
			<th width="50"><?php _e('Image','shop'); ?></th>
			<th align="left"><?php _e('Title','shop'); ?></th>
	        <th align="left"><?php _e('Description','shop'); ?></th>
		</tr>
	    </thead>
	    <tfoot>
	    <tr class="head" align="center">
	        <th width="20"><input type="checkbox" id="checkall" onclick='$("#frm-images").toggleCheckboxes(":not(#checkall)");' /></th>
	        <th width="20"><?php _e('ID','shop'); ?></th>
	        <th width="50"><?php _e('Image','shop'); ?></th>
	        <th align="left"><?php _e('Title','shop'); ?></th>
	        <th align="left"><?php _e('Description','shop'); ?></th>
	    </tr>
	    </tfoot>
	    <tbody>
	    <?php if(empty($images)): ?>
	    <tr class="head" align="center">
	        <td colspan="5"><?php _e('There are not images for this product yet!','shop'); ?></td>
	    </tr>
	    <?php endif; ?>
		<?php foreach($images as $img): ?>
		<tr class="<?php echo tpl_cycle('even,odd'); ?>" align="center" valign="top">
			<td><input type="checkbox" name="ids[]" id="item-<?php echo $img['id']; ?>" value="<?php echo $img['id']; ?>" /></td>
			<td><strong><?php echo $img['id']; ?></strong></td>
			<td><img src="<?php echo XOOPS_URL; ?>/uploads/minishop/ths/<?php echo $img['file']; ?>" style="width: 50px;" /></td>
			<td align="left">
				<strong><?php echo $img['title']; ?></strong>
				<span class="rmc_options">
					<a href="products.php?action=editimage&amp;idimg=<?php echo $img['id']; ?>&amp;id=<?php echo $product->id(); ?>&amp;page=<?php echo $page; ?>&amp;bname=<?php echo $bname; ?>"><?php _e('Edit', 'shop'); ?></a> |
					<a href="javascript:;" onclick="select_option(<?php echo $img['id']; ?>,'deleteimages','frm-images');"><?php _e('Delete', 'shop'); ?></a>
				</span>
			</td>
			<td align="left"><?php echo $img['desc']; ?></td>
		</tr>
		<?php endforeach; ?>
	    </tbody>
	</table>
	<div class="pw_options">
	    <select name="actionb" id="bulk-bottom">
	        <option value=""><?php _e('Bulk actions...','shop'); ?></option>
	        <option value="deleteimages"><?php _e('Delete','shop'); ?></option>
	    </select>
	    <input type="button" id="the-op-bottom" value="<?php _e('Apply','shop'); ?>" onclick="before_submit('frm-categos');" />
	</div>
	<input type="hidden" name="id" value="<?php echo $product->id(); ?>" />
	<input type="hidden" name="page" value="<?php echo $page; ?>" />
    <input type="hidden" name="bname" value="<?php echo $bname; ?>" />
	<?php echo $xoopsSecurity->getTokenHTML(); ?>
	</form>
</div>

<div id="shop-left-form">
	<h3><?php _e('Add image', 'shop'); ?></h3>
	<form name="frm_images" id="frm-new-images" method="post" action="products.php" enctype="multipart/form-data">
		<label for="name"><?php _e('Image title','shop'); ?></label>
		<input type="text" size="30" name="title" id="name" value="" class="required" />
		<label for="image-file"><?php _e('Image file','shop'); ?></label>
		<input type="file" name="file" id="image-file" />
		<label for="description"><?php _e('Image description','shop'); ?></label>
		<textarea name="description" id="description"></textarea>
		<input type="hidden" name="action" value="saveimage" />
		<input type="hidden" name="id" value="<?php echo $product->id(); ?>" />
		<input type="hidden" name="page" value="<?php echo $page; ?>" />
        <input type="hidden" name="bname" value="<?php echo $bname; ?>" />
		<?php echo $form_fields; ?>
		<input type="submit" value="<?php _e('Add Image','shop'); ?>" />
		<?php echo $xoopsSecurity->getTokenHTML(); ?>
	</form>
</div>
