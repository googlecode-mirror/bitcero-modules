<h1 class="rmc_titles dt_titles"><span style="background-position: left -32px;">&nbsp;</span><?php echo $the_form['title']; ?></h1>

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
                    <label for="version"><?php _e('Version','dtransport'); ?></label>
                    <input type="text" name="version" id="version" value="<?php echo $edit ? $sw->version() : ''; ?>" size="20" class="required fullwidth" />
                    <span class="description"><?php _e('Indicate the current version of this item.','dtransport'); ?></span>
                </div>
            </div>
            <div class="dt_cell">
                <div class="item">
                    <label for="limits"><?php _e('Downloads limit per user','dtransport'); ?></label>
                    <input type="text" name="limits" id="limits" value="<?php echo $edit ? $sw->limits() : ''; ?>" size="20" class="required fullwidth" />
                    <span class="description"><?php _e('Users could download this item only this times. Leave 0 for unlimited times.','dtransport'); ?></span>
                </div>
            </div>
            <div class="dt_cell">
                <div class="item">
                    <label for="siterate"><?php echo _e('Site Rating','dtransport'); ?></label>
                    <select name="siterate" id="siterate" class="required fullwidth">
                        <?php for($i=0;$i<=10;$i++): ?>
                        <option value="<?php echo $i; ?>"<?php echo $sw->rate()==$i?' selected="selected"':''; ?>><?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="dt_row">
            <div class="dt_cell">
                <div class="item">
                    <label for="category"><?php echo _e('Categories','dtransport'); ?></label>
                    <select name="category" id="category" class="required fullwidth" multiple="multiple">
                        <?php foreach($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="dt_cell">
            
            </div>
            <div class="dt_cell">
            
            </div>
        </div>
        
    </div>

</form>
</div>