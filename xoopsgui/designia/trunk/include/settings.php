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
RMTemplate::get()->add_theme_script('edit_area/edit_area_full.js','designia');

RMTemplate::get()->assign('xoops_pagetitle', __('Designia Options','designia'));

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
            
            <h2><?php _e('Edit CSS styles','designia'); ?></h2>
            <form name="edForm" method="post" action="<?php echo RMCURL; ?>">
                <textarea id="editor" rows=5 cols=45><?php include XOOPS_CACHE_PATH.'/designia.css'; ?></textarea>
                <input type="hidden" name="action" value="save_settings" />
                <input type="hidden" name="designia" value="settings" />
                <input type="submit" value="<?php _e('Guardar Cambios','designia'); ?>" class="buttonGreen" />
            </form>
            </div>
        <div class="set_cell">
            
        </div>
    </div>
</div>

<?php
xoops_cp_footer();