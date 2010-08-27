<div id="rd-header">
    <a href="<?php echo RDURL; ?>"><?php echo $xoopsModule->name(); ?></a> |
    <a href="<?php echo RDFunctions::make_link('explore', array('by'=>'recent')); ?>"><?php _e('Recent Resources','docs'); ?></a> |
    <a href="<?php echo RDFunctions::make_link('explore', array('by'=>'top')); ?>"><?php _e('Top Resources','docs'); ?></a>
    <div class="right">
        <form name="frmsearch" method="get" action="<?php echo RDFunctions::make_link('search'); ?>">
            <input type="text" name="keyword" size="20" />
            <input type="submit" value="<?php _e('Search','docs'); ?>" />
        </form>
    </div>
</div>
