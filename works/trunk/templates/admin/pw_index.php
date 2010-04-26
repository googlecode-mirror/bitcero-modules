<h1 class="rmc_titles"><span style="background-position: left top;">&nbsp;</span><?php _e('Dashboard','admin_works'); ?></h1>

<div id="works-dash-right">
	<?php foreach($widgets as $widget): ?>
	<div class="outer works_widget">
		<div class="th widget_title"><?php echo $widget['title']; ?></div>
		<div class="widget_content"><?php echo $widget['content']; ?></div>
	</div>
	<?php endforeach; ?>
</div>

<div id="works-dash-left">
	<div class="outer works_widget">
		<div class="th widget_title"><?php _e('Quick Overview','admin_works'); ?></div>
		<div class="widget_content">
		
		</div>
	</div>
</div>
