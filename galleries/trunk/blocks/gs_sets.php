<?php
// $Id$
// --------------------------------------------------------------
// MyGalleries
// Module for advanced image galleries management
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

function gs_sets_show($options){
    global $xoopsUser, $xoopsModuleConfig;
    
    $db = XoopsDatabaseFactory::getDatabaseConnection();
    $wo = '';
    $tsets = $db->prefix("gs_sets");
    $tfriends = $db->prefix("gs_friends");
    $mc = RMUtilities::module_config('galleries');
    
    $format = $mc['set_format_values'];
    $crop = $format[0]; // 0 = Redimensionar, 1 = Cortar
    $width = $format[1];
    $height = $format[2];
    
    if ($xoopsUser) $wo = "$tsets.owner='".$xoopsUser->uid()."' OR";
    
    $sql = "SELECT * FROM $tsets WHERE owner='1' OR public='2' ORDER BY `date` DESC LIMIT 0,$options[0]";
    $result = $db->query($sql);
    
    include_once XOOPS_ROOT_PATH.'/modules/galleries/class/gsset.class.php';
    include_once XOOPS_ROOT_PATH.'/modules/galleries/class/gsuser.class.php';
    include_once XOOPS_ROOT_PATH.'/modules/galleries/class/gsimage.class.php';
    include_once XOOPS_ROOT_PATH.'/modules/galleries/class/gsfunctions.class.php';
    
    $block = array();
    $users = array();
    $tf = new RMTimeFormatter(0, '%T% %d%, %Y%');
    
    while ($row = $db->fetchArray($result)){
        $rtn = array();
        $set = new GSSet();
        $set->assignVars($row);   
        
        if (!isset($users[$set->owner()])) $users[$set->owner()] = new GSUser($set->owner(), 1);
        
        // Si se ha seleccionado la opción para mostrar imágenes entonces...
        if ($options[1]){
            //Obtenemos una imagen del album
            $sql = "SELECT b.* FROM ".$db->prefix('gs_setsimages')." a, ".$db->prefix('gs_images')." b WHERE";
            $sql.= " a.id_set='".$set->id()."' AND b.id_image=a.id_image AND b.public=2 AND b.owner='".$set->owner()."' ORDER BY b.created DESC LIMIT 0,1" ;

            $resimg = $db->query($sql);
            if ($db->getRowsNum($resimg)>0){
                $rowimg = $db->fetchArray($resimg);
                $img = new GSImage();
                $img->assignVars($rowimg);
                $urlimg = $users[$set->owner()]->filesURL().'/'.($mc['set_format_mode'] ? 'formats/set_' : 'ths/').$img->image();
                
                // Conversion de los formatos
                if (!$img->setFormat() && $mc['set_format_mode']){
                    GSFunctions::resizeImage($crop, $users[$set->owner()]->filesPath().'/'.$img->image(),$users[$set->owner()]->filesPath().'/formats/set_'.$img->image(), $width, $height);
                    $img->setSetFormat(1, 1);
                }
            } else {
                $urlimg = '';
            }
            
            $rtn['img'] = $urlimg;
            
            
        }
        
        $rtn['id'] = $set->id();
        $rtn['title'] = $set->title();
        $rtn['owner'] = $set->owner();
        $rtn['link'] = $users[$set->owner()]->userURL().($mc['urlmode'] ? 'set/'.$set->id().'/' : '&amp;set='.$set->id());
        if ($options[2]){
            $rtn['date'] = $tf->format($set->date());
            $rtn['pics'] = $set->pics();
            $rtn['uname'] = $set->uname();
            $rtn['linkuser'] = $users[$set->owner()]->userURL();
        }
        
        $block['sets'][] = $rtn;
        
    }
    
    $block['showimg'] = $options[1];
    $block['showinfo'] = $options[2];
    $block['item_width'] = $options[3];
    
    RMTemplate::get()->add_xoops_style('blocks.css', 'galleries');
    RMTemplate::get()->add_local_script('blocks.js','galleries');
    
    return $block;
    
}

function gs_sets_edit($options){
    
    $form =  '</td></tr>';
    $form .= '<tr class="head"><td colspan="2">'.__('Albums block options','galleries').'</td></tr>';
    $form .= '<tr><td class="head">'.__('Number of albums','galleries').'</td><td class="odd">';
    $form .= '<input type="text" name="options[0]" value="'.$options[0].'" size="5" /></td></tr>';
    $form .= '<tr><td class="head">'.__('Show picture','galleries').'</td><td class="odd">';
    $form .= '<label><input type="radio" name="options[1]" value="1"'.($options[1]==1?' checked="checked"':'').' />'.__('Yes','galleries').'</label>';
    $form .= '<label><input type="radio" name="options[1]" value="0"'.($options[1]==0?' checked="checked"':'').' />'.__('No','galleries').'</label></td></tr>';
    $form .= '<tr><td class="head">'.__('Show information','galleries').'</td><td class="odd">';
    $form .= '<label><input type="radio" name="options[2]" value="1"'.($options[2]==1?' checked="checked"':'').' />'.__('Yes','galleries').'</label>';
    $form .= '<label><input type="radio" name="options[2]" value="0"'.($options[2]==0?' checked="checked"':'').' />'.__('No','galleries').'</label></td></tr>';
    $form .= '<tr><td class="head">'.__('Item width:','galleries').'</td><td class="odd"><input type="text" name="options[3]" size="5" value="'.$options[3].'" />';
    $form .= '<tr class="head"><td colspan="2" style="font-size: 1px;">&nbsp;';
    
    return $form;
       
}
