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
            
            $widgets[] = dt_widget_categories();
            $widgets[] = dt_widget_licences();
            
        }
        
        return $widgets;
    }
}