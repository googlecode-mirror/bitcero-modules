<h1 class="rmc_titles"><?php echo sprintf(__('Install %s','rmcommon'), $module->getInfo('name')); ?></h1>

<div class="descriptions"><?php echo $module->getInfo('description'); ?></div>
<div class="descriptions"><?php _e('This module will make next changes in Xoops system. Please review in a detailed way all them in order to decide if you really wish to install this module','rmcommon'); ?></div>

<div class="mod_preinstall_container">

    <div class="left">
        <div class="outer">
            <div class="th"><?php _e('Moduel Templates','rmcommon'); ?></div>
            <?php foreach($module->getInfo('templates') as $tpl): ?>
            <div class="<?php echo tpl_cycle("even,odd"); ?>">
                <?php echo $tpl['file']; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <div class="left">
        <div class="outer">
            <div class="th"><?php _e('Database Tables','rmcommon'); ?></div>
            <?php foreach($module->getInfo('tables') as $table): ?>
            <div class="<?php echo tpl_cycle("even,odd"); ?>">
                <?php echo $table; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    
</div>