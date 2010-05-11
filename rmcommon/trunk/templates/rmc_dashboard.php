<h1 class="rmc_titles"><?php _e('Dashboard','rmcommon'); ?></h1>
<div class="rmc_widgets_container">
    <!-- Right widgets -->
    <div id="rmc-central-right-widgets">
        <div class="outer">
            <div class="th"><img src="images/loading_2.gif" alt="" class="loading" id="loading-mods" /> <?php _e('Installed Modules','rmcommon'); ?></div>
            <div class="even mods_coint">
                <div id="ajax-mods-list">
                
                </div>
                <span class="description">
                    <?php _e('If you wish to manage or install new modules please go to Modules Management.','rmcommon'); ?><br />
                    <a href="<?php echo XOOPS_URL; ?>/modules/system/admin.php?fct=modulesadmin"><?php _e('Modules management', 'rmcommon'); ?></a>
                </span>
            </div>
        </div>
        
        <!-- Available Modules -->
        <div class="outer">
            <div class="th"><?php _e('Available Modules','rmcommon'); ?></div>
            <?php foreach($available_mods as $module): ?>
            <div class="<?php echo tpl_cycle("even,odd"); ?>">
                <span class="modimg" style="background: url(../<?php echo $module->getInfo('dirname'); ?>/<?php echo $module->getInfo('image'); ?>) no-repeat center;">&nbsp;</span>
                <strong><?php echo $module->getInfo('name'); ?></strong><br />
                <span class="moddesc"><?php echo $module->getInfo('description'); ?></span><br />
                <a href="<?php echo XOOPS_URL; ?>/modules/system/admin.php?fct=modulesadmin&op=install&module=<?php echo $module->getInfo('dirname'); ?>"><?php _e('Install', 'rmcommon'); ?></a>
            </div>
            <?php endforeach; ?>
            <span class="description">
                <?php _e('If you wish to manage or install new modules please go to Modules Management.','rmcommon'); ?><br />
                <a href="<?php echo XOOPS_URL; ?>/modules/system/admin.php?fct=modulesadmin"><?php _e('Modules management', 'rmcommon'); ?></a>
            </span>
        </div>
        <!-- End available modules -->
        <?php RMEvents::get()->run_event('rmcommon.dashboard.right.widgets'); ?>
    </div>
    <!-- / End right widgets -->
    
    <!-- Left widgets -->
    <div id="rmc-central-left-widgets">
        <div class="outer">
            <div class="th"><?php _e('System Tools','rmcommon'); ?></div>
            <div class="even system_tools">
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr class="even">
                        <td width="50%">
                            <a style="background-image: url(images/configure.png);" href="<?php echo XOOPS_URL; ?>/modules/system/admin.php?fct=preferences&op=showmod&mod=<?php echo $xoopsModule->mid(); ?>"><?php _e('Configure Common Utilities','rmcommon'); ?></a>
                        </td>
                        <td>
                            <a style="background-image: url(images/images.png);" href="images.php"><?php _e('Images Manager','rmcommon'); ?></a>
                        </td>
                    </tr>
                    <tr class="odd">
                        <td width="50%">
                            <a style="background-image: url(images/comments.png);" href="comments.php"><?php _e('Comments Management','rmcommon'); ?></a>
                        </td>
                        <td>
                            <a style="background-image: url(images/plugin.png);" href="plugins.php"><?php _e('Plugins Management','rmcommon'); ?></a>
                        </td>
                    </tr>
                    <tr class="even">
                        <td width="50%">
                            <a style="background-image: url(images/modules.png);" href="../system/admin.php?fct=modulesadmin"><?php _e('XOOPS Modules','rmcommon'); ?></a>
                        </td>
                        <td>
                            <a style="background-image: url(images/users.png);" href="../system/admin.php?fct=users"><?php _e('Users Management','rmcommon'); ?></a>
                        </td>
                    </tr>
                    <?php 
                        $system_tools = RMEvents::get()->run_event('rmcommon.get.system.tools', array());
                        $i = 1;
                    ?>
                    <tr class="odd">
                    <?php foreach ($system_tools as $tool): ?>
                    <?php if($i>2): ?>
                        </tr><tr class="<?php echo tpl_cycle('even,odd'); ?>">
                        <?php $i=1; ?>
                    <?php endif; ?>
                        <td><a href="<?php echo $tool['link']; ?>" style="background-image: url(<?php echo $tool['icon']; ?>);"><?php echo $tool['caption']; ?></td>
                    <?php $i++; endforeach; ?>
                    </tr>
                </table>
            </div>
        </div>
        
        <!-- Recent news -->
        <div class="outer">
            <div class="th"><img src="images/loading_2.gif" alt="" class="loading" id="loading-news" /> <?php _e('Recent News','rmcommon'); ?></div>
            <div class="even" id="rmc-recent-news">
            
            </div>
        </div>
        <!-- End recent news -->
        
        <?php RMEvents::get()->run_event('rmcommon.dashboard.left.widgets'); ?>
        
    </div>
    <!-- End left widgets -->
</div>