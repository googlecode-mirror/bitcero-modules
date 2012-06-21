<?php
// $Id$
// --------------------------------------------------------------
// D-Transport
// Manage download files in XOOPS
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class DtransportRmcommonPreload
{
    public function eventRmcommonLoadRightWidgets($widgets){
        global $xoopsModule;
        
        if (!isset($xoopsModule) || ($xoopsModule->getVar('dirname')!='system' && $xoopsModule->getVar('dirname')!='dtransport'))
            return $widgets;
        
        if (defined("RMCSUBLOCATION") && RMCSUBLOCATION=='newitem'){
            include_once '../widgets/dt_items.php';
            
            $action = rmc_server_var($_GET, 'action', '');
            $edit = $action=='edit'?1:0;

            $widgets[] = dt_widget_information($edit);
            $widgets[] = dt_widget_defimg($edit);
            $widgets[] = dt_widget_options($edit);
            //$widgets[] = dt_widget_alert($edit);
            //$widgets[] = dt_widget_credits($edit);
            
            // Other widgets
            $widgets = RMEvents::get()->run_event('dtransport.load.items.widgets', $widgets, $action, $edit);
            
        }
        
        return $widgets;
    }
}