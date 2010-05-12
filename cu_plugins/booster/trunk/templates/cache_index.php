<h1 class="rmc_titles"><?php _e('Cache Status','cachetizer'); ?></h1>
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
            return confirm('<?php _e('Do you really wish to delete all cached files?','cachetizer'); ?>');
        });
    });
    -->
</script>
<div class="cache_options">
	<a href="plugins.php?p=cachetizer&amp;action=clean" id="clean-cache" style="background-image: url(plugins/cachetizer/images/clean.png);"><?php _e('Clean cache','cachetizer'); ?></a>
	<a href="plugins.php?p=cachetizer&amp;action=view" style="background-image: url(plugins/cachetizer/images/files.png);"><?php _e('View cache files','cachetizer'); ?></a>
</div>

<form action="plugins.php" method="post" name="frmcache" id="frm-cache">
<div class="descriptions">
	<?php echo sprintf(__('Current status of Cachetizer is: %s','cachetizer'), $plugin->get_config('enabled') ? __('<strong>Enabled</strong>','cachetizer') : __('<strong>Disabled</strong>','cachetizer')); ?>
</div><br />
<strong><?php _e('Change Cachetizer status','cachetizer'); ?></strong>
<input type="button" id="enable-disable" value="<?php $plugin->get_config('enabled') ? _e('Disable Cachetizer','cachetizer') : _e('Enable Cachetizer','cachetizer'); ?>" />
<br /><br />
<div class="descriptions">
	<?php _e('Cache duration specify the time inteval to rewrite cache files. A small value indicates small interval. The value must be specified in seconds.','cachetizer'); ?>
</div><br />
<strong><?php _e('Cache duration','cachetizer'); ?></strong>
<input type="text" name="duration" size="8" value="<?php echo $plugin->get_config('time'); ?>" /> <?php _e('seconds','cachetizer'); ?> &nbsp;
<input type="button" id="save" value="<?php _e('Save Settings','cachetizer'); ?>" />
<input type="hidden" name="action" id="action" value="" />
<input type="hidden" name="p" value="cachetizer" />
</form>