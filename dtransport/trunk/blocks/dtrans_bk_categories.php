<?php
// $Id$
// --------------------------------------------------------------
// D-Transport
// Manage files for download in XOOPS
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

function dt_block_categories($options){
    include_once XOOPS_ROOT_PATH.'/modules/dtransport/class/dtcategory.class.php';
    $rmu = RMUtilities::get();
    $rmf = RMFunctions::get();
    $mc = $rmu->module_config('dtransport');
    
    $url = $rmf->current_url();

    $rpath = parse_url($url);
    $xpath = parse_url(XOOPS_URL);
    
    if($mc['permalinks']){
        $params = trim(str_replace($xpath['path'].'/'.trim($mc['htbase'], '/'), '', rtrim($rpath['path'], "/")), '/');
        $search = array('category','publisher','recents','popular','rated','updated');

        if($params=='')
            $params = array();
        else
            $params = explode("/", trim($params));
        
        if(!empty($params) && $params[0]=='category'){
        
            $db = XoopsDatabaseFactory::getDatabaseConnection();
            $params = explode("page", implode("/", array_slice($params, 1)));
            $path = explode("/", $params[0]);
            foreach ($path as $k){

                if ($k=='') continue;

                $category = new DTCategory();
                $sql = "SELECT * FROM ".$db->prefix("dtrans_categos")." WHERE nameid='$k' AND parent='$idp'";
                $result = $db->query($sql);

                if ($db->getRowsNum($result)>0){
                    $row = $db->fetchArray($result);
                    $idp = $row['id_cat'];
                    $category->assignVars($row);
                } else {
                    $dtfunc->error_404();
                }

            }
        
        } else {
            $category = new DTCategory();
        }
        
    }
    
    $tpl = RMTemplate::get();
    $tpl->add_xoops_style('blocks.css','dtransport');

    include_once XOOPS_ROOT_PATH.'/modules/dtransport/class/dtfunctions.class.php';
    $categories = array();
    $dtfunc = new DTFunctions();

    $dtfunc->getCategos($categories, 0, $category->id(), array(), false, 1);

    $block = array();
    foreach($categories as $cat){

        if($cat['jumps']>$options[0]-1 && $options[0]>0) continue;

        $block['categories'][] = $cat;

    }
    
    if(!$category->isNew())
        $block['parent'] = array('name' => $category->name(), 'link' => $category->permalink());

    return $block;

}

function dt_block_categories_edit($options){

    $tpl = RMTemplate::get();
    $tpl->add_xoops_style('admin_block.css','dtransport');

    ob_start()
    ?>
    <div class="dt_table">
        <div class="dt_row">
            <div class="dt_cell">
                <label for="cat-level"><?php _e('Show categories levels:','dtransport'); ?></label>
            </div>
            <div class="dt_cell">
                <select name="options[0]" id="cat-level">
                    <option value="0"<?php if($options[0]==0): ?> selected="selected"<?php endif; ?>><?php _e('All levels','dtransport'); ?></option>
                    <option value="1"<?php if($options[0]==1): ?> selected="selected"<?php endif; ?>><?php _e('1 level','dtransport'); ?></option>
                    <option value="2"<?php if($options[0]==2): ?> selected="selected"<?php endif; ?>><?php _e('2 levels','dtransport'); ?></option>
                    <option value="3"<?php if($options[0]==3): ?> selected="selected"<?php endif; ?>><?php _e('3 levels','dtransport'); ?></option>
                    <option value="4"<?php if($options[0]==4): ?> selected="selected"<?php endif; ?>><?php _e('4 levels','dtransport'); ?></option>
                    <option value="5"<?php if($options[0]==5): ?> selected="selected"<?php endif; ?>><?php _e('5 levels','dtransport'); ?></option>
                </select>
            </div>
        </div>
    </div>
    <?php
    $ret = ob_get_clean();
    return $ret;
}