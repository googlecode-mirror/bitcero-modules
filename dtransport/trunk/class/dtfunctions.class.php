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
* @desc Funciones utilizadas en el módulo
*/
class DTFunctions
{
    /**
     * Create the toolbar for module
     */
    public function toolbar(){
        
        RMTemplate::get()->add_tool(__('Dashboard','dtransport'), 'index.php', '../images/dashboard.png', 'dashboard');
        RMTemplate::get()->add_tool(__('Categories','dtransport'), 'categories.php', '../images/categories.png', 'categories');
        RMTemplate::get()->add_tool(__('Downloads','dtransport'), 'items.php', '../images/item.png', 'items');
        RMTemplate::get()->add_tool(__('Screenshots','dtransport'), 'screens.php', '../images/shots.png', 'screenshots');
        RMTemplate::get()->add_tool(__('Features','dtransport'), 'features.php', '../images/features.png', 'features');
        RMTemplate::get()->add_tool(__('Files','dtransport'), 'files.php', '../images/files.png', 'files');
        RMTemplate::get()->add_tool(__('Logs','dtransport'), 'logs.php', '../images/logs.png', 'logs');
        RMTemplate::get()->add_tool(__('Licences','dtransport'), 'licenses.php', '../images/license.png', 'licenses');
        RMTemplate::get()->add_tool(__('Platforms','dtransport'), 'platforms.php', '../images/os.png', 'platforms');
        
    }
	/**
	* @desc Crea la cabecera del módulo
	*/
	public function makeHeader(){
		global $tpl, $mc, $xoopsUser;
		
		$tpl->assign('lang_mine', _MS_DT_MINE);
		if (DTFunctionsHandler::canSubmit()){
			$tpl->assign('lang_submit', _MS_DT_SUBMIT);
		}
		$tpl->assign('lang_search', _MS_DT_SEARCH);
		$tpl->assign('lang_recents', _MS_DT_RECENTS);
		$tpl->assign('lang_popular', _MS_DT_POPULAR);
		$tpl->assign('lang_bestrate', _MS_DT_BESTRATE);
		$tpl->assign('dtrans_title', $mc['title']);
		
		// Enlaces
		if ($mc['urlmode']){
			$tpl->assign('mine_link', DT_URL.'/mine/');
			$tpl->assign('recents_link', DT_URL.'/recents/');
			$tpl->assign('popular_link', DT_URL.'/popular/');
			$tpl->assign('rated_link', DT_URL.'/rated/');
		} else {
			$tpl->assign('mine_link', DT_URL.'/mydownloads.php');
			$tpl->assign('recents_link', DT_URL.'/search.php?order=created');
			$tpl->assign('popular_link', DT_URL.'/search.php?order=popular');
			$tpl->assign('rated_link', DT_URL.'/search.php?order=rateuser');
		}
	}
	
	/**
	* @desc Comprueba si el usuario actual tiene permisos de envio
	* @return bool
	*/
	public function canSubmit(){
		global $xoopsUser, $mc;
		
		if (!$mc['send_download']) return false;
		
		if (in_array(0, $mc['groups_send'])) return true;
		
		if (!$xoopsUser) return false;
		
		if ($xoopsUser->isAdmin()) return true;
		
		foreach ($xoopsUser->getGroups() as $k){
			if (in_array($k, $mc['groups_send'])) return true;
		}
		
	}
	
	/**
	* @desc Calcula el rating en base a los votos y el rating de la tabla
	* @param int Cantidad de Votos
	* @param int Rating Total
	* @return string
	*/
	public function createRatingGraph($votes, $rating){
		
		if ($votes<=0 || $rating<=0){
			$rate = 0;
		} else {
			$rate = (($rating/$votes)*6);
		}
		
		$rtn = DTFunctionsHandler::ratingStars($rate);
		
		return $rtn;
		
	}
	
	/**
	* @desc Genera el div con la imágen de la calificación
	* @return string
	*/
	public function ratingStars($rate){
		$rtn = '<div class="dtRating">
					<div class="dtRateStars" style="width: '.$rate.'px;">
					&nbsp;
					</div>
				</div>';
		return $rtn;
	}
	
