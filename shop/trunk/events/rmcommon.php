<?php
// $Id$
// --------------------------------------------------------------
// MiniShop 3
// Module for catalogs
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class ShopRmcommonPreload
{
    
    public function eventRmcommonLoadRightWidgets($widgets){
        global $xoopsModule;
        
        if (!isset($xoopsModule) || ($xoopsModule->getVar('dirname')!='system' && $xoopsModule->getVar('dirname')!='shop'))
            return $widgets;
        
        if (defined("RMCSUBLOCATION") && RMCSUBLOCATION=='new_product'){
            
            $action = rmc_server_var($_REQUEST, 'action', '');
            $edit = 0;
            if($action=='edit'){
                $edit = 1;
                $id = rmc_server_var($_REQUEST, 'id', 0);
                $product = new ShopProduct($id);
            }
            
            include_once '../include/widgets.php';
            $widgets[] = shop_widget_info();
            $widgets[] = shop_widget_image();
            
        }
        
        return $widgets;
    }

}
