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

if(rmc_server_var($_POST, 'action', '')!=''){
    
    $settings = "<?php\n\nreturn array(\n'logo' => '".rmc_server_var($_POST, 'logo_url', RMCURL.'/themes/designia/images/logo.png')."',\n'scheme' => '".rmc_server_var($_POST, 'scheme', 'colors')."'\n);";
    file_put_contents(XOOPS_CACHE_PATH.'/designia.php', $settings);
    redirectMsg(RMCURL, __('Settings saved successfully!','designia'), RMMSG_SUCCESS);
    die();
    
}

RMTemplate::get()->add_theme_style('settings.css','designia');
RMTemplate::get()->add_local_script('colorpicker.js', 'rmcommon', 'include');
RMTemplate::get()->add_style('colorpicker.css', 'rmcommon');
RMTemplate::get()->add_theme_script('settings.js', 'designia');

RMTemplate::get()->assign('xoops_pagetitle', __('Designia Options','designia'));

$dConfig = include(XOOPS_CACHE_PATH.'/designia.php');

xoops_cp_header();

?>

<h1 class=rmc_titles><?php _e('Designia Theme Settings','designia'); ?></h1>

<div class="set_table">
    <div class=set_row>
        <div class="set_cell">
            <h3><?php _e('Basic Theme Options','designia'); ?></h3>
            
            <form name="edForm" method="post" action="<?php echo RMCURL; ?>/?designia=settings">
                <label class=opt_caption for="logo_url"><?php _e('Logo URL:','designia'); ?></label>
                <input type="text" class="single" name="logo_url" value="<?php echo $dConfig['logo']; ?>" id="logo-url" />
                <span class="opt_desc"><?php _e('Provide a URL for an image to be used as logo for theme.','designia'); ?></span>
                
                <label class=opt_caption><?php _e('Color scheme:','designia'); ?></label>
                <table class="screens">
                    <tr>
                <?php
                    $path = RMCPATH.'/themes/designia';
                    $handle = opendir($path.'/css');
                    while (false !== ($file = readdir($handle))) {
                        if (!is_file($path.'/css/' . $file) || substr($file, 0, 6)!='colors')
                            continue;
                            
                ?>
                <td align=center>
                    <label>
                        <img src="<?php echo RMCURL.'/themes/designia'; ?>/images/shots/<?php echo str_replace(".css", "", $file); ?>.jpg" />
                        <br />
                        <input type="radio" name="scheme" value="<?php echo $file; ?>"<?php echo $file==$dConfig['scheme'] ? ' checked="checked"' : ''; ?> /></label></td>
                <?php
                    
                    }
                    closedir($handle);
                ?>
                    </tr>
                </table>
                
                <input type="hidden" name="action" value="save_settings" />
                <input type="hidden" name="designia" value="settings" />
                <input type="submit" value="<?php _e('Save Changes','designia'); ?>" class="buttonGreen" />
            </form>
            </div>
        <div class="set_cell">
            
        </div>
    </div>
</div>

<?php
xoops_cp_footer();