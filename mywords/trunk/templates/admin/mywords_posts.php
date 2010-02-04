<h1 class="rmc_titles mw_titles"><span style="background-position: -32px 0;">&nbsp;</span><?php _e('Posts Management','admin_mywords'); ?></h1>
<form name="frmSearch" method="get" action="posts.php" style="margin: 0;">
<table width="100%" cellspacing="0" border="0">
	<tr class="even">
	<td align="left">
			<?php _e('Search:','admin_mywords'); ?>
			<input type="text" name="keyw" value="<?php echo $keyw!='' ? $keyw : ''; ?>" size="30" />
	</td>
	<td align="center">
		<?php _e('Results:','admin_mywords'); ?>
		<input type="text" size="5" name="limit" value="<?php echo $limit; ?>" />
		<input type="submit" name="sbt" value="<?php _e('Go!','admin_mywords'); ?>" class="formButton" />
	</td>
	<td align="center">
		<a href="posts.php?op=new"><?php _e('Add New','admin_mywords'); ?></a> |
		<a href="posts.php?limit=<?php echo $limit ?>"><?php _e('Show all','admin_mywords'); ?></a> <strong>(<?php echo ($pub_count+$draft_count+$pending_count); ?>)</strong> |
		<a href="posts.php?status=publish&amp;limit=<?php echo $limit ?>"><?php _e('Published', 'admin_mywords'); ?></a> <strong>(<?php echo $pub_count; ?>)</strong> |
		<a href="posts.php?status=draft&amp;limit=<?php echo $limit ?>"><?php _e('Drafts', 'admin_mywords'); ?></a> <strong>(<?php echo $draft_count; ?>)</strong> |
		<a href="posts.php?status=pending&amp;limit=<?php echo $limit ?>"><?php _e('Pending of Review', 'admin_mywords'); ?></a> <strong>(<?php echo $pending_count; ?>)</strong>
	</td></tr>
</table></form>
<br />
<form name="modPosts" id="form-posts" method="post" action="posts.php">
<div class="options">
	<?php echo isset($nav) ? $nav->render() : ''; ?>
	<select name="op" id="posts-op">
		<option value=""><?php _e('Bulk Actions','admin_mywords'); ?></option>
		<option value="delete"><?php _e('Delete Posts','admin_mywords'); ?></option>
		<option value="pending"><?php _e('Set status as Pending review','admin_mywords'); ?></option>
		<option value="draft"><?php _e('Set status as Draft','admin_mywords'); ?></option>
		<option value="publish"><?php _e('Set status as published','admin_mywords'); ?></option>
	</select>
	<input type="button" value="<?php _e('Apply', 'admin_mywords'); ?>" onclick="submit();" />
</div>
<table border="0" cellspacing="1" cellpadding="0" class="outer" style="margin: 5px 0;">
  <tr class="head" align="center">
  	<th align="center" width="30"><input type="checkbox" name="checkall" id="checkall" value="1" onclick='$("#form-posts").toggleCheckboxes(":not(#checkall)");' /></th>
    <th align="left" width="30%"><?php _e('Post','admin_mywords'); ?></th>
    <th><?php _e('Author','admin_mywords'); ?></th>
    <th align="left"><?php _e('Categories','admin_mywords'); ?></th>
    <th align="left"><?php _e('Tags','admin_mywords'); ?></th>
    <th><img src="../images/commi.png" alt="" /></th>
	<th><?php _e('Date','admin_mywords'); ?></th>
  </tr>
  <?php if(empty($posts)): ?>
  <tr class="even">
  	<td colspan="7" align="center" class="error"><?php _e('No posts where found','admin_mywords'); ?></td>
  </tr>
  <?php endif; ?>
  <?php foreach($posts as $post): ?>
  <tr class="<?php echo tpl_cycle('even,odd'); ?>" valign="top">
  	<td align="center" valign="top"><input type="checkbox" name="posts[]" id="post-<?php echo $post['id']; ?>" value="<?php echo $post['id']; ?>" /></td>
    <td>
    	<strong>
    	<?php switch($post['status']){
			case 'draft':
				echo "<span class=\"draft\">[".__('Draft','admin_mywords')."]</span> ";
				break;
			case 'scheduled':
				echo "<span class=\"draft\">[".__('Scheduled','admin_mywords')."]</span> ";
				break;
			case 'pending':
				echo "<span class=\"draft\">[".__('Pending review','admin_mywords')."]</span> ";
				break;
		} ?><a href="posts.php?op=edit&amp;id=<?php echo $post['id']; ?>"><?php echo $post['title']; ?></a></strong>
    	<span class="mw_options">
    		<a href="posts.php?op=edit&amp;id=<?php echo $post['id']; ?>"><?php _e('Edit','admin_mywords'); ?></a> |
    		<a href="javascript:;" onclick="return post_del_confirm('<?php echo $post['title']; ?>', <?php echo $post['id']; ?>);"><?php _e('Delete','admin_mywords'); ?></a> |
    		<?php if($post['status']!='publish'): ?>
    			<a href="<?php echo MW_URL.'?p='.$post['id']; ?>"><?php _e('Preview','admin_mywords'); ?></a>
    		<?php else: ?>
    			<a href="<?php echo $post['link']; ?>"><?php _e('View','admin_mywords'); ?></a>
    		<?php endif; ?>
    	</span>
    </td>
    <td align="center"><a href="posts.php?author=<?php echo $post['uid'] ?>"><?php echo $post['uname'] ?></a></td>
    <td class="mw_postcats"><?php echo $post['categories']; ?></td>
    <td class="mw_postcats">
    <?php 
    $count = 0;
    foreach ($post['tags'] as $tag): ?>
    <?php echo $count<=0 ? '' : ',' ?><a href="posts.php?tag=<?php echo $tag['id_tag']; ?>"><?php echo $tag['tag']; ?></a>
    <?php $count++; endforeach; ?>
    </td>
    <td align="center">
		<?php echo $post['comments']; ?>
	</td>
    <td align="center"><?php echo $post['date']; ?></td>
  </tr>
  <?php endforeach; ?>
</table>
<?php echo $xoopsSecurity->getTokenHTML(); ?>
</form>
