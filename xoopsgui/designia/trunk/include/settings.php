<?php
// $Id$
// --------------------------------------------------------------
// Designia v1.0
// Theme for Common Utilities 2
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

global $xoopsUser, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $rmc_config;

include XOOPS_ROOT_PATH.'/include/cp_header.php';
require_once XOOPS_ROOT_PATH.'/modules/rmcommon/admin_loader.php';

RMTemplate::get()->add_theme_style('settings.css','designia');
RMTemplate::get()->add_local_script('colorpicker.js', 'rmcommon', 'include');
RMTemplate::get()->add_style('colorpicker.css', 'rmcommon');
RMTemplate::get()->add_theme_script('settings.js', 'designia');

$dConfig = include(XOOPS_CACHE_PATH.'/designia.php');

xoops_cp_header();

?>

<h1 class=rmc_titles><?php _e('Designia Theme Settings','designia'); ?></h1>

<div class="set_table">
    <div class=set_row>
        <div class="set_cell">
            <h3><?php _e('Basic Theme Options','designia'); ?></h3>
            
            <label class=opt_caption><?php _e('Logo URL:','designia'); ?></label>
            <input type="text" class="single" name="logo_url" value="<?php echo $dConfig['logo']; ?>" id="logo-url" />
            <span class="opt_desc"><?php _e('Provide a URL for an image to be used as logo for theme.','designia'); ?></span>
            
            <div class="set_table">
                <div class="set_row">
                    <div class="set_cell">
                        <label class="opt_caption"><?php _e('Logo background:','designia'); ?></label>
                        <span class="colorshow" id="logo-bg-color">&nbsp;</span>
                        <input type=text name=logo_bg value="<?php echo $dConfig['logobg']; ?>" id="logo-bg" class="selector" size="6" />
                    </div>
                    <div class="set_cell">
                        <label class="opt_caption"><?php _e('Top bar color:','designia'); ?></label>
                        <span class="colorshow" id="topbar-bg-color">&nbsp;</span>
                        <input type=text name=topbar_bg value="<?php echo $dConfig['topbar']; ?>" id="topbar-bg" class="selector" size="6" />
                    </div>
                    <div class="set_cell">
                        <label class="opt_caption"><?php _e('Menu bar color:','designia'); ?></label>
                        <span class="colorshow" id="menubar-bg-color">&nbsp;</span>
                        <input type=text name=menubar_bg value="<?php echo $dConfig['menubar']; ?>" id="menubar-bg" class="selector" size="6" />
                    </div>
                </div>
            </div>            
        </div>
        <div class="set_cell">
            Hola
        </div>
    </div>
</div>

<?php
xoops_cp_footer();