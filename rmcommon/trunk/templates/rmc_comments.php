<div class="comments_search">
	<form name="search_form" method="get" action="comments.php">
	<?php _e('Search:','rmcommon'); ?>
	<input type="text" name="w" id="wsearch" value="<?php echo isset($keyw) ? $keyw : '' ?>" size="20" />
	<input type="button" value="<?php _e('Apply','rmcommon'); ?>" onclick="$('#wsearch').val()==''?alert('<?php _e('You need something to search!','rmcommon'); ?>'):submit();" />
	<input type="hidden" name="action" value="" />
	</form>
</div>
<h1 class="rmc_titles"><?php _e('Comments Manager','rmcommon'); ?></h1>
<form name="list_comments" method="post" action="comments.php" id="list-comments">
<div class="rmc_bulkactions">
<?php $nav->display(false, true); ?>
<select name="action" id="action-select" onchange="$('#action-select2').val($(this).val());">
    <option value="" selected="selected"><?php _e('Bulk Actions...','rmcommon'); ?></option>
    <option value="unapprove"><?php _e('Set unapproved','rmcommon'); ?></option>
    <option value="approve"><?php _e('Set approved','rmcommon'); ?></option>
    <option value="spam"><?php _e('Mark as SPAM','rmcommon'); ?></option>
    <option value="delete"><?php _e('Delete comments','rmcommon'); ?></option>
</select>
<input type="submit" value="<?php _e('Apply','rmcommon'); ?>" onclick="if($('#action-select').val()=='delete') return confirm('Do you really want to delete selected comments?');" /> &nbsp;&nbsp;
<a href="comments.php"><?php _e('View all','rmcommon'); ?></a> |
<?php if($filter=='waiting'): ?>
<strong><?php _e('Unapproved','rmcommon'); ?></strong> |
<?php else: ?>
<a href="comments.php?filter=waiting"><?php _e('Unapproved','rmcommon'); ?></a> |
<?php endif; ?>
<?php if($filter=='approved'): ?>
<strong><?php _e('Approved','rmcommon'); ?></strong>
<?php else: ?>
<a href="comments.php?filter=approved"><?php _e('Approved','rmcommon'); ?></a>
<?php endif; ?>
|
<?php if($filter=='spam'): ?>
<strong><?php _e('SPAM','rmcommon'); ?></strong>
<?php else: ?>
<a href="comments.php?filter=spam"><?php _e('SPAM','rmcommon'); ?></a>
<?php endif; ?>
</div>
<table class="outer" cellspacing="0" width="100%">
    <thead>
    <tr>
        <th width="20"><input type="checkbox" id="checkall" value="" onclick="$('#list-comments').toggleCheckboxes(':not(#checkall)');" /></th>
        <th align="left"><?php _e('Author','rmcommon'); ?></th>
        <th align="left"><?php _e('Comment','rmcommon'); ?></th>
        <th><?php _e('Status','rmcommon'); ?></th>
        <th nowrap="nowrap"><?php _e('In reply to','rmcommon'); ?></th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <th width="20"><input type="checkbox" id="checkall2" value="" onclick="$('#list-comments').toggleCheckboxes(':not(#checkall2)');" /></th>
        <th align="left"><?php _e('Author','rmcommon'); ?></th>
        <th align="left"><?php _e('Comment','rmcommon'); ?></th>
        <th><?php _e('Status','rmcommon'); ?></th>
        <th nowrap="nowrap"><?php _e('In reply to','rmcommon'); ?></th>
    </tr>
    </tfoot>
    <tbody>
    <?php if(count($comments)<=0): ?>
    <tr class="head">
        <td colspan="5" align="center"><?php _e('There are not comments yet!','rmcommon'); ?></td>
    </tr>
    <?php else: ?>
    <?php foreach ($comments as $com): ?>
    <tr class="<?php echo tpl_cycle("even,odd"); ?>" valign="top"<?php if($com['status']=='spam'): ?> style="color: #F00;"<?php endif; ?>>
        <td align="center"><input type="checkbox" name="coms[]" id="com-<?php echo $com['id']; ?>" value="<?php echo $com['id']; ?>" /></td>
        <td class="poster_cell"><img class="poster_avatar" src="<?php echo $com['poster']['avatar']; ?>" />
        <strong><?php echo $com['poster']['name']; ?></strong>
        <span class="poster_data"><a href="mailto:<?php echo $com['poster']['email']; ?>"><?php echo $com['poster']['email']; ?></a><br />
        <?php echo $com['ip']; ?></span></td>
        <td><span class="comment_date"><?php echo $com['posted']; ?></span>
        <?php echo $com['text']; ?>
        <span class="rmc_options">
        	<a href="comments.php?id=<?php echo $com['id']; ?>&amp;action=edit&amp;page=<?php echo $page; ?>&amp;filter=<?php echo $filter; ?>&amp;w=<?php echo $keyw; ?>"><?php _e('Edit','rmcommon'); ?></a> | 
        	<a href="javascript:;" onclick="confirm_delete(<?php echo $com['id']; ?>);"><?php _e('Delete','rmcommon'); ?></a> | 
        	<?php if($com['status']=='approved'): ?>
        	<a href="javascript:;" onclick="approve_action(<?php echo $com['id']; ?>,'unapprove');"><?php _e('Unnaprove','rmcommon'); ?></a>
        	<?php else: ?>
        	<a href="javascript:;" onclick="approve_action(<?php echo $com['id']; ?>,'approve');"><?php _e('Approve','rmcommon'); ?></a>
        	<?php endif; ?>
        	<?php if($com['status']!='spam'): ?>
        	| <a href="javascript:;" onclick="approve_action(<?php echo $com['id']; ?>,'spam');"><?php _e('Spam','rmcommon'); ?></a>
        	<?php endif; ?>
        </span>
        </td>
        <td align="center">
        	<?php 
        		switch($com['status']){
					case 'approved':
						_e('Approved', 'rmcommon');
						break;
					case 'waiting':
						_e('Unapproved','rmcommon');
						break;
					case 'spam':
						echo "<span style='color: #F00;'>";
						_e('SPAM', 'rmcommon');
						echo "</span>";
						break;
        		}
        	?>
        </td>
        <td align="center">
        	<?php if(isset($com['item'])): ?><?php echo $com['item']; ?><?php else: echo "&nbsp;"; endif; ?><br />
        	<?php echo $com['module']; ?>
        </td>
    </tr>
    <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>
<div class="rmc_bulkactions">
<?php $nav->display(false, true); ?>
<select name="actionb" id="action-select2" onchange="$('#action-select').val($(this).val());">
    <option value="" selected="selected"><?php _e('Bulk Actions...','rmcommon'); ?></option>
    <option value="unapprove"><?php _e('Set unapproved','rmcommon'); ?></option>
    <option value="approve"><?php _e('Set approved','rmcommon'); ?></option>
    <option value="delete"><?php _e('Delete comments','rmcommon'); ?></option>
</select>
<input type="submit" value="<?php _e('Apply','rmcommon'); ?>" onclick="if($('#action-select').val()=='delete') return confirm('Do you really want to delete selected comments?');" /> &nbsp;&nbsp;
</div>
<input type="hidden" name="filter" value="<?php echo $filter; ?>" />
<input type="hidden" name="w" value="<?php echo $keyw; ?>" />
<input type="hidden" name="page" value="<?php echo $page; ?>" />
<?php echo $xoopsSecurity->getTokenHTML(); ?>
</form>