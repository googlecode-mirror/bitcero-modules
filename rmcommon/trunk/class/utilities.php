<?php
// $Id$
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------
 
class RMUtilities
{
	/**
	 * Obtiene una ?nica instancia de esta clase
	 */
	function get(){
		static $instance;
		if (!isset($instance)) {
			$instance = new RMUtilities();
		}
		return $instance;
	}
	/**
	 * Funci?n para obtener la URL apartir de una ruta
	 * absoluta en el servidor.
	 * @param string $ruta Ruta fisica en el servidor
	 * @return string
	 */
	public function make_url($ruta){
		$ruta = str_replace(ABSPATH, ABSURL, $ruta);
		return $ruta;
	}
	/**
	 * Elimina un archivo existente del servidor
	 * @param string $filepath Ruta completa al archivo
	 * @return bool
	 */
	public function delete_file($filepath){
		if ($filepath == '') return false;
		
		if (!file_exists($filepath)) return true;
		
		return unlink($filepath);
		
	}
	/**
	 * Comprueba si existe un elemento en una tabla expec?fica
	 * @param string $table Nombre de la tabla
	 * @param string $cond Condici?n de b?squeda
	 * @return bool
	 */
	public function get_count($table, $cond=''){
		$db =& EXMDatabase::get();
		$sql = "SELECT COUNT(*) FROM $table";
		if ($cond!='') $sql .= " WHERE $cond";
		list($num) = $db->fetchRow($db->query($sql));
		return $num;
	}
	/**
	 * Obtiene la version del m?dulo actual
	 * Esta funci?n solo funciona con m?dulos de Red M?xico Soft
	 * o compatibles
	 * @param bool $includename Mostrar todo el nombre del m?dulo
	 * @param string $module Obtener Versi?n del M?dulo Espec?ficado
	 * @param int $type 0 = String, 1 = Array
	 */
	public function getVersion($includename = true, $module='', $type=0){
		global $exmApp, $exmConfig;
		
        //global $version;
        
		if ($module=='exm' || $module=='exm'){
            
			if (!file_exists(ABSPATH.'/include/version.php')) return false;
			@include(ABSPATH.'/include/version.php');
                        
		} elseif($module!='') {
			/**
            * Obtenemos la version de un módulo instalado
            */
            if (file_exists(ABSPATH.'/apps/'.$module.'/language/'.$exmConfig['language'].'/modinfo.php')){
            	include_once ABSPATH.'/apps/'.$module.'/language/'.$exmConfig['language'].'/modinfo.php';
            } else {
            	include_once ABSPATH.'/apps/'.$module.'/language/spanish/modinfo.php';
            }
            if ($module == $exmApp->dirname()){
                $version = $exmApp->getVar('version');   
            } else {
                $mod = new EXMApplication($module);
                if (!$mod->isNew()){
                    $version = $mod->getVar('version');
                } else {
                    $mod->loadInfoAsVar($module);
                    $version = $mod->getVar('version');
                    $version = is_array($version) ? $version : array('number'=>$version, 'revision'=>0, 'status'=>0, 'name'=>$mod->getInfo('name'));
                }
            }
			
		} else {
			if (empty($exmApp)) return false;
			
			if (!file_exists(ABSPATH.'/apps/'.$exmApp->getVar('dirname').'/include/version.php')) return false;
			
			global $version;
			require_once ABSPATH.'/apps/'.$exmApp->dirname().'/include/version.php';
			
		}
	
		if ($type==1){
			return $version;
		}
		
		$rtn = '';
		
		if ($includename){
			$rtn .= (defined($version['name']) ? constant($version['name']) : $version['name']) . ' ';
		}
		
		$rtn .= $version['number'];

		if ($version['revision'] > 0){
			$rtn .= '.' . $version['revision'] / 100;
		} else {
			$rtn .= '.0';
		}

		switch($version['status']){
			case '-3':
				$rtn .= ' alfa';
				break;
			case '-2':
				$rtn .= ' beta';
				break;
			case '-1':
				$rtn .= ' final';
				break;
			case '0':
				break;
		}
        
		return $rtn;
	}

