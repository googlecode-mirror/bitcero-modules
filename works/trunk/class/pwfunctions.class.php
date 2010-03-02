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
		global $xoopsModuleConfig, $tpl, $xoopsUser, $db;
	
		$mc =& $xoopsModuleConfig;

		$tpl->assign('pw_title', $mc['title']);
		$tpl->assign('lang_home', _MS_PW_HOME);	
		$tpl->assign('lang_recentsalls',_MS_PW_RECENTSALLS);
		$tpl->assign('lang_featuredsalls',_MS_PW_FEATUREDSALLS);
		$tpl->assign('lang_categos',_MS_PW_CATEGOS);
		$tpl->assign('url_home',PW_URL);
		$tpl->assign('url_recent',PW_URL.'/'.($mc['urlmode'] ? 'recent/' : 'recent.php'));	
		$tpl->assign('url_featured',PW_URL.'/'.($mc['urlmode'] ? 'featured/' : 'featured.php'));
		$tpl->assign('url_categos','showCategos()');	

		//Obtenemos la lista de categorías
		$sql = "SELECT * FROM ".$db->prefix('pw_categos')." WHERE active=1 ORDER BY `order`";
		$result = $db->query($sql);
		while ($row = $db->fetchArray($result)){
			$cat = new PWCategory();
			$cat->assignVars($row);
	
			$link = PW_URL.($mc['urlmode'] ? '/category/'.$cat->nameId() : '/catego.php?id='.$cat->nameId());

			$tpl->append('categos',array('id'=>$cat->id(),'name'=>$cat->name(),'link'=>$link));			
		}
			
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
		$db = Database::getInstance();
		$sql = "SELECT * FROM ".$db->prefix('pw_works')." WHERE public=$public";
		$sql .= $category>0 ? " AND catego='$category'" : '';
        $sql .= $order!='' ? " ORDER BY id_work $order" : '';
		$sql.= " LIMIT 0,$limit";
		
		if ($xoopsModule && $xoopsModule->dirname()=='works'){
			$mc =& $xoopsModuleConfig;
		} else {
			$mc = RMUtils::moduleConfig('works');
		}
		
		$result = $db->query($sql);
		$works = array();
		while ($row = $db->fetchArray($result)){
			$work = new PWWork();
			$work->assignVars($row);
			$ret = array();

			if (!isset($categos[$work->category()])) $categos[$work->category()] = new PWCategory($work->category());

			if (!isset($clients[$work->client()])) $clients[$work->client()] = new PWClient($work->client());

			$link = PW_URL.($mc['urlmode'] ? '/'.$work->title_id().'/' : '/work.php?id='.$work->id());
			$link_cat = PW_URL.($mc['urlmode'] ? '/category/'.$categos[$work->category()]->nameId().'/' : '/catego.php?id='.$categos[$work->category()]->nameId());

			$ret = array(
				'id'=>$work->id(),
				'title'=>$work->title(),
				'desc'=>$work->descShort(),
				'catego'=>$categos[$work->category()]->name(),
				'client'=>$clients[$work->client()]->name(),
				'link'=>$link,
				'created'=>formatTimeStamp($work->created(),'s'),
				'image'=>XOOPS_UPLOAD_URL.'/works/ths/'.$work->image(),
				'rating'=>PWFunctions::rating($work->rating()),
				'featured'=>$work->mark(),
				'linkcat'=>$link_cat
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

}
