<?php
// $Id$
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}
/**
 * Clase para el manejo de bloques en Common Utilities
 */
class RMBlock extends RMObject
{
    /**
     * Grupos con permiso de lectura
     */
    private $rgroups = array();
    /**
     * Grupos con permiso de escritura 
     */
    private $wgroups = array();
    /**
     * Secciones en las que se muestra el bloque
     */
    private $sections = array();
    
    /**
     * Carga un nuevo objecto EXMBlock
     * @param $id = Identificador del Bloque existente en la base de datos
     */
    function __construct($id = null)
    {
        $this->db =& Database::getInstance();
        $this->_dbtable = $this->db->prefix("rmc_blocks");
        $this->setNew();
        $this->initVarsFromTable();
        $this->setVarType('options', XOBJ_DTYPE_ARRAY);
        $this->setVarType('content', XOBJ_DTYPE_OTHER);
        
        if ($id==null) return;
        
        if (!$this->loadValues($id)) return;
        
        $this->unsetNew();
        
        // Cargamos los grupos con permisos    
        //$this->readGroups();
    }
    
    public function id(){
        return $this->getVar('bid');
    }

    /**
     * Obtiene un array con los identificadores
     * de los grupos con permiso de lectura
     */
    public function readGroups(){
        
        if (empty($this->rgroups)){
            $sql = "SELECT gperm_groupid FROM ".$this->db->prefix("group_permission")." WHERE gperm_itemid='".$this->id()."' AND gperm_name='block_read'";
            $result = $this->db->query($sql);
            $ret = array();
            while ($row = $this->db->fetchArray($result)){
                
                $this->rgroups[] = $row['gperm_groupid'];
                
            }
        }

        return $this->rgroups;
        
    }
    /**
     * Establece los grupos con permiso de acceso al bloque
     */
    public function setReadGroups($groups){
        $this->rgroups = $groups;
    }
    /**
     * Obtiene un array con los identificadores
     * de los grupos con permiso de administrador
     */
    public function adminGroups($object = false){
        
        if (empty($this->wgroups)){
            $sql = "SELECT gperm_groupid FROM ".$this->db->prefix("group_permission")." WHERE gperm_itemid='".$this->id()."' AND gperm_name='block_admin'";
            $result = $this->db->query($sql);
            $ret = array();
            while ($row = $this->db->fetchArray($result)){
                
                $this->wgroups[] = $row['gperm_groupid'];
                
            }
        }
        
        if ($object){
            // Devolvemos los objectos EXMGroup en un array            
            $ret = array();
            foreach ($this->wgroups as $k){
                $ret[] = new EXMGroup($row['gperm_groupid']);
            }
            return $ret;            
        } else {
            // Devolvemos unicamente los ids
            return $this->wgroups;
        }
        
    }
    /**
     * Establece los grupos con permiso de acceso al bloque
     */
    public function setAdminGroups($groups){
        $this->wgroups = $groups;
    }
    /** 
     * Gets or sets the sections for this widget
     * @param array Sections with subpages
     * @return nothing|array
     */
    public function sections(){
        
        if (func_num_args()<=0){
            // Load all sections
            if (empty($this->sections)){
                $sql = "SELECT mid FROM ".$this->db->prefix("rmc_bkmod")." WHERE bid='".$this->id()."'";
                $result = $this->db->query($sql);
                $ret = array();
                while ($row = $this->db->fetchArray($result)){
                    
                    $this->sections[] = $row['mid'];
                    
                }
            }
            
            return $this->sections;
            
        } else {
            $value = func_get_arg(0);
            $this->sections = $value;
        }
        
    }
    /**
    * @desc Devuelve las subpáginas seleccionadas para una
    * determinada sección
    */
    public function subpages(){
        $sql = "SELECT mid, page FROM ".$this->db->prefix("rmc_bkmod")." WHERE bid='".$this->id()."'";
        $result = $this->db->query($sql);
        $ret = array();
        while ($row = $this->db->fetchArray($result)){
            $ret[$row['mid']][] = $row['subpage'];
            
        }
        return $ret;
    }
    /**
     * Comprueba si un grupo especifico cuenta con
     * permisos de admnistración/lectura para un 
     * bloque específico
     * @param int $gid Identificador del grupo
     * @param int $level Nivel de Acceso
     * @return bool
     */
    public function checkRights($gid=0, $level=0){
        global $xoopsUser;

        if ($gid<=0 && empty($xoopsUser)) $gid = XOOPS_GROUP_ANONYMOUS;
        
        $pHand =& xoops_gethandler('groupperm');
        return $pHand->checkRight($level ? 'block_admin' : 'block_read', $this->id(), $gid>0 ? $gid : $xoopsUser->getGroups());
    }
    
