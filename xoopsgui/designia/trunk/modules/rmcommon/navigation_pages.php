<?php load_theme_locale('designia','',true); ?>
<div class="rmc_pages_navigation_container">
	<ul>
		<?php if($showing): ?><li class="showing"><?php echo $showing_legend; ?></li><?php endif; ?>
		<?php if($caption): ?><li class="pages_caption"><?php _e('Pages:','rmcommon'); ?></li><?php endif; ?>
		<?php if($total_pages>$steps && $current_page>$steps-1): ?>
			<li class="pages"><a href="<?php echo str_replace('{PAGE_NUM}', 1, $url); ?>" title="<?php _e('First Page','rmcommon'); ?>"><?php _e('First','designia'); ?></a></li>
		<?php endif; ?>
		
		<?php if($current_page>1): ?>
			<li class="pages"><a href="<?php echo str_replace('{PAGE_NUM}', $current_page-1, $url); ?>" title="<?php _e('Previous Page','rmcommon'); ?>"><?php _e('Previous','designia'); ?></a></li>
		<?php endif; ?>
		
		<?php
			for($i=$start;$i<=$end;$i++):
			if ($i==$current_page):
		?>
			<li class="current_page"><?php echo $i; ?></li>
		<?php else: ?>
			<li class="pages"><a href="<?php echo str_replace('{PAGE_NUM}', $i, $url); ?>" title="<?php echo sprintf(__('Page %u','rmcommon'), $i); ?>"><?php echo $i; ?></a></li>
		<?php 
				endif;
			endfor;
		?>
		
		<?php if($current_page<$total_pages): ?>
			<li class="pages"><a href="<?php echo str_replace('{PAGE_NUM}', $current_page+1, $url); ?>" title="<?php _e('Next Page','rmcommon'); ?>"><?php _e('Next','designia'); ?></a>
		<?php endif; ?>
		
		<?php if($total_pages>$steps && $current_page<($total_pages-$steps)+2): ?>
			<li class="pages"><a href="<?php echo str_replace('{PAGE_NUM}', $total_pages, $url); ?>" title="<?php _e('Last Page','rmcommon'); ?>"><?php _e('Last','designia'); ?></a></li>
		<?php endif; ?>
		
	</ul>
	
</div>