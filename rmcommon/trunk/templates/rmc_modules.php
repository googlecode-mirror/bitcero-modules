<h1 class="rmc_titles"><?php _e('Modules Management','rmcommon'); ?></h1>

<?php foreach($modules as $mod): ?>
<div class="rmc_item_module">
	<strong><a href="<?php echo $mod['link']; ?>"><?php echo $mod['name']; ?></a></strong>
	<?php echo $mod['description']; ?>
</div>
<?php endforeach; ?>
