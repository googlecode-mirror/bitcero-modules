<!DOCTYPE html>
<html>
    <head>
        <meta charset=utf-8>
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700' rel='stylesheet' type='text/css'>
        <?php
        
        !defined('RMCLOCATION') ? define('RMCLOCATION', '') : true;
        !defined('RMCSUBLOCATION') ? define('RMCSUBLOCATION', '') : true;
        
        foreach ($this->tpl_styles as $style){
            echo '<link rel="stylesheet" type="text/css" media="'.$style['media'].'" href="'.$style['url'].'"'.($style['more']!=''?' '.$style['more']:'').' />'."\n";
        }

        foreach ($this->tpl_scripts as $script){
            echo '<script type="'.$script['type'].'" src="'.$script['url'].'"></script>'."\n";
        }
        
        foreach ($this->tpl_head as $head){
            echo $head."\n";
        }
        
        include_once 'include/xoops_metas.php';
        ?>
        
        <title><?php if($this->get_var('xoops_pagetitle')!=''): ?><?php echo $this->get_var('xoops_pagetitle'); ?> - <?php endif; ?><?php echo isset($xoopsModule) ? $xoopsModule->getInfo('name').' - ' : ''; ?><?php echo $xoopsConfig['sitename']; ?></title>
        <link rel=stylesheet type="text/css" media=all href="<?php echo $rm_theme_url; ?>/css/menu.css" />
        <link rel=stylesheet type="text/css" media=all href="<?php echo $rm_theme_url; ?>/css/jquery.mCustomScrollbar.css" />
        <script type="text/javascript" src="<?php echo $rm_theme_url; ?>/js/hoverIntent.js"></script>
        <script type="text/javascript" src="<?php echo $rm_theme_url; ?>/js/superfish.js"></script>
        <script type="text/javascript" src="<?php echo $rm_theme_url; ?>/js/supersubs.js"></script>
        <script type="text/javascript" src="<?php echo $rm_theme_url; ?>/js/jquery.window.min.js"></script>
        <script type="text/javascript" src="<?php echo $rm_theme_url; ?>/js/designia.js"></script>
        <script type="text/javascript" src="<?php echo $rm_theme_url; ?>/js/jquery.easing.1.3.js"></script>
        <script type="text/javascript" src="<?php echo $rm_theme_url; ?>/js/jquery.mousewheel.min.js"></script>
        <script type="text/javascript" src="<?php echo $rm_theme_url; ?>/js/jquery.mousewheel.min.js"></script>
        <script type="text/javascript" src="<?php echo $rm_theme_url; ?>/js/jquery.tablesorter.min.js"></script>

        <script type="text/javascript">
            var designia_url = '<?php echo $rm_theme_url; ?>';
            $(document).ready(function(){
                $("ul.menu-menu").superfish();
                $("table.outer").addClass('tablesorter');
                $(".outer").tablesorter();
            });
        </script>
        
        <link rel=stylesheet type="text/css" media=all href="<?php echo $rm_theme_url; ?>/css/main.css" />
        <link rel=stylesheet type="text/css" media=all href="<?php echo $rm_theme_url; ?>/css/<?php echo $dConfig['scheme']; ?>" />
        <link rel=stylesheet type="text/css" media=all href="<?php echo $rm_theme_url; ?>/css/jquery.window.css" />

        <?php DesigniaFunctions::extra_headers(); ?>

    </head>
    <body>
        <nav id="des-vertical" class="dark_bg dark_clear_text">
            <ul>
                <li class=nav_item>
                    <a href="<?php echo RMCURL; ?>">
                    <img src="<?php echo $rm_theme_url; ?>/images/dashboard.png" alt="<?php _e('Dashboard','rmcommon'); ?>" />
                        <p><?php _e('Dashboard','rmcommon'); ?></p>
                    </a>
                </li>
                <li class=nav_item>
                    <a href="#" class="des-modules-nav">
                        <img src="<?php echo $rm_theme_url; ?>/images/modules.png" alt="<?php _e('Modules','rmcommon'); ?>" />
                        <p><?php _e('Modules','rmcommon'); ?></p>
                    </a>
                </li>
                <li class=nav_item>
                    <a href="<?php echo RMCURL; ?>/plugins.php" id="des-plugins-nav">
                        <img src="<?php echo $rm_theme_url; ?>/images/plugins.png" alt="<?php _e('Plugins','rmcommon'); ?>" />
                        <p><?php _e('Plugins','rmcommon'); ?></p>
                    </a>
                </li>
                <li class=nav_item>
                    <a href="<?php echo RMCURL; ?>/blocks.php" id="des-blocks-nav">
                        <img src="<?php echo $rm_theme_url; ?>/images/blocks.png" alt="<?php _e('Blocks','rmcommon'); ?>" />
                        <p><?php _e('Blocks','rmcommon'); ?></p>
                    </a>
                </li>
                <li class=nav_item>
                    <a href="<?php echo RMCURL; ?>/comments.php" id="des-comms-nav">
                        <img src="<?php echo $rm_theme_url; ?>/images/coms.png" alt="<?php _e('Comments','rmcommon'); ?>" />
                        <p><?php _e('Comments','rmcommon'); ?></p>
                    </a>
                </li>
                <li class=nav_item>
                    <a href="http://xoops.org" target="_blank">
                        <img src="<?php echo $rm_theme_url; ?>/images/xoops.png" alt="<?php _e('XOOPS','designia'); ?>" />
                        <p><?php _e('XOOPS','designia'); ?></p>
                    </a>
                </li>
                <?php RMEvents::get()->run_event('designia.get.nav.items'); ?>
            </ul>
        </nav>
        <div id="right-container">
            <header id="des-header" class="overlay_bg">
                <h1 class=des_logo><a href="<?php echo RMCURL; ?>" title="<?php echo $xoopsConfig['sitename']; ?>"><img src="<?php echo $dConfig['logo']; ?>" alt="<?php echo $xoopsConfig['sitename']; ?>" width=178 height=45 /></a></h1>
                <div class="header_user"></div>
            </header>
            <nav id="des-nav" class="dark_bg overlay_bg">
                <ul>
                    <li class=user>
                        <img src="http://www.gravatar.com/avatar/<?php echo md5($xoopsUser->getVar('email')); ?>?s=50&amp;d=<?php echo urlencode($rm_theme_url.'/images/profile.png'); ?>" alt="<?php echo $xoopsUser->getVar('name'); ?>" />
                        <div id="des-userinfo">
                            <p id="usr-name"><?php echo sprintf(__('Welcome back %s','designia'), $xoopsUser->getVar('name')); ?></p>
                            <p>
                                <a href="<?php echo XOOPS_URL; ?>" target="viewsite"><?php _e('View Site','designia'); ?></a>
                                <a href="<?php echo RMCURL; ?>/?designia=settings"><?php _e('Preferences','designia'); ?></a>
                                <a href="<?php echo XOOPS_URL; ?>"><?php _e('Log out','designia'); ?></a>
                            </p>
                        </div>
                    </li>
                    <li class=nav_item>
                        <a href="<?php echo RMCURL; ?>">
                            <img src="<?php echo $rm_theme_url; ?>/images/dashboard.png" alt="<?php _e('Dashboard','rmcommon'); ?>" />
                            <p><?php _e('Dashboard','rmcommon'); ?></p>
                        </a>
                    </li>
                    <li class=nav_item>
                        <a href="#" class="des-modules-nav">
                            <img src="<?php echo $rm_theme_url; ?>/images/modules.png" alt="<?php _e('Modules','rmcommon'); ?>" />
                            <p><?php _e('Modules','rmcommon'); ?></p>
                        </a>
                    </li>
                    <li class=nav_item>
                        <a href="<?php echo RMCURL; ?>/plugins.php" id="des-plugins-nav">
                            <img src="<?php echo $rm_theme_url; ?>/images/plugins.png" alt="<?php _e('Plugins','rmcommon'); ?>" />
                            <p><?php _e('Plugins','rmcommon'); ?></p>
                        </a>
                    </li>
                    <li class=nav_item>
                        <a href="<?php echo RMCURL; ?>/blocks.php" id="des-blocks-nav">
                            <img src="<?php echo $rm_theme_url; ?>/images/blocks.png" alt="<?php _e('Blocks','rmcommon'); ?>" />
                            <p><?php _e('Blocks','rmcommon'); ?></p>
                        </a>
                    </li>
                    <li class=nav_item>
                        <a href="<?php echo RMCURL; ?>/comments.php" id="des-comms-nav">
                            <img src="<?php echo $rm_theme_url; ?>/images/coms.png" alt="<?php _e('Comments','rmcommon'); ?>" />
                            <p><?php _e('Comments','rmcommon'); ?></p>
                        </a>
                    </li>
                    <li class=nav_item>
                        <a href="http://xoops.org" target="_blank">
                            <img src="<?php echo $rm_theme_url; ?>/images/xoops.png" alt="<?php _e('XOOPS','designia'); ?>" />
                            <p><?php _e('XOOPS','designia'); ?></p>
                        </a>
                    </li>
                    <?php RMEvents::get()->run_event('designia.get.nav.items'); ?>
                </ul>
            </nav>
            
            <div id="des-nav-modules">
                <?php include 'include/modules.php'; ?>
            </div>
            
            <?php if($this->get_menus()): ?>
            <nav id="des-menu-nav" class="overlay_bg">
                <ul class="menu-menu">
                    <li class=module_name>
                        <a style="background-image: url(<?php echo DesigniaFunctions::module_icon($xoopsModule->dirname(), '16'); ?>);" href="<?php echo XOOPS_URL; ?>/modules/<?php echo $xoopsModule->dirname(); ?>/<?php echo $xoopsModule->getInfo('adminindex'); ?>"><?php echo $xoopsModule->name(); ?></a>
                        <ul>
                            <?php foreach($this->get_menus() as $menu): ?>
                            <li class=nav_item>
                                <a href="<?php echo $menu['link']; ?>" style="background-image: url(<?php echo $menu['icon']; ?>);"><?php echo $menu['title']; ?></a>
                                <?php if($menu['options']): ?>
                                <ul>
                                <?php foreach($menu['options'] as $sub): ?>
                                    <li><a href="<?php echo $sub['link']; ?>"><?php echo $sub['title']; ?></a></li>
                                <?php endforeach; ?>
                                </ul>
                                <?php endif; ?>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <?php if($xoopsModule->dirname()!='system'): ?>
                    <li>
                        <a style="background-image: url(<?php echo RMTHEMEURL; ?>/images/system.png);" href="<?php echo XOOPS_URL; ?>/modules/system/"><?php _e('System','designia'); ?></a>
                        <ul>
                            <?php foreach($system_menu as $menu): ?>
                            <li class=nav_item>
                                <a href="<?php echo $menu['link']; ?>" style="background-image: url(<?php echo $menu['icon']; ?>);"><?php echo $menu['title']; ?></a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <?php endif; ?>
                    
                    <?php if($xoopsModule->dirname()!='rmcommon'): ?>
                    <li>
                        <a style="background-image: url(<?php echo DesigniaFunctions::module_icon('rmcommon', '16'); ?>);" href="<?php echo XOOPS_URL; ?>/modules/rmcommon/">Common Utilities</a>
                        <ul>
                            <?php foreach($rmcommon_menu as $menu): ?>
                            <li class=nav_item>
                                <a href="<?php echo $menu['link']; ?>" style="background-image: url(<?php echo $menu['icon']; ?>);"><?php echo $menu['title']; ?></a>
                                <?php if(isset($menu['options'])): ?>
                                <ul>
                                <?php foreach($menu['options'] as $sub): ?>
                                    <li><a href="<?php echo $sub['link']; ?>"><?php echo $sub['title']; ?></a></li>
                                <?php endforeach; ?>
                                </ul>
                                <?php endif; ?>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <?php endif; ?>
                    
                    <?php if(!empty($other_menu)): ?>
                    <?php foreach($other_menu as $name => $topmenu): ?>
                    <li>
                        <a style="background-image: url(<?php echo $topmenu['icon']; ?>);" href="<?php echo $topmenu['link']; ?>"><?php echo $topmenu['name']; ?></a>
                        <ul>
                            <?php foreach($topmenu['menus'] as $menu): ?>
                            <li class=nav_item>
                                <a href="<?php echo $menu['link']; ?>" style="background-image: url(<?php echo $menu['icon']; ?>);"><?php echo $menu['title']; ?></a>
                                <?php if(isset($menu['options'])): ?>
                                <ul>
                                <?php foreach($menu['options'] as $sub): ?>
                                    <li><a href="<?php echo $sub['link']; ?>"><?php echo $sub['title']; ?></a></li>
                                <?php endforeach; ?>
                                </ul>
                                <?php endif; ?>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <?php endforeach; ?>
                    <?php endif; ?>
                    
                    <li>
                        <a style="background-image: url(<?php echo DESIGNIA_URL; ?>/images/designia.png);" href="#"><?php _e('Designia','designia'); ?></a>
                        <ul>
                            <li class=nav_item>
                                <a href="<?php echo RMCURL; ?>/?designia=settings" style="background-image: url(<?php echo DESIGNIA_URL; ?>/images/colors.png);"><?php _e('Settings','designia'); ?></a>
                                <a href="#" id="designia-about" style="background-image: url(<?php echo DESIGNIA_URL; ?>/images/info.png);"><?php _e('About theme','designia'); ?></a>
                            </li>
                        </ul>
                    </li>
                    
                    <?php if($this->help()): ?>
                    <li>
                        <a href="#" style="background-image: url(<?php echo DESIGNIA_URL; ?>/images/help.png);"><?php _e('Help','designia'); ?></a>
                        <ul>
                            <?php foreach($this->help() as $help): ?>
                            <li class=nav_item>
                            <a href="<?php echo $help['link']; ?>" class="help_button rm_help_button" style="background-image: url(<?php echo DESIGNIA_URL; ?>/images/help.png);" target="_blank" title="<?php echo $help['caption']; ?>"><?php echo $help['caption']; ?></a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                    <?php endif; ?>
                    
                </ul>
                <div class=clearer></div>
            </nav>
            <?php endif; ?>
            
            <!-- System messages -->
            <?php foreach($rmc_messages as $message): ?>
            <div class="des_sys_message msgtype_<?php echo $message['level']; ?>"<?php if($message['level']>4 && $message['icon']!=''): ?> style="background-image: url(<?php echo $message['icon']; ?>);<?php endif; ?>>
                <span class="msg-close" title="<?php _e('Close message box','designia'); ?>"></span>
                <?php echo html_entity_decode($message['text']); ?>
            </div>
            <?php endforeach; ?>
            
            <!-- Toolbar -->
            <?php if($this->get_toolbar()): ?>
            <nav id="des-toolbar">
                <ul>
                <?php foreach($this->get_toolbar() as $menu): ?>
                    <li<?php echo $menu['location']==RMCLOCATION ? ' class = "selected"' : ''; ?>>
                        <a href="<?php echo $menu['link']; ?>"<?php if($menu['icon']): ?> style="background-image: url(<?php echo $menu['icon']; ?>); padding-left: 24px"<?php endif; ?>><?php echo $menu['title']; ?></a>
                    </li>
                <?php endforeach; ?>
                </ul>
            </nav>
            <?php endif; ?>
            
            <!-- The content -->
            <div id="des-content-wrapper">
                <div id="des-wrapper">
                    <div class="des-rwrapper">
                        <?php if($left_widgets): ?>
                        <div id="des-lblocks">
                            <?php foreach($left_widgets as $widget): ?>
                            <div class="des_widget_wrapper">
                                <div class="des_widget_title dark_bg overlay_bg dark_border dark_clear_text"><span<?php echo isset($widget['icon']) && $widget['icon']!='' ? ' style="background-image: url('.$widget['icon'].'); padding-left: 26px;"' : ''; ?>><?php echo $widget['title']; ?></span></div>
                                <div class="des_widget_content"><?php echo $widget['content']; ?></div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                        <div id="des-content">
                            <?php echo $content; ?>
                            <div class=clearer></div>
                        </div>
                        <?php if($right_widgets): ?>
                        <div id="des-rblocks">
                            <?php foreach($right_widgets as $widget): ?>
                            <div class="des_widget_wrapper">
                                <div class="des_widget_title dark_bg overlay_bg"><span<?php echo isset($widget['icon']) && $widget['icon']!='' ? ' style="background-image: url('.$widget['icon'].'); padding-left: 26px;"' : ''; ?>><?php echo $widget['title']; ?></span></div>
                                <div class="des_widget_content"><?php echo $widget['content']; ?></div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <!--// The content -->
        </div>
        
        <!-- Footer -->
        <div id="des-footer">
            <?php echo sprintf(__('Powered by %s.','designia'), '<a href="http://xoops.org">'.XOOPS_VERSION.'</a>'); ?>
            <?php echo sprintf(__('Reloaded by %s.','designia'), '<a href="http://www.redmexico.com.mx/w/common-utilities/">'.RMUtilities::get()->getVersion(true, 'rmcommon').'</a>'); ?>
            <br />
            <?php echo sprintf(__('Using %s.','designia'), '<strong>Designia Theme</strong>'); ?>
        </div>
        <!--// Footer -->
        
        <span id="xtoken"><?php echo $xoopsSecurity->createToken(); ?></span>
        <div class="width_maintainer">&nbsp;</div>
        <script>
            $(window).load(function() {
                $("#des-nav-modules").mCustomScrollbar("horizontal",400,"easeOutCirc",0.05,"fixed","yes","yes",10);
            });
        </script>
        <script type="text/javascript" src="<?php echo $rm_theme_url; ?>/js/jquery.mCustomScrollbar.js"></script>
    </body>
</html>