	/**
	* @desc Genera el arreglo con las categorías existentes en la base de datos
	* @param array Referencia al array a rellenar
	* @param int Espacios en el árbol
	* @param int Identificar de la Categoría padre
	* @param int, array Identificador de la Categoría a ignorar (Junto con sus subcategoría)
	* @param bool True devuelve objetos {@link DTCategory}
	* @param int Especifica si se buscaran catagorias inactivas(0), activas(1), todas(2)
	* @return array
	*/
	public function getCategos(&$categos, $jumps = 0, $parent = 0, $exclude = array(), $asobj = false, $active=2){
		$db = Database::getInstance();
		
		if (!is_array($exclude)) $exclude = array($exclude);		
		$result = $db->query("SELECT * FROM ".$db->prefix("dtrans_categos")." WHERE parent='$parent' ".($active ? ($active==2 ? "" : " AND active=1") : " AND active=0 ")." ORDER BY `id_cat`");
		
		
		while ($row = $db->fetchArray($result)){
			if (is_array($exclude) && (in_array($row['parent'], $exclude) || in_array($row['id_cat'], $exclude))){
				$exclude[] = $row['id_cat'];
			} else {
				$rtn = array();
				if ($asobj){
					$rtn['object'] = new DTCategory();
					$rtn['object']->assignVars($row);
					$rtn['jumps'] = $jumps;
				} else {
					$rtn = $row;
					$rtn['jumps'] = $jumps;
				}
				$categos[] = $rtn;
			}
			DTFunctionsHandler::getCategos($categos, $jumps + 1, $row['id_cat'], $exclude, $asobj, $active);
		}
		
		return true;
	}
	
	/**
	* @desc Genera la ruta para la URL de una categoría
	* @param array Array de Categorías
	* @param int Indice del array que contiene a la categoría
	* @param bool Indica si el array contiene objetos
	* return string
	*/
	function creatPath(&$categos, $index, $obj = false){
		
		if (!isset($categos[$index])) return '';
		
		if ($obj){
			$cat = &$categos[$index]['object'];
		} else {
			$cat = new DTCategory();
			$cat->assignVars($categos[$index]);
		}
		
		$path = '';
		
		if ($categos[$index]['jumps']>0){
			$path = DTFunctionsHandler::creatPath($categos, $index-1, $obj);
		}
		
		return $path.'/'.$cat->nameId();
		
	}
	
	/**
	* @desc Genera un array con los datos de un elemento específico
	* @param object {@link DTSoftware()}
	* @return array
	*/
	public function createItemData(DTSoftware &$item){
		global $mc, $xoopsUser;
		
		$link = $mc['urlmode'] ? DT_URL .'/item/'.$item->nameId().'/' :  DT_URL .'/item.php?id='.$item->id();
		$dlink = $link . ($mc['urlmode'] ? 'download/' :  '/download');
		$slink = $link . ($mc['urlmode'] ? 'screens/' :  '/screens');
		
		$data = array();
		$data['link'] = $link;		// Vinculo para detalles
		$data['dlink'] = $dlink; 	// Vinculo de descarga
		$data['slink'] = $slink;	// Vinculo para imágenes
		$data['id'] = $item->id();
		$data['name'] = $item->name();
		$data['desc'] = $item->shortdesc();
		$data['votes'] = $item->votes();
		$data['siterate'] = DTFunctionsHandler::ratingStars($item->siteRating()*6);
		$data['rating'] = @number_format($item->rating()/$item->votes(), 1);
		$data['img'] = $item->image();
		$data['created'] = formatTimestamp($item->created(),'s');
		if ($item->created()<$item->modified()){
			$data['modified'] = formatTimestamp($item->modified(), 's');
		}
		$data['is_new'] = $item->created()>(time()-($mc['new']*86400));
		$data['is_updated'] = $data['is_new'] ? false : $item->modified()>(time()-($mc['update']*86400));
		$data['approved'] = $item->approved();
		$data['downs'] = $item->hits();
		$data['screens'] = $item->screensCount();
		$data['featured'] = $item->mark();
		$data['nameid'] = $item->nameId();
		$data['candownload'] = $item->canDownload($xoopsUser ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS);
		
		// Licencias
		$data['lics'] = '';
		foreach ($item->licences(true) as $lic){
			$data['lics'] .= $data['lics']=='' ? '<a href="'.$lic->link().'" target="_blank">'.$lic->name().'</a>' : ', <a href="'.$lic->link().'" target="_blank">'.$lic->name().'</a>';
		}
		
		//  Plataformas
		$data['os'] = '';
		foreach ($item->platforms(true) as $os){
			$data['os'] .= $data['os']=='' ? $os->name() : ', '.$os->name();
		}
		
		return $data;
		
	}
	
