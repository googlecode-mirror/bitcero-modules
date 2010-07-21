<?php
// $Id$
// --------------------------------------------------------------
// Rapid Docs
// Documentation system for Xoops.
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class DocsRmcommonPreload{
    
    public function eventRmcommonLoadRightWidgets($widgets){
        global $xoopsModule;
        
        if (!isset($xoopsModule) || ($xoopsModule->getVar('dirname')!='system' && $xoopsModule->getVar('dirname')!='docs'))
            return $widgets;
        
        if (defined("RMCSUBLOCATION") && RMCSUBLOCATION=='newresource'){
            include_once '../include/admin_widgets.php';
            $widgets[] = rd_widget_options();
            $widgets[] = rd_widget_references();
            $widgets[] = rd_widget_figures();
            
        }
        
        return $widgets;
    }
    
    // Plugin for TinyMCE
    public function eventRmcommonTinymcePluginLoading(){
        
        // EXM Image Manager
        ?>
        <?php $ret = parse_url($_SERVER['HTTP_REFERER']);
                   parse_str($ret['query'], $str); ?>
            ed.addCommand('mceRapidDocsReferences', function() {
                ed.windowManager.open({
                    file : '<?php echo XOOPS_URL; ?>/modules/docs/references.php?id=<?php echo $str['id']; ?>',
                    width : 600 + parseInt(ed.getLang('exmsystem.delta_width', 0)),
                    height : 600 + parseInt(ed.getLang('exmsystem.delta_height', 0)),
                    inline : 1,
                    title: '<?php _e('Notes & References','docs'); ?>',
                    maximizable: 'true'
                }, {
                    plugin_url : '<?php echo XOOPS_URL; ?>/modules/docs'
                });
            });
            
            ed.addCommand('mceRapidDocsFigures', function() {
                ed.windowManager.open({
                    file : '<?php echo XOOPS_URL; ?>/modules/docs/figures.php',
                    width : 600 + parseInt(ed.getLang('exmsystem.delta_width', 0)),
                    height : 600 + parseInt(ed.getLang('exmsystem.delta_height', 0)),
                    inline : 1,
                    title: '<?php _e('Figures','docs'); ?>',
                    maximizable: 'true'
                }, {
                    plugin_url : '<?php echo XOOPS_URL; ?>/modules/docs'
                });
            });
            
            // Register buttons
            ed.addButton('rd_refs', {
                title : '<?php _e('Insert notes & references','docs'); ?>',
                image : '<?php echo XOOPS_URL; ?>/modules/docs/images/notes_plugin.png',
                cmd : 'mceRapidDocsReferences'
            });
            
            // Register buttons
            ed.addButton('rd_figures', {
                title : '<?php _e('Insert figures','docs'); ?>',
                image : '<?php echo XOOPS_URL; ?>/modules/docs/images/figures_plugin.png',
                cmd : 'mceRapidDocsFigures'
            });
            
        <?php
    }
    
    // Plugins for XoopsCode Editor
    public function eventRmcommonLoadExmcodePlugins(){
    ?>
        x.add_plugin('docsrefs', {
            show: function(){
                
                x.popup({
                    width: 600,
                    height: 600,
                    title: 'Notes & References',
                    url: '<?php echo XOOPS_URL; ?>/modules/docs/references.php',
                    single: 1,
                    maximizable: 1
                });
                
            },
            check: function(s){
                
            }
        });
        
        x.add_plugin('docsfigs', {
            show: function(){
                
                x.popup({
                    width: 600,
                    height: 600,
                    title: 'Figures',
                    url: '<?php echo XOOPS_URL; ?>/modules/docs/figures.php',
                    single: 1,
                    maximizable: 1
                });
                
            },
            check: function(s){
                
            }
        });

        x.add_button('rdrefs',{
           name : 'rdrefs', 
           title : 'Add notes & references',
           alt : 'Notes & References',
           cmd : 'show',
           plugin : 'docsrefs',
           row: 'top',
           icon: '<?php echo XOOPS_URL; ?>/modules/docs/images/notes_plugin.png'
        });
        
        x.add_button('rdfigs',{
           name : 'rdfigs', 
           title : 'Add figures',
           alt : 'Add Figures',
           cmd : 'show',
           plugin : 'docsfigs',
           row: 'top',
           icon: '<?php echo XOOPS_URL; ?>/modules/docs/images/figures_plugin.png'
        });
    <?php
    }
    
    public function eventRmcommonExmcodePlugins($plugins){
        global $xoopsModule;
        if ($xoopsModule->dirname()!='docs') return $plugins;
        $plugins .= ',docsrefs,docsfigs';
        return $plugins;
    }
    
    public function eventRmcommonExmcodeButtons($buttons){
        global $xoopsModule;
        if ($xoopsModule->dirname()!='docs') return $buttons;
        $buttons .= ',rdrefs,rdfigs';
        return $buttons;
    }

}