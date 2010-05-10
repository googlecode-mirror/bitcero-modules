<h1 class="rmc_titles"><?php echo sprintf(__('Install %s','rmcommon'), $module->getInfo('name')); ?></h1>

<div class="descriptions"><?php echo $module->getInfo('description'); ?></div>
<div class="descriptions"><?php _e('This module will make next changes in Xoops system. Please review in a detailed way all them in order to decide if you really wish to install this module','rmcommon'); ?></div>

<div class="mod_preinstall_container">

    <div class="left">
        <div class="outer">
            <?php if($module->getInfo('templates')): ?>
            <div class="th"><a href="javascript:;" id="down-tpls">&nbsp;</a><?php _e('Module Templates','rmcommon'); ?></div>
            <div id="tpls-container" class="container_hidden">
            <?php foreach($module->getInfo('templates') as $tpl): ?>
            <div class="<?php echo tpl_cycle("even,odd"); ?>">
                <?php echo $tpl['file']; ?>
            </div>
            <?php endforeach; ?>
            </div>
            <?php endif; ?>
            
            <?php if($module->getInfo('tables')): ?>
            <div class="th"><a href="javascript:;" id="down-tables">&nbsp;</a><?php _e('Database Tables','rmcommon'); ?></div>
            <div id="tables-container" class="container_hidden">
            <?php foreach($module->getInfo('tables') as $table): ?>
            <div class="<?php echo tpl_cycle("even,odd"); ?>">
                <?php echo $table; ?>
            </div>
            <?php endforeach; ?>
            </div>
            <?php endif; ?>
            
            <?php if($module->getInfo('config')): ?>
            <div class="th"><a href="javascript:;" id="down-configs">&nbsp;</a><?php _e('Option settings to insert','rmcommon'); ?></div>
            <div id="configs-container" class="container_hidden">
            <?php foreach($module->getInfo('config') as $item): ?>
            <div class="<?php echo tpl_cycle("even,odd"); ?>">
                <strong><?php echo defined($item['title']) ? constant($item['title']) : $item['title']; ?></strong><br />
                <?php if($item['description']!=''): ?><span class="descriptions"><?php echo defined($item['description']) ? constant($item['description']) : $item['description']; ?></span><?php endif; ?>
            </div>
            <?php endforeach; ?>
            </div>
            <?php endif; ?>
            
            <?php if($module->getInfo('blocks')): ?>
            <div class="th"><a href="javascript:;" id="down-blocks">&nbsp;</a><?php _e('Bloks to insert','rmcommon'); ?></div>
            <div id="bloks-container" class="container_hidden">
            <?php foreach($module->getInfo('bloks') as $item): ?>
            <div class="<?php echo tpl_cycle("even,odd"); ?>">
                <strong><?php echo defined($item['name']) ? constant($item['name']) : $item['name']; ?></strong><br />
                <?php if($item['description']!=''): ?><span class="descriptions"><?php echo defined($item['description']) ? constant($item['description']) : $item['description']; ?></span><?php endif; ?>
            </div>
            <?php endforeach; ?>
            </div>
            <?php endif; ?>
            
        </div>
    </div>
    
    <div class="left">
        <h2><?php echo sprintf(__('%s Details','rmcommon'), $module->getInfo('name')); ?></h2>
        <div class="mod_data_container">
            <table cellpadding="0" cellspacing="0">
                <tr>
                    <td rowspan="3"><img src="<?php echo XOOPS_URL; ?>/modules/<?php echo $module->getInfo('dirname'); ?>/<?php echo $module->getInfo('image'); ?>" alt="<?php echo $module->getInfo('name'); ?>" /></td>
                </tr>
            </table>
        </div>
    </div>
    
</div>