<h1 class="rmc_titles mw_titles"><span style="background-position: -32px 0;">&nbsp;</span><?php _e('Posts Management','mywords'); ?></h1>
<form name="frmSearch" method="get" action="posts.php" style="margin: 0;">
<table width="100%" cellspacing="0" border="0">
	<tr class="even">
	<td align="left">
			<?php _e('Search:','mywords'); ?>
			<input type="text" name="keyw" value="<?php echo $keyw!='' ? $keyw : ''; ?>" size="30" />
	</td>
	<td align="center">
		<?php _e('Results:','mywords'); ?>
		<input type="text" size="5" name="limit" value="<?php echo $limit; ?>" />
		<input type="submit" name="sbt" value="<?php _e('Go!','mywords'); ?>" class="formButton" />
	</td>
	<td align="center">
		<a href="posts.php?op=new"><?php _e('Add New','mywords'); ?></a> |
		<a href="posts.php?limit=<?php echo $limit ?>"><?php _e('Show all','mywords'); ?></a> <strong>(<?php echo ($pub_count+$draft_count+$pending_count); ?>)</strong> |
		<a href="posts.php?status=publish&amp;limit=<?php echo $limit ?>"><?php _e('Published', 'admin_mywords'); ?></a> <strong>(<?php echo $pub_count; ?>)</strong> |
		<a href="posts.php?status=draft&amp;limit=<?php echo $limit ?>"><?php _e('Drafts', 'admin_mywords'); ?></a> <strong>(<?php echo $draft_count; ?>)</strong> |
		<a href="posts.php?status=waiting&amp;limit=<?php echo $limit ?>"><?php _e('Pending of Review', 'admin_mywords'); ?></a> <strong>(<?php echo $pending_count; ?>)</strong>
	</td></tr>
</table></form>
<br />
<form name="modPosts" id="form-posts" method="post" action="posts.php">
<div class="options">
	<?php echo isset($nav) ? $nav->render(false) : ''; ?>
	<select name="op" id="posts-op">
		<option value=""><?php _e('Bulk Actions','mywords'); ?></option>
		<option value="delete"><?php _e('Delete Posts','mywords'); ?></option>
		<option value="status-waiting"><?php _e('Set status as Pending review','mywords'); ?></option>
		<option value="status-draft"><?php _e('Set status as Draft','mywords'); ?></option>
		<option value="status-published"><?php _e('Set status as published','mywords'); ?></option>
	</select>
	<input type="button" value="<?php _e('Apply', 'admin_mywords'); ?>" onclick="submit();" />
</div>
<table border="0" cellspacing="1" cellpadding="0" class="outer" style="margin: 5px 0;">
  <tr class="head" align="center">
  	<th align="center" width="30"><input type="checkbox" name="checkall" id="checkall" value="1" onclick='$("#form-posts").toggleCheckboxes(":not(#checkall)");' /></th>
    <th align="left" width="30%"><?php _e('Post','mywords'); ?></th>
    <th><?php _e('Author','mywords'); ?></th>
    <th align="left"><?php _e('Categories','mywords'); ?></th>
    <th align="left"><?php _e('Tags','mywords'); ?></th>
    <th><img src="../images/commi.png" alt="" /></th>
	<th><?php _e('Date','mywords'); ?></th>
  </tr>
  <?php if(empty($posts)): ?>
  <tr class="even">
  	<td colspan="7" align="center" class="error"><?php _e('No posts where found','mywords'); ?></td>
  </tr>
  <?php endif; ?>
  <?php foreach($posts as $post): ?>
  <tr class="<?php echo tpl_cycle('even,odd'); ?>" valign="top">
  	<td align="center" valign="top"><input type="checkbox" name="posts[]" id="post-<?php echo $post['id']; ?>" value="<?php echo $post['id']; ?>" /></td>
    <td>
    	<strong>
    	    <a href="posts.php?op=edit&amp;id=<?php echo $post['id']; ?>"><?php echo $post['title']; ?></a>
            <?php switch($post['status']){
                case 'draft':
                    echo "<span class=\"draft\">- ".__('Draft','mywords')."</span> ";
                    break;
                case 'scheduled':
                    echo "<span class=\"sheduled\">- ".__('Scheduled','mywords')."</span> ";
                    break;
                case 'waiting':
                    echo "<span class=\"pending\">- ".__('Pending','mywords')."</span> ";
                    break;
            } ?>
        </strong>
    	<span class="mw_options">
    		<a href="posts.php?op=edit&amp;id=<?php echo $post['id']; ?>"><?php _e('Edit','mywords'); ?></a> |
    		<a href="javascript:;" onclick="return post_del_confirm('<?php echo $post['title']; ?>', <?php echo $post['id']; ?>);"><?php _e('Delete','mywords'); ?></a> |
    		<?php if($post['status']!='publish'): ?>
    			<a href="<?php echo MW_URL.'?p='.$post['id']; ?>"><?php _e('Preview','mywords'); ?></a>
    		<?php else: ?>
    			<a href="<?php echo $post['link']; ?>"><?php _e('View','mywords'); ?></a>
    		<?php endif; ?>
    	</span>
    </td>
    <td align="center"><a href="posts.php?author=<?php echo $post['uid'] ?>"><?php echo $post['uname'] ?></a></td>
    <td class="mw_postcats"><?php echo $post['categories']; ?></td>
    <td class="mw_postcats">
    <?php 
    $count = 1;
    $ct = count($post['tags']);
    foreach ($post['tags'] as $tag): ?>
    <?php echo $tag['tag']; ?><?php echo $count<$ct ? ',' : ''; ?>
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
<input type="hidden" name="page" value="<?php echo $page; ?>" />
<input type="hidden" name="keyw" value="<?php echo $keyw; ?>" />
<input type="hidden" name="limit" value="<?php echo $limit; ?>" />
</form>
