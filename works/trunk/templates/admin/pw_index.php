<h1 class="rmc_titles"><span style="background-position: left top;">&nbsp;</span><?php _e('Dashboard','admin_works'); ?></h1>

<div id="works-dash-right">
	<?php foreach($widgets_right as $widget): ?>
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
			<table cellspacing="0" border="0" width="100%">
				<tr class="even qoverview">
					<td><span><a href="categos.php"><?php echo $categories; ?></a></span> <a href="categos.php"><?php _e('Categories','admin_works'); ?></a></td>
					<td><span><a href="clients.php"><?php echo $customers; ?></a></span> <a href="clients.php"><?php _e('Customers','admin_works'); ?></a></td>
				</tr>
				<tr class="odd qoverview">
					<td><span><a href="types.php"><?php echo $types; ?></a></span> <a href="types.php"><?php _e('Customers types','admin_works'); ?></a></td>
					<td><span><a href="works.php"><?php echo $works; ?></a></span> <a href="works.php"><?php _e('Works','admin_works'); ?></a></td>
				</tr>
				<tr class="even qoverview">
					<td><span><a href="works.php"><?php echo $images; ?></a></span> <a href="works.php"><?php _e('Images types','admin_works'); ?></a></td>
					<td><?php _e('Current version:','admin_works'); ?> <strong><?php echo RMUtilities::getVersion(false, 'works', 0); ?></strong></td>
				</tr>
			</table>
		</div>
	</div>
	
	<div class="outer works_widget">
		<div class="th widget_title"><?php _e('Works not published','admin_works'); ?></div>
		<div class="widget_content">
		<?php foreach ($works_pending as $w): ?>
			<div class="<?php echo tpl_cycle('even,odd'); ?>">
				<strong><a href="works.php?op=edit&amp;id=<?php echo $w['id']; ?>"><?php echo $w['title']; ?></a></strong>
				<span class="pw_dates"><?php echo $w['date']; ?></span>
				<span class="pw_descriptions"><?php echo $w['desc']; ?></span>
			</div>
		<?php endforeach; ?>
			<div class="foot">
				<a href="works.php"><?php _e('View all works','admin_works'); ?></a>
			</div>
		</div>
	</div>
	
	<div class="outer works_widget">
		<div class="th widget_title"><?php _e('Recent News','admin_works'); ?></div>
		<script type="text/javascript">
		<?php include '../include/js/dashboard.js'; ?>
		</script>
		<div class="widget_content" id="pw-recent-news">
		</div>
	</div>
	
	<?php foreach($widgets_left as $widget): ?>
	<div class="outer works_widget">
		<div class="th widget_title"><?php echo $widget['title']; ?></div>
		<div class="widget_content"><?php echo $widget['content']; ?></div>
	</div>
	<?php endforeach; ?>
</div>