    /**
    * Devuelve el contenido de un bloque
    *
    * @param string $format Uso: 'S' para mostrar y 'E' para editar
    * @param $c_type    Tipo de Contenido
    * @returns string
    */
    public function getContent($format = 'S')
    {
        
        $c_type = $this->contentType();
        
        switch ( $format ) {
            case 'S':
                if ( $c_type == 'H' ) {
                    return str_replace('{X_SITEURL}', ABSURL.'/', $this->getVar('content', 'N'));
                } elseif ( $c_type == 'P' ) {
                    ob_start();
                    echo eval($this->getVar('content', 'N'));
                    $content = ob_get_contents();
                    ob_end_clean();
                    return str_replace('{X_SITEURL}', ABSURL.'/', $content);
                } elseif ( $c_type == 'S' ) {
                    $myts =& MyTextSanitizer::getInstance();
                    return str_replace('{X_SITEURL}', ABSURL.'/', $myts->displayTarea($this->getVar('content', 'N'), 1, 1));
                } else {
                    $myts =& MyTextSanitizer::getInstance();
                    return str_replace('{X_SITEURL}', ABSURL.'/', $myts->displayTarea($this->getVar('content', 'N'), 1, 0));
                }
                break;
            case 'E':
                return $this->getVar('content', 'E');
                break;
            default:
                return $this->getVar('content', 'N');
                break;
        }
    }
    /**
     * Genera el bloque a partir de su función
     */
    function buildBlock()
    {
        
        global $exmConfig, $exmOption;
        $block = array();
        // M for module block, S for system block C for Custom
        if ( $this->getVar("block_type") != "C" ) {
            // get block display function
            $show_func = $this->getVar('show_func');
            if ( !$show_func ) {
                return false;
            }
            // Comprobamos el tipo de bloque
             if ($this->getVar('type')=='U'){
                // Bloque cargado
                $dir = ABSPATH.'/uploads/widgets/'.sprintf("%05s", $this->id()).'/';
                $file = ABSPATH.'/uploads/widgets/'.sprintf("%05s", $this->id()).'/'.$this->file();
                if (file_exists($dir . 'lang_'.$exmConfig['language'].'.php')){
                    include_once $dir . 'lang_'.$exmConfig['language'].'.php';
                } else {
                    @include_once $dir . 'lang_spanish.php';
                }
                
                include_once $file;
                $options = $this->getVar("options");
                $option = is_array($options) ? $options : explode('|', $options);
                if (function_exists($show_func)){
                    $block = $show_func($option);
                    if (!$block) return false;
                }
                
            } else {
                
                // Bloque de Módulo
                // Comprobamos si se trata de un bloque de plugin de sistema               
                if ($this->dirname()=='zwe'){
                    $file = ABSPATH.'/apps/system/'.$this->feature().'/blocks/'.$this->file();
                    if (file_exists(ABSPATH.'/apps/system/'.$this->feature().'/language/'.$exmConfig['language'] . '/blocks.php')) {
                        $langFile = ABSPATH.'/apps/system/'.$this->feature().'/language/'.$exmConfig['language'] . '/blocks.php';
                    } else {
                        $langFile = ABSPATH.'/apps/system/'.$this->feature().'/language/spanish/blocks.php';
                    }
                } else {
                    $file = ABSPATH."/apps/".$this->getVar('dirname')."/blocks/".$this->getVar('func_file');
                    if ( file_exists(ABSPATH."/apps/".$this->getVar('dirname')."/language/".$exmConfig['language']."/blocks.php") ) {
                        $langFile = ABSPATH."/apps/".$this->getVar('dirname')."/language/".$exmConfig['language']."/blocks.php";
                    } elseif ( file_exists(ABSPATH."/apps/".$this->getVar('dirname')."/language/spanish/blocks.php") ) {
                        $langFile = ABSPATH."/apps/".$this->getVar('dirname')."/language/spanish/blocks.php";
                    }
                }
                
                include_once $langFile;
                
                include_once $file;
                $options = $this->getVar("options");
                $option = is_array($options) ? $options : explode('|', $options);
                if (function_exists($show_func)){
                    $block = $show_func($option);
                    if (!$block) return false;
                }
                
            }
            
        } else {
            
            // Bloque Personalizado. Solo devolvemos el contenido
            $block['content'] = $this->getContent("S",$this->getVar("c_type"));
            if (empty($block['content'])) {
                return false;
            }
        }

        return $block;
    }
    