	/**
	* @desc Genera la barra de navegación para el listado de descargas
	*/
	public function createNavigation($total, $xpage, $pactual){
		global $tpl;
		
		if ( $total <= $xpage ) {
			return;
		}
        $tpages = ceil($total / $xpage);
        
        if ($tpages <= 1) return;
        
        $prev = $pactual - 1;
    
        if ($pactual > 1){
            if ($pactual>4 && $tpages > 5){
                /**
                * Si la página actual es mayor que 2 y el numero total de 
                * página es mayor que once entonces podemos mostrar la imágen
                * "Primer Página" de lo contario no tiene caso tener este botón
                */
                $tpl->append('dtNavPages', array('id'=>'first', 'num'=>1));
            }
            /**
            * Si la página actual es mayor que uno entonces mostramos
            * la imágen "Página Anterior"
            */
            $tpl->append('dtNavPages', array('id'=>'previous', 'num'=>($pactual-1)));
        }
        
        // Identificamos la primer página y la última página
        $pstart = $pactual-4>0 ? $pactual-4+1 : 1;
        $pend = ($pstart + 6)<=$tpages ? ($pstart + 6) : $tpages;
        
        if ($pstart > 3 && $tpages>4+1+3){
            $tpl->append('dtNavPages', array('id'=>3,'salto'=>1,'num'=>3));
        }
        
        if ($tpages > 0){
            for ($i=$pstart;$i<=$pend;$i++){
                $tpl->append('dtNavPages', array('id'=>$i,'num'=>$i));
            }
        }
        
        if ($pend < $tpages-3 && $tpages>11){
            $tpl->append('dtNavPages', array('id'=>$tpages-3,'salto'=>2,'num'=>($tpages - 3)));
        }
        
        if ($pactual < $tpages && $tpages > 1){
            $tpl->append('dtNavPages', array('id'=>'next', 'num'=>($pactual+1)));
            if ($pactual < $tpages-1 && $tpages > 11){
                $tpl->append('dtNavPages', array('id'=>'last', 'num'=>$tpages));
            }
        }
        
        $tpl->assign('dtTotalPages', $tpages);
        
	}
	
	/**
	* @desc Obtiene la localización de una categoría
	*/
	public function getCatLocation(DTCategory &$cat){
		global $mc;
		
		$rtn = '';
		if ($cat->parent()!=0){
			$parent = new DTCategory($cat->parent());
			$rtn = DTFunctionsHandler::getCatLocation($parent);
		}
		
		$link = DT_URL.'/'.($mc['urlmode'] ? "category/".$cat->id() : "category.php?id=".$cat->id());
		
		$rtn .= ($rtn !='' ? " &raquo; " : "")."<a href='$link'>".$cat->name()."</a>";
		return $rtn;
		
	}
	
	/**
	* @desc Determina el tiempo transcurrido del envio de una alerta al tiempo actual
	**/
	function checkAlert(){

		global $xoopsModuleConfig,$db,$xoopsConfig, $mc;
	
		$file = XOOPS_ROOT_PATH."/cache/alerts.php";

		$datelast = file_get_contents($file);
		
		if($datelast<=time()-$xoopsModuleConfig['hrs_alerts']*86400){
			//Ejecutamos verificación de alertas
			$sql = "SELECT * FROM ".$db->prefix('dtrans_alerts');
			$result = $db->query($sql);
			while ($rows = $db->fetchArray($result)){
				$alert = new DTAlert();
				$alert->assignVars($rows);

				//Obtenemos los datos de la descarga
				$sw = new DTSoftware($alert->software());
				
				if (!$sw->approved()) continue;

				if (!$alert->lastActivity()){
					if($sw->created()>=time()-$alert->limit()*86400){
						continue;
					}
				}


				//Verificamos la fecha de la última descarga del modulo
				if ($alert->lastActivity()<=time()-$alert->limit()*86400){
					
					if($alert->alerted() && ($alert->alerted()<$alert->lastActivity()+$alert->limit()*86400)){
						continue;
					}
					
					$errors='';
					//Enviamos alerta al autor de la descarga
					$xoopsMailer =& getMailer();
					$alert->mode() ? $xoopsMailer->useMail() : $xoopsMailer->usePM();
					$xoopsMailer->setTemplate('alert.tpl');
					$xoopsMailer->assign('SITENAME', $xoopsConfig['sitename']);
					$xoopsMailer->assign('ADMINMAIL', $xoopsConfig['adminmail']);
					$xoopsMailer->assign('SITEURL', XOOPS_URL."/");
					$xoopsMailer->assign('DOWNLOAD', $sw->name());
					if ($xoopsModuleConfig['urlmode']){
						$url = DT_URL."/item/".$sw->nameId();

					}else{
						$url = DT_URL."/item.php?id=".$sw->id();
					}
					
					$xoopsMailer->assign('LINK_RESOURCE',$url);
					$xoopsMailer->assign('DAYS',$alert->limit());
					$xoopsMailer->setTemplateDir(XOOPS_ROOT_PATH."/modules/dtransport/language/".$xoopsConfig['language']."/mail_template/");
					$xu = new XoopsUser($sw->uid());
					$xoopsMailer->setToUsers($xu);
					$xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
					$xoopsMailer->setFromName($xoopsConfig['sitename']);
					$xoopsMailer->setSubject(sprintf(_MS_DT_SUBJECTALERT,$sw->name()));
					$xoopsMailer->send(true);

				}
		
			}	
			//Almacenamos la fecha de la última verificación de alertas
			file_put_contents($file,time());			

		}else{
			return false;
		}
		

	}
	
}
