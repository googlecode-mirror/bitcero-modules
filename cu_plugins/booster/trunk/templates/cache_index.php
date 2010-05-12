<h1 class="rmc_titles"><?php _e('Cache Status','booster'); ?></h1>
<script type="text/javascript">
    <!--
    $(document).ready(function(){
        $("#enable-disable").click(function(){
            $("#action").val("<?php echo $plugin->get_config('enabled') ? 'disable' : 'enable'; ?>");
            $("#frm-cache").submit();
        });
        
        $("#save").click(function(){
            $("#action").val('save');
            $("#frm-cache").submit();
        });
        
        $("#clean-cache").click(function(){
            return confirm('<?php _e('Do you really wish to delete all cached files?','booster'); ?>');
        });
    });
    -->
</script>
<div class="cache_options">
	<a href="plugins.php?p=booster&amp;action=clean" id="clean-cache" style="background-image: url(plugins/booster/images/clean.png);"><?php _e('Clean cache','booster'); ?></a>
	<a href="plugins.php?p=booster&amp;action=view" style="background-image: url(plugins/booster/images/files.png);"><?php _e('View cache files','booster'); ?></a>
</div>

<form action="plugins.php" method="post" name="frmcache" id="frm-cache">
<div class="descriptions">
	<?php echo sprintf(__('Current status of booster is: %s','booster'), $plugin->get_config('enabled') ? __('<strong>Enabled</strong>','booster') : __('<strong>Disabled</strong>','booster')); ?>
</div><br />
<strong><?php _e('Change booster status','booster'); ?></strong>
<input type="button" id="enable-disable" value="<?php $plugin->get_config('enabled') ? _e('Disable booster','booster') : _e('Enable booster','booster'); ?>" />
<br /><br />
<div class="descriptions">
	<?php _e('Cache duration specify the time inteval to rewrite cache files. A small value indicates small interval. The value must be specified in seconds.','booster'); ?>
</div><br />
<strong><?php _e('Cache duration','booster'); ?></strong>
<input type="text" name="duration" size="8" value="<?php echo $plugin->get_config('time'); ?>" /> <?php _e('seconds','booster'); ?> &nbsp;
<input type="button" id="save" value="<?php _e('Save Settings','booster'); ?>" />
<input type="hidden" name="action" id="action" value="" />
<input type="hidden" name="p" value="booster" />
</form>