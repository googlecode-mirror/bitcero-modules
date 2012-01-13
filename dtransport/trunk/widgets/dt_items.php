<?php
// $Id$
// --------------------------------------------------------------
// D-Transport
// Manage download files in XOOPS
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/**
* Este widget muestra las categorias disponibles y permite
* agregar la descarga a una o varias de ellas
*/
function dt_widget_categories(){
    
    $widget['title'] = __('Categories','dtransport');
    $widget['icon'] = '../images/categories.png';
    
    //Lista de categorías
    $categos = array();
    DTFunctions::getCategos($categos, 0, 0, array(), false);
    foreach ($categos as $row){
        $cat = new DTCategory();
        $cat->assignVars($row);
        $categories[] = array(
                    'id'=>$cat->id(),
                    'name'=>$cat->name(),
                    'parent'=>$cat->parent(),
                    'active'=>$cat->active(),
                    'description' => $cat->desc(),
                    'indent'=>$row['jumps']
                );    
    }
    unset($categos);
    ob_start();
    ?>
    <form name="frmCats" id="frm-categories" method="post" action="items.php">
    <div class="description"><?php _e('Select the categories that you want to assign to this item.','dtransport'); ?></div>
    <div class="dt_el_list">
        <ul>
        <?php foreach($categories as $cat): ?>
            <li style="padding-left: <?php echo ($cat['indent']*10); ?>px;<?php if($cat['indent']==0): ?> font-weight: bold;<?php endif; ?>"><label><input type="checkbox" name="catids[]" id="cat-id-<?php echo $cat['id']; ?>"<?php echo $cat['selected']; ?> /> <?php echo $cat['name']; ?></label></li>
        <?php endforeach; ?>
        </ul>
    </div>
    </form>
    <?php
    $widget['content'] = ob_get_clean();
    return $widget;
    
}


/**
* Este widget muestra la lista de licencias disponibles para
* ser asignadas a una descarga
*/
function dt_widget_licences(){
    
    $widget['title'] = __('Licences','dtransport');
    $widget['icon'] = '../images/license.png';
    
    $db = XoopsDatabaseFactory::getDatabaseConnection();
    $sql="SELECT * FROM ".$db->prefix('dtrans_licences');
    $result=$db->queryF($sql);
        
    ob_start();
    ?>
    <form name="frmLics" id="frm-lics" method="post" action="items.php">
    <div class="dt_el_list">
    <ul>
    <li><label><input type="checkbox" name="lics[]" id="lic-0" value="0" /> <?php _e('Other license','dtransport'); ?></label></li>
    <?php
    while ($row=$db->fetchArray($result)){
        $lic = new DTLicense();
        $lic->assignVars($row);
    ?>
    <li><label><input type="checkbox" name="lics[]" id="lic-<?php echo $lic->id(); ?>" value="<?php echo $lic->id(); ?>" /> <?php echo $lic->name(); ?></label></li>
    <?php } ?>
    </ul>
    </div>
    </form>
    <?php
    $widget['content'] = ob_get_clean();
    
    return $widget;
    
}