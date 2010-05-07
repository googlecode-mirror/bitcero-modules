<?php
// $Id$
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

function rmc_available_mods(){
	global $available_mods;
	
	$ret['title'] = __('Available Modules','rmcommon');
	$ret['icon'] = RMCURL.'/images/modules.png';
	
	ob_start();
?>
	<div class="rmc_widget_content_reduced rmc_modules_widget">
		<?php foreach($available_mods as $mod): ?>
		<div class="<?php echo tpl_cycle("even,odd"); ?>">
			<a href="modules.php?action=install&amp;dir=<?php echo $mod->getInfo('dirname'); ?>" class="rmc_mod_img" style="background: url(<?php echo XOOPS_URL; ?>/modules/<?php echo $mod->getInfo('dirname'); ?>/<?php echo $mod->getInfo('image'); ?>) no-repeat center;"><span>&nbsp;</span></a>
			<strong><a href="modules.php?action=install&amp;dir=<?php echo $mod->getInfo('dirname'); ?>"><?php echo $mod->getInfo('name'); ?></a></strong>
			<span class="rmc_available_options">
				<a href="modules.php?action=install&amp;dir=<?php echo $mod->getInfo('dirname'); ?>"><?php _e('Install','rmcommon'); ?></a> |
				<a href="javascript:;" onclick="show_module_info('<?php echo $mod->getInfo('dirname'); ?>');"><?php _e('More info','rmcommon'); ?></a>
			</span>
			<span class="rmc_mod_info" id="mod-<?php echo $mod->getInfo('dirname'); ?>">
				<?php _e('Version:','rmcommon'); ?> 
				<?php if($mod->getInfo('rmnative')): ?>
					<?php echo RMUtilities::format_version($mod->getInfo('rmversion')); ?>
				<?php else: ?>
					<?php echo $mod->getInfo('version'); ?>
				<?php endif; ?><br />
				<?php _e('Author:', 'rmcommon'); ?> <?php echo substr(strip_tags($mod->getInfo('author')), 0, 12); ?>
			</span>
		</div>
		<?php endforeach; ?>
	</div>
<?php
	$ret['content'] = ob_get_clean();
	return $ret;
	//print_r($available_mods);
	
}
