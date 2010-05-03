<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<{$xoops_langcode}>" lang="<{$xoops_langcode}>">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<meta http-equiv="content-language" content="<?php echo _LANGCODE; ?>" />
<meta http-equiv="content-type" content="text/html; charset=<?php echo _CHARSET; ?>" />
<title><?php if($this->get_var('xoops_pagetitle')!=''): ?><?php echo $this->get_var('xoops_pagetitle'); ?> - <?php endif; ?><?php echo isset($xoopsModule) ? $xoopsModule->getInfo('name').' - ' : ''; ?><?php echo $xoopsConfig['sitename']; ?></title>
<meta name="author" content="BitC3R0 (i.bitcero@gmail.com)" />
<meta name="copyright" content="Red México" />
<meta name="generator" content="Red México Common Utilities" />
<?php

!defined('RMCLOCATION') ? define('RMCLOCATION', '') : true;

foreach ($this->tpl_scripts as $script){
	echo '<script type="'.$script['type'].'" src="'.$script['url'].'"></script>'."\n";
}
		
foreach ($this->tpl_styles as $style){
	echo '<link rel="stylesheet" type="text/css" media="'.$style['media'].'" href="'.$style['url'].'"'.($style['more']!=''?' '.$style['more']:'').' />'."\n";
}

foreach ($this->tpl_head as $head){
	echo $head."\n";
}
?>
<script type="text/javascript">
	$(document).ready(function(){
		if (navigator.userAgent.toLowerCase().indexOf('chrome')>0 || navigator.userAgent.toLowerCase().indexOf('safari')>0){
    		$("#rmc-center-content").css("overflow",'visible');
		}
	});
</script>
</head>
</body>
<div id="rm-header">
    <div class="right">
        <a href="<?php echo XOOPS_URL; ?>"><h1><span><?php _e('Visit Site','rmcommon'); ?></span><?php echo $xoopsConfig['sitename']; ?></h1></a><br />
        <?php _e('Welcome,','rmcommon'); ?> <a href="<?php echo XOOPS_URL; ?>/user.php"><?php echo $xoopsUser->getVar('uname'); ?></a> |
        <a href="<?php echo XOOPS_URL; ?>/user.php?op=logout"><?php _e('Logout','rmcommon'); ?></a> |
        <a href="http://redmexico.com.mx" target="_blank"><?php _e('Help','rmcommon'); ?></a>
    </div>
	<a href="<?php echo XOOPS_URL; ?>/admin.php"><img src="<?php echo $rm_theme_url; ?>/images/logo.png" alt="XOOPS" /></a>
</div>
<?php if($this->get_toolbar()): ?>
	<div id="rmc-toolbar">
        <?php if($this->help()): ?>
            <a href="<?php echo $this->help(); ?>" class="help_button" target="_blank">
                <span style="background-image: url(<?php echo RMTHEMEURL; ?>/images/help.png);"><?php _e('Help','rmcommon'); ?></span></a>
        <?php endif; ?>
		<?php foreach($this->get_toolbar() as $menu): ?>
		<a href="<?php echo $menu['link']; ?>"<?php echo $menu['location']==RMCLOCATION ? ' class = "selected"' : ''; ?>><span<?php if($menu['icon']): ?> style="background-image: url(<?php echo $menu['icon']; ?>); padding-left: 24px"<?php endif; ?>><?php echo $menu['title']; ?></span></a>
		<?php endforeach; ?>
	</div>
