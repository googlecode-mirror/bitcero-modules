<h1 class="rmc_titles mw_titles" style="background-image: url(../images/post32.png);"><?php _e('Posts Management','admin_mywords'); ?></h1>
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
		<a href="posts.php?limit=<{$limit}>"><?php _e('Show all','admin_mywords'); ?></a>
	</td></tr>
</table></form>
<br />
<form name="modPosts" method="post" action="posts.php">
<?php echo isset($nav) ? $nav->render() : ''; ?>
<select name="op" id="posts-op">
	<option value=""><?php _e('Bulk Actions','admin_mywords'); ?></option>
	<option value="delete"><?php _e('Delete Posts','admin_mywords'); ?></option>
</select>
<table border="0" cellspacing="1" cellpadding="0" class="outer" style="margin: 5px 0;">
  <tr class="head" align="center">
  	<th align="center" width="30"><input type="checkbox" name="chekall" value="1" onchange="xoopsCheckAll('modPosts', 'chekall');" /></th>
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
  	<td align="center" valign="top"><input type="checkbox" name="posts[]" value="<?php echo $post['id']; ?>" /></td>
    <td>
    	<strong><?php echo $post['status']!='publish' ? "<span class=\"draft\">[".__('Draft','admin_mywords')."]</span> " : ''; ?><a href="posts.php?op=edit&amp;id=<?php echo $post['id']; ?>"><?php echo $post['title']; ?></a></strong>
    	<span class="mw_options">
    		<a href="posts.php?op=edit&amp;id=<?php echo $post['id']; ?>"><?php _e('Edit','admin_mywords'); ?></a> |
    		<a href="posts.php?op=delete&amp;id=<?php echo $post['id']; ?>"><?php _e('Delete','admin_mywords'); ?></a> |
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
</form>
