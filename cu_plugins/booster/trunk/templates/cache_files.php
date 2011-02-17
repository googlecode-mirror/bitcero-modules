<h1 class="rmc_titles"><?php _e('Booster Cached Files','booster'); ?></h1>
<script language="JavaScript" type="text/javascript">
/*<![CDATA[*/
$(document).ready(function(){
    $("#del-exp").click(function(){
        $("#action").val('expired');
        $("#frm-files").submit();
    });
    $(".del_file").click(function(){
		id = $(this).attr("id");
		$("#file").val(id);
		$("#action").val('delfile');
		$("#frm-files").submit();
    });
});
/*]]>*/
</script>
<form name="frmfiles" id="frm-files" method="post" action="plugins.php">
<input type="hidden" name="action" id="action" value="" />
<input type="hidden" name="p" value="booster" />
<input type="hidden" name="file" id="file" value="" />
<?php echo $xoopsSecurity->getTokenHTML(); ?>
</form>
<div class="cache_options">
	<a href="plugins.php?p=booster" style="background-image: url(plugins/booster/images/cache.png);"><?php _e('Booster status','booster'); ?></a>
	<a href="plugins.php?p=booster&amp;action=clean" id="clean-cache" style="background-image: url(plugins/booster/images/clean.png);"><?php _e('Clean cache','booster'); ?></a>
	<a href="plugins.php?p=booster&amp;action=view" style="background-image: url(plugins/booster/images/files.png);"><?php _e('View files','booster'); ?></a>
</div>
<div class="descriptions">
	<?php _e('Next are the files cached by booster. You can delete files by clicking delete button.','booster'); ?>
</div>
<div class="descriptions">
	<?php echo sprintf(__('If you wish to delete manually this files then go to %s directory.','booster'), '<strong>'.XOOPS_CACHE_PATH.'/booster/files</strong>'); ?>
</div>
<br />
<table cellspacing="0" width="100%">
<tr class="odd">
	<td colspan="2" align="left">
		<strong><?php echo sprintf(__('%u cached pages','booster'), count($files)); ?></strong> | 
		<strong><?php echo sprintf(__('%u expired pages','booster'), $count_expired); ?></strong>
	</td>
	<td colspan="2" align="right">
		<?php if($count_expired>0): ?><input type="button" value="<?php _e('Delete expired','booster'); ?>" id="del-exp" /><?php else: ?>&nbsp;<?php endif; ?>
	</td>
</tr>
<?php foreach($files as $file): ?>
<tr class="<?php echo tpl_cycle("even,odd"); ?>">
	<td><a href="<?php echo $file['url']; ?>"><?php echo str_replace('http://', '', $file['url']); ?></a></td>
    <td align="center"><?php echo $file['lang']; ?></td>
	<td align="center"<?php echo (time()-$file['time'])>=$plugin->get_config('time') ? ' class="warn"' : ''; ?>><?php echo sprintf(__('%s secs.','booster'), (time()-$file['time'])); ?></td>
	<td align="center"><?php echo RMUtilities::formatBytesSize($file['size']); ?></td>
	<td align="center">
		<input id="<?php echo $file['id']; ?>" class="del_file" type="button" value="<?php _e('Delete','booster'); ?>" />
	</td>
</tr>
<?php endforeach; ?>
</table>
