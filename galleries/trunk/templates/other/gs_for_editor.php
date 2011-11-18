<div id="mg-container" class="container">
    
    <div id="gs-tabs">
        <ul>
            <li class="selected"><a href="#" class="insert-gals">Galleries</a></li>
            <li><a href="#" class="insert-imgs">Pictures</a></li>
        </ul>
    </div>
    <div id="gs-tab-content">
        
        <div id="tab-gals" class="tab">
            <?php echo $xoopsSecurity->getTokenHTML(); ?>
        </div>
        
        <div id="tab-imgs" class="tab">
            <?php echo $xoopsSecurity->getTokenHTML(); ?>
        </div>
        
    </div>
    <div id="ggoptions">
        <?php _e('Pictures x page:','galleries'); ?> <input type="textbox" size="3" value="12" name="xpage" class="g-xpage" />
        <?php _e('Same page:','galleries'); ?> <input type="checkbox" name="spage" class="g-spage" value="1" checked="checked" />
        <?php _e('Short URL:','galleries'); ?> <input type="checkbox" name="surl" class="g-surl" value="1" />
        <input type="button" class="g-insert" value="<?php _e('Insert Gallery','galleries'); ?>" />
    </div>
</div>