    /*
    * Aligns the content of a block
    * If position is 0, content in DB is positioned
    * before the original content
    * If position is 1, content in DB is positioned
    * after the original content
    */
    function buildContent($position,$content="",$contentdb="")
    {
        if ( $position == 0 ) {
            $ret = $contentdb.$content;
        } elseif ( $position == 1 ) {
            $ret = $content.$contentdb;
        }
        return $ret;
    }

    function buildTitle($originaltitle, $newtitle="")
    {
        if ($newtitle != "") {
            $ret = $newtitle;
        } else {
            $ret = $originaltitle;
        }
        return $ret;
    }

    function isCustom()
    {
        if ( $this->getVar("block_type") == "C" ) {
            return true;
        }
        return false;
    }
    
    /**
     * Obtiene el código HTML desde la función de edición
     * del bloque siempre y cuando esta exista
     */
    function getOptions()
    {
        global $xoopsConfig;
        
        $edit_func = $this->getVar('edit_func');
        if (trim($edit_func)=='') return;
        
        switch($this->getVar('element_type')){
            case 'module':
                $file = XOOPS_ROOT_PATH.'/modules/'.$this->getVar('element').'/blocks/'.$this->getVar('file');
                $lang = "load_mod_locale";
                break;
            case 'plugin':
                
                break;            
        }
        
        // Check if widget file exists
        if (!is_file($file)){
            return __('The configuration file for this widget does not exists!','rmcommon');
        }
        
        include_once $file;
        
        // Check if edit function exists
        if (!function_exists($edit_func)){
            return __('There was a problem trying to show the configuration options for this widget!','rmcommon');
        }
        
        // Get language for this widget
        $lang($this->getVar('element'));
        
        return $edit_func($this->getVar('options'));
        
    }
    
    /**
     * Almacenamos los valores del bloque en
     * la base de datos
     */
    public function save(){
        if ($this->isNew()){
            if (!$this->saveToTable()) return false;
        } else {
            if (!$this->updateTable()) return false;
        }
        
        // Guardamos las secciones
        if (count($this->sections)>0){
            
            if (!$this->isNew()){
                $this->db->queryF("DELETE FROM ".$this->db->prefix("rmc_bkmod")." WHERE bid='".$this->id()."'");
            }
            
            $sql = "INSERT INTO ".$this->db->prefix("rmc_bkmod")." (`bid`,`mid`,`page`) VALUES ";
            $sql1 = '';
            foreach ($this->sections as $id => $k){
                if (is_array($k) && isset($k['subpages'])){
                    foreach ($k['subpages'] as $l){
                        $sql1 .= $sql1=='' ? "('".$this->id()."','$id','$l')" : ", ('".$this->id()."','$id','$l')";
                    }
                } else {
                    $sql1 .= $sql1=='' ? "('".$this->id()."','$id','--')" : ", ('".$this->id()."','$id','--')";
                }
                
            }
            if (!$this->db->queryF($sql . $sql1)) $this->addError($this->db->error());
        }
        // Guardamos los permisos
        if (count($this->rgroups)>0 || count($this->wgroups)>0){
            if (!$this->isNew()){
                $this->db->queryF("DELETE FROM ".$this->db->prefix("group_permission")." WHERE gperm_itemid='".$this->id()."' AND (gperm_name='block_read' OR gperm_name='block_admin')");
            }
            
            $sql = "INSERT INTO ".$this->db->prefix("group_permission")." (`gperm_groupid`,`gperm_itemid`,`gperm_modid`,`gperm_name`) VALUES ";
            $sql1 = '';
            foreach ($this->rgroups as $k){
                $sql1 .= $sql1=='' ? "('$k','".$this->id()."','1','block_read')" : ", ('$k','".$this->id()."','1','block_read')";
            }

            foreach ($this->wgroups as $k){
                $sql1 .= $sql1=='' ? "('$k','".$this->id()."','1','block_admin')" : ", ('$k','".$this->id()."','1','block_admin')";
            }
            
            if (!$this->db->queryF($sql . $sql1)) $this->addError($this->db->error());
        }
        
        if ($this->errors()!=''){return false;} else {return true;}
        
    }
    