	/**
	 * Permite obtener opciones de ocnfiguraci?n de un m?dulo cualquiera
	 * @param string $modname Nombre del M?dulo
	 * @param string $option Nombre de la opci?n de configuraci?n
	 * @return string o array
	 */
	public function module_config($modname, $option=''){
		global $xoopsModuleConfig, $xoopsModule;
		
		static $rmModuleConfigs;
		
		if (isset($rmModuleConfigs[$modname])){
			
			if($option!='' & isset($rmModuleConfigs[$modname][$option])) return $rmModuleConfigs[$modname][$option];
			
			return $rmModuleConfigs[$modname];
			
		}
		
		if (isset($xoopsModuleConfig) && (is_object($xoopsModule) && $xoopsModule->getVar('dirname') == $modname && $xoopsModule->getVar('isactive'))) {
			$rmModuleConfigs[$modname] = $xoopsModuleConfig;
			if($option != '' && isset($xoopsModuleConfig[$option])) {
				return $xoopsModuleConfig[$option];
			} else {
				return $xoopsModuleConfig;
			}
		} else {
			$module_handler =& xoops_gethandler('module');
			$module = $module_handler->getByDirname($modname);
			$config_handler =& xoops_gethandler('config');
			if ($module) {
				$moduleConfig =& $config_handler->getConfigsByCat(0, $module->getVar('mid'));
				$rmModuleConfigs[$modname] = $moduleConfig;
				if($option != '' && isset($moduleConfig[$option])) {
					return $moduleConfig[$option];
				} else {
					return $moduleConfig;
				}
			}
		}
	}

    /**
    * Determina el color rgb a partir de una cadena HEX
    */
    private function hexToRGB($color){
        // Transformamos el color hex a rgb
        if ($color[0] == '#')
            $color = substr($color, 1);

        if (strlen($color) == 6)
            list($r, $g, $b) = array($color[0].$color[1],
                                     $color[2].$color[3],
                                     $color[4].$color[5]);
        elseif (strlen($color) == 3)
            list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
        else
            list($r,$g,$b) = array("FF", "FF", "FF");

        $r = hexdec($r); $g = hexdec($g); $b = hexdec($b);
        return array('r'=>$r,'g'=>$g,'b'=>$b);
    }
	
	/**
	 * Genera una cadena aleatoria en base a par?metros especificados
	 */
	public function randomString($size, $digit = true, $special = false, $upper = false, $alpha = true){
		$aM = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$am = "abcdefghijklmnopqrstuvwxyz";
		$d = "0123456789";
		$s = "?@#\$%&()=???!.:,;-_+*[]{}";
		
		$que = array();
		if ($alpha) $que[] = 'alpha';
		if ($digit) $que[] = 'digit';
		if ($special) $que[] = 'special';
		
		$rtn = '';
		
		for ($i=1;$i<=$size;$i++){
			$op = $que[rand(0, count($que) - 1)];
			switch($op){
				case 'alpha':
					$what = $upper ? $aM : (rand(0, 1)==0 ? $aM : $am);
					$rtn .= substr($what, rand(0, strlen($what)-1), 1);
					break;
				case 'digit':
					$rtn .= substr($d, rand(0, strlen($d)-1), 1);
					break;
				case 'special':
					$rtn .= substr($s, rand(0, strlen($s)-1), 1);
					break;
			}
		}
		
		return $rtn;
		
	}
	
	/**
	* Add a slash (/) to the end of string
	*/
	public function add_slash($string){
		$string = rtrim($string, "/");
		return $string.'/';
	}
	
	/**
	 * Format bytes to MB, GB, KB, etc
	 * @param int $size Tamaño de bytes
	 * @return string
	 */
	public function formatBytesSize($size){

		$kb = 1024;
		$mb = $kb * 1024;
		$gb = $mb * 1024;
		
		if ($size<$kb){
			return sprintf(__('%s bytes','rmcommon'), $size);
		} elseif ($size<$mb){
			return sprintf(__('%s Kb','rmcommon'), number_format($size / $kb, 2));
		} elseif ($size<$gb){
			return sprintf(__('%s MB','rmcommon'), number_format($size / $mb, 2));
		} else {
			return sprintf(__('%s GB','rmcommon'), number_format($size / $mb, 2));
		}
		
	}
		
}
