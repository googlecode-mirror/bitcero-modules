<!-- Current Theme -->
<h1 class="rmc_titles"><span>&nbsp;</span><?php _e('Dashboard', 'xthemes'); ?></h1>
<div id="it-current-theme">
	<div class="theme_info">
		<?php if (isset($theme_info['screenshot'])): ?>
			<img class="screenshot" src="<?php echo $theme_url.'/'.$theme_info['screenshot']; ?>" alt="<?php echo $theme_info['name']; ?>" />
		<?php endif; ?>
		<h3><?php echo $theme_info['name'].' '.$theme_info['version']; ?></h3>
		<?php echo $theme_info['description']; ?>
		<div class="theme_data"><strong><?php _e('Version:','xthemes'); ?></strong> <?php echo $theme_info['version']; ?><br />
		<strong><?php _e('Author:','xthemes'); ?></strong> <a href="<?php echo $theme_info['url']; ?>"><?php echo $theme_info['author']; ?></a><br />
		<strong><?php _e('Author Email:','xthemes'); ?></strong> <?php echo $theme_info['email']; ?></div>
		<?php if(!$not_valid): ?>
		<div class="theme_buttons">
			<a href="?op=config"><span><?php _e('Settings','xthemes'); ?></span></a>
			<a href="?op=url"><span><?php _e('Visit Web','xthemes'); ?></span></a>
			<a href="?op=more"><span><?php _e('More themes','xthemes'); ?></span></a>
		</div>
		<?php endif; ?>
	</div>
</div>
<!-- // -->
<br />
<!-- Other Themes -->
<h1 class="rmc_titles"><span style="background-position: -128px;">&nbsp;</span><?php _e('Other Available Themes','xthemes'); ?></h1>
<div id="it_other_themes">
	<?php foreach($themes as $theme): ?>
		<div class="other_theme_item">
			<img class="screenshot" src="<?php echo XOOPS_URL; ?>/themes/<?php echo $theme['info']['dir'].'/'.$theme['info']['screenshot']; ?>" alt="<?php echo $theme['info']['name']; ?>" />
			<h2><?php echo $theme['info']['name']; ?></strong></h2>
			<?php _e('by','xthemes'); ?> <a href="<?php echo $theme['info']['url']; ?>"><?php echo $theme['info']['author']; ?></a><br />
			<span class="descs"><?php echo substr(strip_tags($theme['info']['description'], '<strong>'), 0, 60); ?></span>
			<?php if($theme['valid']): ?>
				<div class="theme_buttons">
				<a href="?op=install-theme&amp;theme=<?php echo $theme['info']['dir']; ?>" class="button"><span><?php _e('Install','xthemes'); ?></span></a>
				<a href="<?php echo $theme['info']['url']; ?>"><span><?php _e('Web Site','xthemes'); ?></span></a>
				</div>
			<?php else: ?>
				<div class="theme_buttons">
				<a href="?op=install-normal&amp;theme=<?php echo $theme['info']['dir']; ?>" class="button"><span><?php _e('Install','xthemes'); ?></span></a>
				<a href="index.php?op=catalog"><span><?php _e('More themes','xthemes'); ?></span></a>
				</div>
			<?php endif; ?>
		</div>
	<?php endforeach; ?>
</div>
<!-- // -->