<?php endif; ?>
<div id="rmc-container">
	<!-- Right Widgets -->
	<?php if(count($right_widgets)>0): ?>
	<div id="rmc-right-widgets">
		<?php foreach($right_widgets as $widget): ?>
        <div class="rmc_widget_title"><span<?php echo isset($widget['icon']) && $widget['icon']!='' ? ' style="background-image: url('.$widget['icon'].'); padding-left: 26px;"' : ''; ?>><?php echo $widget['title']; ?></span></div>
        <div class="rmc_widget_content"><?php echo $widget['content']; ?></div>
        <?php endforeach; ?>
	</div>
	<?php endif; ?>
	<!-- // -->
    
    <!-- Left Widgets -->
    <?php if(count($left_widgets)>0 || $this->get_menus()): ?>
    <div id="rmc-left-widgets">
        <!-- Module Menu -->
        <?php if ($this->get_menus()): ?>
        <div class="rmc_widget_title"><span<?php echo $xoopsModule->getInfo('icon24') ? ' style="background-image: url('.XOOPS_URL.'/modules/'.$xoopsModule->dirname().'/'.$xoopsModule->getInfo('icon24').'); padding-left: 26px;"': ''; ?>><?php echo strlen($xoopsModule->getVar('name'))>20?substr($xoopsModule->getVar('name'), 0, 17).'...':$xoopsModule->getVar('name'); ?></span></div>
        <div class="rmc_widget_content mod_menu">
        <?php 
        foreach($this->get_menus() as $menu): 
            $wcounter++;
        ?>
            <div class="menu<?php if($menu['location']==RMCLOCATION): ?> selected<?php endif; ?>" id="menu-<?php echo $wcounter; ?>">
                <?php if($menu['options']): ?>
                    <span class="toggle" id="switch-<?php echo $wcounter; ?>">&nbsp;</span>
                <?php endif; ?>
                <a href="<?php echo $menu['link']; ?>" style="<?php echo $menu['icon']!='' ? "background-image: url(".$menu['icon']."); padding-left: 22px; width: 120px" : '' ?>"<?php if($menu['options']): ?> class="reduced"<?php endif; ?>><?php echo $menu['title']; ?></a>
            </div>
            <?php if($menu['options']): ?>
            <div class="submenu" id="container-<?php echo $wcounter; ?>" style="<?php if($menu['location']==RMCLOCATION): ?>display: block;<?php else: ?>display: none;<?php endif; ?>">
                <?php foreach($menu['options'] as $submenu): ?>
                <a href="<?php echo $submenu['link']; ?>"><?php echo $submenu['title']; ?></a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        <?php endforeach; ?>
        </div>
        <?php endif; ?>
        <!-- // -->
        <div class="other_widgets">
        <?php foreach($left_widgets as $widget): ?>
        <div class="rmc_widget_title"><span<?php echo isset($widget['icon']) && $widget['icon']!='' ? ' style="background-image: url('.$widget['icon'].'); padding-left: 26px;"' : ''; ?>><?php echo $widget['title']; ?></span></div>
        <div class="rmc_widget_content"><?php echo $widget['content']; ?></div>
        <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
    <!-- // -->
    
    <!-- Contenido -->
    <div id="rmc-center-content" class="<?php echo $left_widgets || $this->get_menus() ? 'reduce_left ' : ''; ?><?php echo $right_widgets ? 'reduce_right' : '' ?>">
        <?php foreach($rmc_messages as $message): ?>
            <div class="<?php if($message['level']): ?>errorMsg<?php else: ?>infoMsg<?php endif; ?>">
                <?php echo html_entity_decode($message['text']); ?>
            </div>
        <?php endforeach; ?>
        <?php echo $content; ?>
        
    </div>
    <!-- // -->
</div>
<div id="rmc-footer">
	<div class="by_redmexico">
		Theme powered by <a href="http://redmexico.com.mx">Red México</a>.<br />
		<a href="http://www.temasweb.com">TemasWeb.com</a> |
		<a href="http://www.frecuenciau.com">Frecuencia Universitaria</a> |
	</div>
	Powered by <a href="http://www.xoops.org"><?php echo XOOPS_VERSION; ?></a>.<br />
	Common Utilities by Red México
</div>
<?php if($xoopsConfig['debug_mode']==1): ?>
<div id="rmc-debug-output"><!--{xo-logger-output}--></div>
<?php endif; ?>
</html>
</body>