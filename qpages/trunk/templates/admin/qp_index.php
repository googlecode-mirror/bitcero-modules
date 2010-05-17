<h1 class="rmc_titles"><span style="background-position: 0 0;">&nbsp;</span><?php _e('Dashboard','qpages'); ?></h1>
<div id="qp-dashboard-container">
	<div id="qp-dash-right">
		
		<div class="qp_widget_container">
			<div class="outer">
				<div class="th"><?php _e('Pages Statistics','qpages'); ?></div>
				<div class="even" style="text-align: center;">
					<img src="<?php echo $chart; ?>" title="<?php _e('Most viewed pages'); ?>" />
				</div>
			</div>
		</div>
		
		<div class="qp_widget_container">
			<div class="outer">
				<div class="th"><?php _e('About QuickPages','qpages'); ?></div>
				<div class="even" style="line-height: 150%;">
					<?php _e('Create static pages easily and quickly with QuickPages for Xoops.','qpages'); ?>
					<ul>
						<li>Created by <a href="http://redmexico.com.mx"><strong>BitC3R0</strong></a>.</li>
						<li>A module from <a href="http://redmexico.com.mx"><strong>Red México</strong></a>.</li>
						<li>You can contac me at i.bitcero@gmail.com</li>
						<li>You can visit the <a href="http://redmexico.com.mx/docs/quickpages">help page</a> for QuickPages</li>
					</ul>
				</div>
			</div>
		</div>
		
		<?php foreach($left_widgets as $w): ?>
		<div class="qp_widget_container">
			<div class="outer">
				<div class="th"><?php echo $w['title']; ?></div>
				<div class="even"><?php echo $w['content']; ?></div>
			</div>
		</div>
		<?php endforeach; ?>
		
	</div>
	
	<div id="qp-dash-left">
	
		<div class="qp_widget_container" id="qp-news-content">
			<div class="outer">
				<div class="th"><img src="../images/loading.gif" class="qp_loading_image" /> <?php _e('Recent News','qpages'); ?></div>
				<div class="even">
					
				</div>
			</div>
		</div>
		
		<div class="qp_widget_container">
			<div class="outer" id="qp-recent-pages">
				<div class="th"><?php _e('Recent Pages','qpages'); ?></div>
				<?php if(empty($pages)): ?>
				<div class="even">
					<?php _e('There are not pages created yet!','qpages'); ?>
				</div>
				<?php endif; ?>
				<?php foreach($pages as $page): ?>
				<div class="even">
					<a href="<?php echo $page['link']; ?>"><strong><?php echo $page['title']; ?></strong></a>
					<?php if(!$page['public']): _e('[Draft]','qpages'); endif;?>
					&nbsp;
					(<a href="pages.php?op=edit&amp;id=<?php echo $page['id']; ?>"><?php _e('Edit','qpages'); ?></a>)<br />
					<?php echo $page['desc']; ?>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
		
		<?php foreach($right_widgets as $w): ?>
		<div class="qp_widget_container">
			<div class="outer">
				<div class="th"><?php echo $w['title']; ?></div>
				<div class="even"><?php echo $w['content']; ?></div>
			</div>
		</div>
		<?php endforeach; ?>
		
	</div>
	
</div>