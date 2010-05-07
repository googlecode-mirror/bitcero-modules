<h1 class="rmc_titles"><?php _e('Modules Management','rmcommon'); ?></h1>

<?php foreach($installed_modules as $mod): ?>
<div class="rmc_item_module">
	<a href="<?php if($mod->getVar('hasMain')): ?><?php echo XOOPS_URL; ?>/modules/<?php echo $mod->getVar('dirname'); ?><?php else: ?>"<?php echo $mod->getVar('name'); ?>
</div>
<?php endforeach; ?>
