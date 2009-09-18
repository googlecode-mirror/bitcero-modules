<?php xt_menu_options(); ?>
<!-- Current Theme -->
<h1>Current Theme</h1>
<div id="it-current-theme">
	<div class="theme_info">
		<?php if (isset($theme_info['screenshot'])): ?>
			<img class="screenshot" src="<?php echo $theme_url.'/'.$theme_info['screenshot']; ?>" alt="<?php echo $theme_info['name']; ?>" />
		<?php endif; ?>
		<h3><?php echo $theme_info['name'].' '.$theme_info['version']; ?></h3>
		<?php echo $theme_info['description']; ?>
		<div class="theme_data"><strong>Version:</strong> <?php echo $theme_info['version']; ?><br />
		<strong>Author:</strong> <a href="<?php echo $theme_info['url']; ?>"><?php echo $theme_info['author']; ?></a><br />
		<strong>Author Email:</strong> <?php echo $theme_info['email']; ?></div>
		<?php if(!$not_valid): ?>
		<div class="theme_buttons">
			<a href="?op=config"><span>Settings</span></a>
			<a href="?op=url"><span>Visit Web</span></a>
			<a href="?op=more"><span>More themes</span></a>
		</div>
		<?php endif; ?>
	</div>
</div>
<!-- // -->
<br />
<!-- Other Themes -->
<h1>Other Available Themes</h1>
<div id="it_other_themes">
	<?php foreach($themes as $theme): ?>
		<div class="other_theme_item">
			<img class="screenshot" src="<?php echo XOOPS_URL; ?>/themes/<?php echo $theme['info']['dir'].'/'.$theme['info']['screenshot']; ?>" alt="<?php echo $theme['info']['name']; ?>" />
			<h2><?php echo $theme['info']['name']; ?></strong> by <a href="<?php echo $theme['info']['url']; ?>"><?php echo $theme['info']['author']; ?></a></h2>
			<span class="descs"><?php echo substr(strip_tags($theme['info']['description'], '<strong>'), 0, 60); ?></span>
			<?php if($theme['valid']): ?>
				<div class="theme_buttons">
				<a href="?op=install-theme&amp;theme=<?php echo $theme['info']['dir']; ?>" class="button"><span>Install</span></a>
				<a href="<?php echo $theme['info']['url']; ?>"><span>Web Site</span></a>
				</div>
			<?php else: ?>
				<div class="theme_buttons">
				<a href="?op=install-normal&amp;theme=<?php echo $theme['info']['dir']; ?>" class="button"><span>Install</span></a>
				<a href="http://temasweb.com"><span>Premium Themes</span></a>
				</div>
			<?php endif; ?>
		</div>
	<?php endforeach; ?>
</div>
<!-- // -->