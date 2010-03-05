<?php
// $Id$
// --------------------------------------------------------------
// MyGalleries
// Module for advanced image galleries management
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class GSFunctions
{
	public function makeQuota(GSUser $user, $showpercent = false){
		$rtn = '<div style="font-weight: bold; color: #999; font-family: Verdana, arial, helvetica, sans-serif; font-size: 10px; width: 102px; text-align: center:"><div style="text-align: left;width: 102px; height: 15px; border: 1px solid #ccc; background: url('.XOOPS_URL.'/modules/galleries/images/quotagray.png) no-repeat;">';
		$quota = $user->quota();
		$used = $user->usedQuota();
		$percent = $quota/102;
		$rtn .= '<div style="text-align: center; width: '.($used>0 ? ($used/$percent > 102 ? 102 : round($used/$percent)) : 0).'px; background: url('.XOOPS_URL.'/modules/galleries/images/quotabar.png) no-repeat;">&nbsp;</div>';
		$rtn .= '</div>';
		if ($showpercent){
			$rtn .= round($user->usedQuota()*(100/$quota)).'%';
		} else {
			$rtn .= RMUtilities::formatBytesSize($user->usedQuota());
		}
		$rtn .= "</div>";
		return $rtn;
	}
	
	/**
	* @desc Calcula el espacio utilizado en disco por un directorio
	*/
	public function folderSize($path){
		if ($path=='') return;
		$size = 0;
		$dir = opendir($path);
		while (($file = readdir($dir)) !== false){
			if ($file == '.' || $file=='..') continue;
			if (is_dir($path . '/'.$file)){
				$size += GSFunctions::folderSize($path . '/'.$file);
			} else {
				$size += filesize($path . '/'.$file);
			}
		}
		closedir($dir);
		return $size;
	}
	
	/**
	* @desc Crea el encabezado de la sección frontal
	*/
	public function makeHeader(){
		global $xoopsModuleConfig, $tpl, $xoopsUser;
		
		$mc =& $xoopsModuleConfig;
		$tpl->assign('gs_title', $mc['section_title']);
		$tpl->assign('lang_home', _MS_GS_HOME);
		$tpl->assign('lang_tags', _MS_GS_TAG);
		$tpl->assign('lang_sets', _MS_GS_SETS);
		$tpl->assign('lang_hphotos', _MS_GS_HPHOTOS);
		$tpl->assign('gs_tagslink', GS_URL.($mc['urlmode'] ? '/explore/tags' : '/explore.php?by=explore/tags'));
		$tpl->assign('gs_setslink', GS_URL.($mc['urlmode'] ? '/explore/sets' : '/explore.php?by=explore/sets'));
		$tpl->assign('gs_photoslink', GS_URL.($mc['urlmode'] ? '/explore/photos' : '/explore.php?by=explore/photos'));
		$tpl->assign('lang_search',_MS_GS_SEARCH);
		
		if($xoopsUser && in_array($xoopsUser->uid(),GSFunctions::getAllowedUsers())){
			$tpl->assign('lang_myphotos',_MS_GS_MYPHOTOS);
			$tpl->assign('gs_myphotoslink', GS_URL.($mc['urlmode'] ? '/cpanel/' : '/cpanel.php'));
		}
		
		if (GSFunctions::canSubmit($xoopsUser)){
			$tpl->assign('can_submit', 1);
			$tpl->assign('lang_sendpics', _MS_GS_SENDPICS);
			$tpl->assign('gs_sendlink', GS_URL.($mc['urlmode'] ? '/submit/' : '/submit.php'));
		}
		
	}
	
	/**
	* @desc Determina si un usuario cuenta con autorización
	* para cargar imágenes
	* @param {@link EXMUser}
	* @return false;
	*/
	public function canSubmit(EXMUser &$exmUser){
		global $xoopsModuleConfig;
		
		if ($exmUser && $exmUser->isAdmin()) return true;
		
		$users = GSFunctions::getAllowedUsers();
		
		if ($exmUser && in_array($exmUser->uid(), $users)) return true;
		
		$mc =& $xoopsModuleConfig;
		if ($mc['submit']){
			
			if (in_array(0, $mc['groups'])) return true;
			
			if (!$exmUser) return false;
			
			foreach ($exmUser->groups() as $k){
				if (in_array($k, $groups)) return true;
			}
			
		}
		
	}
	
	/**
	* @desc Obtiene un array con los identificadores de los usuarios permitidos
	* @return array
	*/
	public function getAllowedUsers(){
		
		$db =& Database::getInstance();
		$result = $db->query("SELECT uid FROM ".$db->prefix("gs_users")." WHERE blocked='0'");
		$rtn = array();
		while (list($uid) = $db->fetchRow($result)){
			$rtn[] = $uid;
		}

		return $rtn;
	}
	
	/**
	* @desc Redimensiona una imágen con los datos especificados
	*/
	public function resizeImage($crop,$input,$output,$width,$height){
			// Redimensionamos la imagen
		$redim = new RMImageControl($input, $output);
		if ($crop){
			$redim->resizeAndCrop($width,$height);
		} else {
			$redim->resizeWidthOrHeight($width,$height);
		}
	}
	
	/**
	* @desc Determina la página para la imágen especificada
	*/
	public function pageFromPic(GSImage &$pic, GSUser &$user, $set=0){
		global $xoopsModuleConfig;
		$db =& Database::getInstance();
		$mc =& $xoopsModuleConfig;
		
		if ($pic->isNew()) return;
		
		if ($set>0){
		
		} else {

			// Determinar en que págona se ubica la imágen
			$result = $db->query("SELECT id_image FROM ".$db->prefix('gs_images')." WHERE owner='".$user->uid()."' AND public='1' ORDER BY created DESC, modified DESC");
			$num = $db->getRowsNum($result);
			for ($i = 0; $i < $num; ++$i)
			{
				list($cur_id) = $db->fetchRow($result);
			 //	echo $cur_id."<br />";
				if ($cur_id == $pic->id())
					break;
			}
			++$i;	// we started at 0
			$limit = $mc['user_format_mode'] ? $mc['user_format_values'][3] : $mc['limit_pics'];
			return ceil($i / $limit);
		}
	}


	/**
	*@desc Elimina las postales según su tiempo de existencia
	**/
	public function deletePostcard(){

		global $xoopsModuleConfig, $db;
	
		$mc =& $xoopsModuleConfig;
	
		$time = time() - $mc['time_postcard']*86400;
		
		$sql = "DELETE FROM ".$db->prefix('gs_postcards')." WHERE date<=$time";
		$result = $db->queryF($sql);
	}


	/**
	* @desc Verifica el tipo de acceso a la información y si es necesario 
	*la existencia del archivo htaccess
	**/
	public function accessInfo(){
		global $xoopsModuleConfig;

		if ($xoopsModuleConfig['urlmode']==0) return true;
		
		$docroot = str_replace("\\", "/", $_SERVER['DOCUMENT_ROOT']);
		$path = str_replace($docroot, '', XOOPS_ROOT_PATH.'/modules/galleries/');
		if (substr($path, 0, 1)!='/'){
			$path = '/'.$path;
		}
		$file=XOOPS_ROOT_PATH.'/modules/galleries/.htaccess';

		if (!file_exists($file)){
			return false;
		}
			
		//Determina permisos de lectura y escritura a htacces
		if ((!is_readable($file)))		
		{
			return false;
		}
		
		//Verifica que información contiene htaccess y si es necesario reescribe htacces
		$info = file_get_contents($file);
		
		//Si acceso es por id numérico
		if ($xoopsModuleConfig['urlmode']){
			$contenido = "RewriteEngine On\nRewriteBase $path\nRewriteCond %{REQUEST_URI} !/[A-Z]+-\nRewriteRule ^usr/(.*)/?$ user.php?id=usr/$1 [L]\nRewriteRule ^explore/(.*)/?$ explore.php?by=explore/$1 [L]\nRewriteRule ^submit/(.*)/?$ submit.php?submit=submit/$1 [L]\nRewriteRule ^cpanel/(.*)/?$ cpanel.php?s=cpanel/$1 [L]\nRewriteRule ^postcard/(.*)/?$ postcard.php?id=postcard/$1 [L]";
			//Compara contenido de htaccess
			$pos = stripos(file_get_contents($file),$contenido);		

			if ($pos!==false) return true;
			
			if ((!is_writable($file)))		
			{
				return false;
			}
			
			//Copia información a archivo
			return file_put_contents($file,$contenido);
		
		}

	}

	/**
	* Create the toolbar for rmcommon
	*/
	public function toolbar(){
		RMTemplate::get()->add_tool(__('Dashboard','admin_works'), './index.php', '../images/dashboard.png', 'dashboard');
		RMTemplate::get()->add_tool(__('Albums','admin_works'), './sets.php', '../images/album.png', 'sets');
		RMTemplate::get()->add_tool(__('Tags','admin_works'), './tags.php', '../images/tags.png', 'tags');
		RMTemplate::get()->add_tool(__('Users','admin_works'), './users.php', '../images/users.png', 'users');
		RMTemplate::get()->add_tool(__('Albums','admin_works'), './images.php', '../images/images.png', 'images');
		RMTemplate::get()->add_tool(__('Postcards','admin_works'), './postcards.php', '../images/postcard.png', 'postcards');
		
		RMTemplate::get()->add_style('admin.css', 'galleries');
	}
	
}
