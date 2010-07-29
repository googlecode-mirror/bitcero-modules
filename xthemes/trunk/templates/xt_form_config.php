<h1 class="rmc_titles"><span style="background-position: -32px;">&nbsp;</span><?php echo sprintf(__("%s Configuration",'xthemes'), $element_info['name']); ?></h1>
<form name="itform" method="post" action="index.php">
	<div id="it-form-container">
		<?php if($xt_show=='theme'): ?>
		    
            <?php $theme->get_config($current_settings); ?>
            
		<?php else: ?>
			
			<?php $plugin->get_config($current_settings); ?>
			
		<?php endif; ?>
        <div class="field_item">
            <div class="caption">&nbsp;</div>
            <div class="content">
                <input type="submit" value="<?php _e('Save Configuration','xthemes'); ?>" class="button" />
                <input type="button" value="<?php _e('Cancel','xthemes'); ?>" class="button" onclick="history.go(-1)" />
            </div>
        </div>
	</div>
    <input type="hidden" name="op" value="save_settings" />
    <input type="hidden" name="element" value="<?php echo $element; ?>" />
</form>