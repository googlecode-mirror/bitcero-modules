<h1 class="rmc_titles dt_titles"><span style="background-position: left -32px;">&nbsp;</span><?php echo $location; ?></h1>

<div class="descriptions">
    <?php _e('Use next fields to create or change de information of the download item.','dtransport'); ?>
</div>

<div class="dt_form">
<form name="frmItems" id="frm-items" method="post" action="items.php">
    <div class="item">
        <label for="name"><?php _e('Download name','dtransport'); ?></label>
        <input type="text" name="name" id="name" value="<?php echo $edit ? $sw->name() : ''; ?>" size="50" class="required fullwidth title" />
        <span class="description"><?php _e('Specify a name that identifies this download. This name must be unique and different of other downloads.','dtransport'); ?></span>
    </div>
    
    <div class="item">
        <label for="shortdesc"><?php _e('Short description','dtransport'); ?></label>
        <textarea cols="50" rows="3" name="shotdesc" id="shortdesc" class="required fullwidth"><?php echo $edit?$sw->shortdesc('e'):''; ?></textarea>
        <span class="description"><?php _e('This is a small description that will be used as an advance of the item.','dtransport'); ?></span>
    </div>
    
    <div class="item">
        <label for="desc"><?php _e('Full description','dtransport'); ?></label>
        <?php 
            echo $ed->render();
        ?>
        <span class="description"><?php _e('This is a small description that will be used as an advance of the item.','dtransport'); ?></span>
    </div>
    
    <div class="dt_table">
        
        
        <div class="dt_row">
            <div class="dt_cell">
                <div class="item">
                    <label><?php _e('Categories','dtransport'); ?></label>
                    <div class="dt_el_list">
                        <ul>
                        <?php foreach($categories as $cat): ?>
                            <li style="padding-left: <?php echo ($cat['indent']*10); ?>px;<?php if($cat['indent']==0): ?> color: #333;<?php endif; ?>"><label><input type="checkbox" name="catids[]" id="cat-id-<?php echo $cat['id']; ?>"<?php echo $cat['selected']; ?> /> <?php echo $cat['name']; ?></label></li>
                        <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="description"><?php _e('Select the categories that you want to assign to this item.','dtransport'); ?></div>
                </div>
            </div>
            <div class="dt_cell">
                <div class="item">
                    <label><?php echo _e('Licences','dtransport'); ?></label>
                    <div class="dt_el_list">
                    <ul>
                    <li><label><input type="checkbox" name="lics[]" id="lic-0" value="0" /> <?php _e('Other license','dtransport'); ?></label></li>
                    <?php foreach($lics as $lic): ?>
                    <li><label><input type="checkbox" name="lics[]" id="lic-<?php echo $lic['id']; ?>" value="<?php echo $lic['id']; ?>" /> <?php echo $lic['name']; ?></label></li>
                    <?php endforeach; ?>
                    </ul>
                    </div>
                    <div class="description"><?php _e('Select the licenses that you want to assign to this item.','dtransport'); ?></div>
                </div>
            </div>
        </div>
        
        <div class="dt_row">
            <div class="dt_cell">
                <div class="item">
                    <label><?php echo _e('Platforms','dtransport'); ?></label>
                    <div class="dt_el_list">
                    <ul>
                    <li><label><input type="checkbox" name="platforms[]" id="os-0" value="0" /> <?php _e('Other platform','dtransport'); ?></label></li>
                    <?php foreach($oss as $os): ?>
                    <li><label><input type="checkbox" name="platforms[]" id="lic-<?php echo $os['id']; ?>" value="<?php echo $os['id']; ?>" /> <?php echo $os['name']; ?></label></li>
                    <?php endforeach; ?>
                    </ul>
                    </div>
                    <div class="description"><?php _e('Select that platforms over this item can be used.','dtransport'); ?></div>
                </div>
            </div>
            <div class="dt_cell">
                <div class="item">
                    <label><?php _e('Allowed groups','dtransport'); ?></label>
                    <div class="dt_el_list">
                        <?php echo $groups; ?>
                    </div>
                    <div class="description"><?php _e('Only users that belong to selected groups, could donwload this item.','dtransport'); ?></div>
                </div>
            </div>
        </div>        
    </div>
    
    <div class="item">
        <label><?php _e('Download tags','dtransport'); ?></label>
        <div class="dt_el_list">
            Hola
        </div>
    </div>
    <input type="hidden" name="action" value="<?php echo $edit ? ($type=='edit' ? 'savewait' : 'saveedit') : 'save'; ?>" />
    <input type="hidden" name="id" value="<?php echo $id; ?>" />
    <input type="hidden" name="page" value="<?php echo $page; ?>" />
    <input type="hidden" name="search" value="<?php echo $search; ?>" />
    <input type="hidden" name="sort" value="<?php echo $sort; ?>" />
    <input type="hidden" name="mode" value="<?php echo $mode; ?>" />
    <input type="hidden" name="cat" value="<?php echo $catid; ?>" />
    <input type="hidden" name="type" value="<?php echo $type; ?>" />
    <?php echo $xoopsSecurity->getTokenHTML(); ?>
</form>
</div>