    /**
     * Elimina todos los datos del bloque
     */
    function delete(){
        
        if (!$this->db->queryF("DELETE FROM ".$this->db->prefix("rmc_bkmod")." WHERE widget_id='".$this->id()."'")){
            $this->addError($this->db->error());
        }
        if (!$this->db->queryF("DELETE FROM ".$this->db->prefix("group_permission")." WHERE gperm_itemid='".$this->id()."' AND gperm_name='block_read' OR gperm_name='block_admin'")){
            $this->addError($this->db->error());
        }
        
        /*if ($this->blockType()=='U'){
            exm_delete_directory(ABSPATH.'/uploads/widgets/'.sprintf("%05s", $this->id()));
        }*/
        
        $this->deleteFromTable();
        if ($this->errors()!=''){ return false; } else { return true; }
    }
}

class RMBlockHandler
{
    function getAllByGroupModule($groupid, $app_id=0, $toponlyblock=false, $visible=null, $orderby='b.weight,b.bid', $isactive=1, $subpage='')
    {
        
        $orderby = $orderby=='' ? 'b.weight,b.bid' : $orderby;
        
        $db =& EXMDatabase::get();
        $ret = array();
        $sql = "SELECT DISTINCT gperm_itemid FROM ".$db->prefix('group_permission')." WHERE (gperm_name = 'block_read' OR gperm_name='block_admin') AND gperm_modid = 1";
        if ( is_array($groupid) ) {
            $sql .= ' AND gperm_groupid IN ('.implode(',', $groupid).',0)';
        } else {
            if (intval($groupid) > 0) {
                $sql .= ' AND gperm_groupid IN (0,'.$groupid.')';
            }
        }

        $result = $db->query($sql);
        $blockids = array();
        while ( $myrow = $db->fetchArray($result) ) {
            $blockids[] = $myrow['gperm_itemid'];
        }
        if (!empty($blockids)) {
            $sql = 'SELECT b.* FROM '.$db->prefix('rmc_blocks').' b, '.$db->prefix('rmc_bkmod').' m WHERE m.bid=b.bid';
            $sql .= ' AND b.isactive='.$isactive;
            if (isset($visible)) {
                $sql .= ' AND b.visible='.intval($visible);
            }
            $app_id = intval($app_id);
            if (!empty($app_id)) {
                $sql .= ' AND m.app_id IN (0,'.$app_id;
                if ($toponlyblock) {
                    $sql .= ',1';
                }
                $sql .= ')';
            } else {
             /*   if ($toponlyblock) {*/
                    $sql .= ' AND m.app_id IN (0,1)';
                /*} else {
                    $sql .= ' AND m.app_id=0';
                }*/
            }
            $sql .= $subpage!=''  ? " AND (m.subpage='$subpage' OR m.subpage='--')" : '';
            $sql .= ' AND b.bid IN ('.implode(',', $blockids).')';
            $sql .= ' ORDER BY '.$orderby;
            $result = $db->query($sql);
            //echo $sql; die();
            while ( $myrow = $db->fetchArray($result) ) {
                $block = new RMBlock();
                $block->assignVars($myrow);
                $ret[$myrow['bid']] =& $block;
                unset($block);
            }
        }

        return $ret;
    }
    
    function getByModule($moduleid, $asobject=true)
    {
        $db =& EXMDatabase::get();
        
        if (!is_numeric($moduleid)){
            $col = 'dirname';
        } else {
            $col = 'mid';
        }
        
        if ( $asobject == true ) {
            $sql = $sql = "SELECT * FROM ".$db->prefix("rmc_blocks")." WHERE $col='".$moduleid."'";
        } else {
            $sql = "SELECT bid FROM ".$db->prefix("rmc_blocks")." WHERE $col=".$moduleid."";
        }
        $result = $db->query($sql);
        $ret = array();
        while( $myrow = $db->fetchArray($result) ) {
            if ( $asobject ) {
                $ret[] = new EXMBlock($myrow['bid']);
            } else {
                $ret[] = $myrow['bid'];
            }
        }
        return $ret;
    }
}
