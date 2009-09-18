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
	</td>
	<td align="right">
		<?php echo $nav->render(); ?>
	</td></tr>
</table></form>
<br />
<form name="modPosts" method="post" action="posts.php">
<table border="0" cellspacing="1" cellpadding="0" class="outer">
  <tr class="head" align="center">
  	<th align="center" width="30"><input type="checkbox" name="chekall" value="1" onchange="xoopsCheckAll('modPosts', 'chekall');" /></th>
    <th align="left"><?php _e('Post','admin_mywords'); ?></th>
    <th><?php _e('Author','admin_mywords'); ?></th>
    <th align="left"><?php _e('Categories','admin_mywords'); ?></th>
    <th><?php _e('Tags','admin_mywords'); ?></th>
    <th><img src="images/comi.png" alt="" /></th>
	<th><?php _e('Date','admin_mywords'); ?></th>
  </tr>
  <?php foreach($posts as $post): ?>
  <tr class="<?php echo cycle('even,odd'); ?>">
  	<td align="center"><input type="checkbox" name="posts[]" value="<?php echo $post['id']; ?>" /></td>
    <td><a href="<{$post.link}>"><?php echo $post['title']; ?></a></td>
    <td><{assign var="i" value=1}>
    <{foreach item=cat from=$post.categos}>
    	<{if $i==1}><{$cat.nombre}><{else}> &middot; <{$cat.nombre}><{/if}>
    	<{assign var="i" value=$i+1}>
    <{/foreach}></td>
    <td align="center"><{$post.fecha}></td>
    <td align="center">
		<{$post.tracks}>
	</td>
    <td align="center"><{$post.coms}></td>
	<td align="center"><img src="../images/<{if $post.aprovado}>aprovado.png<{else}>delete.png<{/if}>" alt="" /></td>
	<td align="center"><a href="<{$xoops_url}>/userinfo.php?uid=<{$post.uid}>"><{$post.uname}></a></td>
	<td align="center"><a href="posts.php?op=edit&amp;post=<{$post.id}>" title="<{$lang_edit}>"><img src="../images/edit.png" alt="<{$lang_edit}>" /></a>
	<a href="posts.php?op=delete&amp;post=<{$post.id}>" title="<{$lang_delete}>"><img src="../images/delete.png" alt="<{$lang_delete}>" /></a>
	<a href="posts.php?op=trackbacks&amp;post=<{$post.id}>" title="<{$lang_trackbacks}>"><img src="../images/trackback.png" alt="<{$lang_trackbacks}>" /></a></td>
  </tr>
  <?php endforeach; ?>
</table>
</form>
