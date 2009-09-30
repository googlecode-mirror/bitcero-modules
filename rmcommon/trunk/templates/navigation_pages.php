<div class="rmc_pages_navigation_container">
	<span class="pages_caption"><?php _e('Pages:','rmcommon'); ?></span>
	<?php if($total_pages>$steps && $current_page>$steps-1): ?>
		<a href="<?php echo str_replace('{PAGE_NUM}', 1, $url); ?>" title="<?php _e('First Page','rmcommon'); ?>" class="image"><img src="<?php echo RMCURL; ?>/images/first.gif" alt="<?php _e('First Page','rmcommon'); ?>" /></a>
	<?php endif; ?>
	<?php if($current_page>1): ?>
		<a href="<?php echo str_replace('{PAGE_NUM}', $current_page-1, $url); ?>" title="<?php _e('Previous Page','rmcommon'); ?>" class="image"><img src="<?php echo RMCURL; ?>/images/prev.gif" alt="<?php _e('Previous Page','rmcommon'); ?>" /></a>
	<?php endif; ?>
	
	<?php if($start>1): ?><span class="page_separators"><img src="<?php echo RMCURL; ?>/images/points.gif" alt="" /></span><? endif; ?>
	
	<?php
		for($i=$start;$i<=$end;$i++):
			if ($i==$current_page):
	?>
		<span class="current_page"><?php echo $i; ?></span>
	<?php else: ?>
		<a href="<?php echo str_replace('{PAGE_NUM}', $i, $url); ?>" title="<?php echo sprintf(__('Page %u','rmcommon'), $i); ?>"><?php echo $i; ?></a>
	<?php 
			endif;
	    endfor;
	?>
	
	<?php if($start<=$total_pages-$steps): ?><span class="page_separators"><img src="<?php echo RMCURL; ?>/images/points.gif" alt="" /></span><? endif; ?>
	
	<?php if($current_page<$total_pages): ?>
		<a href="<?php echo str_replace('{PAGE_NUM}', $current_page+1, $url); ?>" title="<?php _e('Next Page','rmcommon'); ?>" class="image"><img src="<?php echo RMCURL; ?>/images/next.gif" alt="<?php _e('Next Page','rmcommon'); ?>" /></a>
	<?php endif; ?>
	<?php if($total_pages>$steps && $current_page<($total_pages-$steps)+2): ?>
		<a href="<?php echo str_replace('{PAGE_NUM}', $total_pages, $url); ?>" title="<?php _e('Last Page','rmcommon'); ?>" class="image"><img src="<?php echo RMCURL; ?>/images/last.gif" alt="<?php _e('Last Page','rmcommon'); ?>" /></a>
	<?php endif; ?>
</div>