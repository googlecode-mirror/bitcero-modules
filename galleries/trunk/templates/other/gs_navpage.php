<div class="rmc_pages_navigation_container">
    
	<?php if($total_pages>$steps && $current_page>$steps-1): ?>
		<a href="#" title="<?php _e('First Page','rmcommon'); ?>" class="image pages" id="page-1"><img src="<?php echo RMCURL; ?>/images/first.gif" alt="<?php _e('First Page','rmcommon'); ?>" /></a>
	<?php endif; ?>
	
	<?php
		for($i=$start;$i<=$end;$i++):
			if ($i==$current_page):
	?>
		<span class="current_page"><?php echo $i; ?></span>
	<?php else: ?>
		<a href="#" title="<?php echo sprintf(__('Page %u','rmcommon'), $i); ?>" class="page-<?php echo $i; ?>"><?php echo $i; ?></a>
	<?php 
			endif;
	    endfor;
	?>
	
	<?php if($total_pages>$steps && $current_page<($total_pages-$steps)+2): ?>
		<a href="#" title="<?php _e('Last Page','rmcommon'); ?>" class="image pages" id="page-<?php echo $total_pages; ?>"><img src="<?php echo RMCURL; ?>/images/last.gif" alt="<?php _e('Last Page','rmcommon'); ?>" /></a>
	<?php endif; ?>
	
</div>