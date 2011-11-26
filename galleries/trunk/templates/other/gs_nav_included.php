<div class="rmc_pages_navigation_container">
    
	<?php if($total_pages>$steps && $current_page>$steps-1): ?>
		<a href="#" title="<?php _e('First Page','rmcommon'); ?>" onclick="gsGallery.load(1,<?php echo $url; ?>,this); return false;"><?php _e('First','rmcommon'); ?></a>
	<?php endif; ?>
	
	<?php if($current_page>1): ?>
		<a href="#" title="<?php _e('Previous Page','rmcommon'); ?>" onclick="gsGallery.load(<?php echo $current_page-1; ?>,<?php echo $url; ?>,this); return false;"><?php _e('Previous','rmcommon'); ?></a>
	<?php endif; ?>
	
	<?php
		for($i=$start;$i<=$end;$i++):
			if ($i==$current_page):
	?>
		<span class="current_page"><?php echo $i; ?></span>
	<?php else: ?>
		<a href="#" title="<?php echo sprintf(__('Page %u','rmcommon'), $i); ?>" onclick="gsGallery.load(<?php echo $i; ?>,<?php echo $url; ?>,this); return false;"><?php echo $i; ?></a>
	<?php 
			endif;
	    endfor;
	?>
	
	<?php if($current_page<$total_pages): ?>
		<a href="#" title="<?php _e('Next Page','rmcommon'); ?>" onclick="gsGallery.load(<?php echo $current_page+1; ?>,<?php echo $url; ?>,this); return false;"><?php _e('Next','rmcommon'); ?></a>
	<?php endif; ?>
	
	<?php if($total_pages>$steps && $current_page<($total_pages-$steps)+2): ?>
		<a href="#" title="<?php _e('Last Page','rmcommon'); ?>" onclick="gsGallery.load(<?php echo $total_pages; ?>,<?php echo $url; ?>,this); return false;"><?php _e('Last','rmcommon'); ?></a>
	<?php endif; ?>
</div>