<?php
// $Id$
// --------------------------------------------------------------
// Professional Works
// Advanced Portfolio System
// Author: BitC3R0 <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class PWFunctions
{

	/**
	* @desc Crea el encabezado de la sección frontal
	*/
	public function makeHeader(){
		global $xoopsModuleConfig, $xoopsTpl, $xoopsUser, $db;
		
		$xoopsTpl->assign('pw_title', $xoopsModuleConfig['title']);
		$xoopsTpl->assign('lang_recentsall', __('Recent works','works'));
		$xoopsTpl->assign('lang_featuredall', __('Featured works','works'));
		
		$recent = $xoopsModuleConfig['urlmode'] ? XOOPS_URL.'/'.trim($xoopsModuleConfig['htbase'], '/').'/recent/' : XOOPS_URL.'/modules/works/recent.php';
		$featured = $xoopsModuleConfig['urlmode'] ? XOOPS_URL.'/'.trim($xoopsModuleConfig['htbase'], '/').'/featured/' : XOOPS_URL.'/modules/works/featured.php';
		
		$xoopsTpl->assign('url_recent', $recent);
		$xoopsTpl->assign('url_featured', $featured);
		$xoopsTpl->assign('url_home', PW_URL);
			
	}

	/**
	* @desc Rating del trabajo
	* @param $rating del trabajo
	**/
	public function rating($rating){
		$rtn = '<div class="pwRating" style="font-weight: bold; color: #999; font-family: Verdana, arial, helvetica, sans-serif; font-size: 10px; width: 69px; text-align: center;"><div style="text-align: left;width: 69px; height: 15px; background: url('.XOOPS_URL.'/modules/works/images/starsgray.png) no-repeat;">';
		$rating = $rating;
		$percent = 10/69;
		$rtn .= '<div style="text-align: center; width: '.($rating>0 ? ($rating/$percent > 69 ? 69 : round($rating/$percent)) : 0).'px; height: 15px; background: url('.XOOPS_URL.'/modules/works/images/stars.png) no-repeat;">&nbsp;</div>';
		$rtn .= '</div>';
		$rtn .= "</div>";
		return $rtn;
	}

	/**
	* @desc Verifica el tipo de acceso a la información y si es necesario 
	*la existencia del archivo htaccess
	**/
	public function accessInfo(){
		global $xoopsModuleConfig;

		if ($xoopsModuleConfig['urlmode']==0) return true;
		
		$docroot = str_replace("\\", "/", $_SERVER['DOCUMENT_ROOT']);
		$path = str_replace($docroot, '', XOOPS_ROOT_PATH.'/modules/works/');
		if (substr($path, 0, 1)!='/'){
			$path = '/'.$path;
		}
		$file=XOOPS_ROOT_PATH.'/modules/works/.htaccess';

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
			$contenido = "RewriteEngine On\nRewriteBase ".str_replace($docroot, '', PW_PATH.'/')."\nRewriteCond %{REQUEST_URI} !/[A-Z]+-\nRewriteRule ^pag/(.*)/?$ index.php?pag=$1 [L]\nRewriteRule ^recent/(.*)/?$ recent.php$1 [L]\nRewriteRule ^featured/(.*)/?$ featured.php$1 [L]\nRewriteRule ^work/(.*)/?$ work.php?id=$1 [L]\nRewriteRule ^cat/(.*)/?$ catego.php?id=$1 [L]";
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
	* Get works based on given parameters
	*/
	public function get_works($limit, $category=null, $public=1, $object=true, $order="DESC"){
		global $xoopsModule, $xoopsModuleConfig;
        
        include_once XOOPS_ROOT_PATH.'/modules/works/class/pwwork.class.php';
        include_once XOOPS_ROOT_PATH.'/modules/works/class/pwcategory.class.php';
        include_once XOOPS_ROOT_PATH.'/modules/works/class/pwclient.class.php';
        
		$db = Database::getInstance();
		$sql = "SELECT * FROM ".$db->prefix('pw_works')." WHERE public=$public";
		$sql .= $category>0 ? " AND catego='$category'" : '';
        $sql .= $order!='' ? " ORDER BY $order" : '';
		$sql.= " LIMIT 0,$limit";
		
		if ($xoopsModule && $xoopsModule->dirname()=='works'){
			$mc =& $xoopsModuleConfig;
		} else {
			$mc = RMUtilities::module_config('works');
		}
		
		$result = $db->query($sql);
		$works = array();
		while ($row = $db->fetchArray($result)){
			$work = new PWWork();
			$work->assignVars($row);
			$ret = array();

			if (!isset($categos[$work->category()])) $categos[$work->category()] = new PWCategory($work->category());

			if (!isset($clients[$work->client()])) $clients[$work->client()] = new PWClient($work->client());

			$ret = array(
				'id'=>$work->id(),
				'title'=>$work->title(),
				'desc'=>$work->descShort(),
				'catego'=>$categos[$work->category()]->name(),
				'client'=>$clients[$work->client()]->name(),
				'link'=>$work->link(),
				'created'=>formatTimeStamp($work->created(),'s'),
				'image'=>XOOPS_UPLOAD_URL.'/works/ths/'.$work->image(),
				'rating'=>PWFunctions::rating($work->rating()),
				'featured'=>$work->mark(),
				'linkcat'=>$categos[$work->category()]->link(),
                'metas'=>$work->get_metas()
			);
			
			if ($object){
	    		$w = new stdClass();
				foreach ($ret as $var => $value){
					$w->$var = $value;
				}
				$works[] = $w;
		    } else {
				$works[] = $ret;
		    }
			
		}
		
		return $works;
		
	}
	
	/**
	* Create toolbar
	*/
	public function toolbar(){
		RMTemplate::get()->add_tool(__('Dashboard','admin_works'), './index.php', '../images/dashboard.png', 'dashboard');
		RMTemplate::get()->add_tool(__('Categories','admin_works'), './categos.php', '../images/cats16.png', 'categories');
		RMTemplate::get()->add_tool(__('Customer Types','admin_works'), './types.php', '../images/types.png', 'customertypes');
		RMTemplate::get()->add_tool(__('Customers','admin_works'), './clients.php', '../images/clients.png', 'customers');
		RMTemplate::get()->add_tool(__('Works','admin_works'), './works.php', '../images/works.png', 'works');
	}
    
    /**
    * Get works custom fields
    * @param int Work id
    */
    public function work_metas($work){
        
        if ($work<=0) return;
        
        $db = Database::getInstance();
        $sql = "SELECT * FROM ".$db->prefix("pw_meta")." WHERE work='$work'";
        $result = $db->query($sql);
        $metas = array();
        while ($row = $db->fetchArray($result)){
            $metas[$row['name']] = $row['value'];
        }
        
        return $metas;
        
    }

}
