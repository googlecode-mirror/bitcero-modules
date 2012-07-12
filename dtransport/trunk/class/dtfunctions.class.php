<?php
// $Id$
// --------------------------------------------------------------
// D-Transport
// Manage files for download in XOOPS
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

        $item = rmc_server_var($_REQUEST, 'item', '');
        RMTemplate::get()->add_tool(__('Dashboard','dtransport'), 'index.php', '../images/dashboard.png', 'dashboard');

        if(RMCLOCATION=='items'){
            RMTemplate::get()->add_tool(__('Downloads','dtransport'), 'items.php', '../images/item.png', 'downitems');
            RMTemplate::get()->add_tool(__('New Download','dtransport'), 'items.php?action=new', '../images/new.png', 'newitem');
            RMTemplate::get()->add_tool(__('Waiting','dtransport'), 'items.php?type=wait', '../images/wait.png', 'itemswaiting');
            RMTemplate::get()->add_tool(__('Edited','dtransport'), 'items.php?type=edit', '../images/edited.png', 'itemsedited');
        } elseif(RMCLOCATION=='files'){
            RMTemplate::get()->add_tool(__('Downloads','dtransport'), 'items.php', '../images/item.png', 'items');
            RMTemplate::get()->add_tool(__('Files','dtransport'), 'files.php?item='.$item, '../images/files.png', 'fileslist');
            RMTemplate::get()->add_tool(__('Add file','dtransport'), 'files.php?action=new&amp;item='.$item, '../images/newfile.png', 'newfile');
        } elseif(RMCLOCATION=='screens'){
            RMTemplate::get()->add_tool(__('Downloads','dtransport'), 'items.php', '../images/item.png', 'items');
            RMTemplate::get()->add_tool(__('Screenshots','dtransport'), 'screens.php'.($item>0?'?item='.$item:''), '../images/shots.png', 'screenshots');
        } elseif(RMCLOCATION=='logs'){
            RMTemplate::get()->add_tool(__('Downloads','dtransport'), 'items.php', '../images/item.png', 'items');
            RMTemplate::get()->add_tool(__('Item Logs','dtransport'), 'logs.php'.($item>0?'?item='.$item:''), '../images/logs.png', 'itemlogs');
            RMTemplate::get()->add_tool(__('Add Log','dtransport'), 'logs.php?action=new&amp;item='.$item, '../images/addlog.png', 'newlog');
        } elseif(RMCLOCATION=='features'){
            RMTemplate::get()->add_tool(__('Downloads','dtransport'), 'items.php', '../images/item.png', 'items');
            RMTemplate::get()->add_tool(__('Features','dtransport'), 'features.php'.($item>0?'?item='.$item:''), '../images/features.png', 'showfeatures');
            RMTemplate::get()->add_tool(__('Add Feature','dtransport'), 'features.php?action=new&amp;item='.$item, '../images/addfeature.png', 'newfeature');
        } else {
            RMTemplate::get()->add_tool(__('Categories','dtransport'), 'categories.php', '../images/categories.png', 'categories');
            RMTemplate::get()->add_tool(__('Downloads','dtransport'), 'items.php', '../images/item.png', 'items');
            RMTemplate::get()->add_tool(__('Screenshots','dtransport'), 'screens.php', '../images/shots.png', 'screenshots');
            RMTemplate::get()->add_tool(__('Features','dtransport'), 'features.php', '../images/features.png', 'features');
            RMTemplate::get()->add_tool(__('Files','dtransport'), 'files.php', '../images/files.png', 'files');
            RMTemplate::get()->add_tool(__('Logs','dtransport'), 'logs.php', '../images/logs.png', 'logs');
            RMTemplate::get()->add_tool(__('Licences','dtransport'), 'licenses.php', '../images/license.png', 'licenses');
            RMTemplate::get()->add_tool(__('Platforms','dtransport'), 'platforms.php', '../images/os.png', 'platforms');
        }
        
    }
	/**
	* @desc Crea la cabecera del módulo
	*/
	public function makeHeader(){
		global $xoopsTpl, $xoopsUser;

        $func = RMUtilities::get();
        $mc = $func->module_config('dtransport');
		
		$xoopsTpl->assign('lang_mine', _MS_DT_MINE);
		if (DTFunctions::canSubmit()){
			$xoopsTpl->assign('lang_submit', _MS_DT_SUBMIT);
		}
		$xoopsTpl->assign('lang_search', _MS_DT_SEARCH);
		$xoopsTpl->assign('lang_recents', _MS_DT_RECENTS);
		$xoopsTpl->assign('lang_popular', _MS_DT_POPULAR);
		$xoopsTpl->assign('lang_bestrate', _MS_DT_BESTRATE);
		$xoopsTpl->assign('dtrans_title', $mc['title']);
		
		// Enlaces
		if ($mc['permalinks']){
			$xoopsTpl->assign('mine_link', DT_URL.'/mine/');
			$xoopsTpl->assign('recents_link', DT_URL.'/recents/');
			$xoopsTpl->assign('popular_link', DT_URL.'/popular/');
			$xoopsTpl->assign('rated_link', DT_URL.'/rated/');
		} else {
			$xoopsTpl->assign('mine_link', DT_URL.'/mydownloads.php');
			$xoopsTpl->assign('recents_link', DT_URL.'/search.php?order=created');
			$xoopsTpl->assign('popular_link', DT_URL.'/search.php?order=popular');
			$xoopsTpl->assign('rated_link', DT_URL.'/search.php?order=rateuser');
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
		
		$rtn = DTFunctions::ratingStars($rate);
		
		return $rtn;
		
	}
	
	/**
	* @desc Genera el div con la imágen de la calificación
	* @return string
	*/
	public function ratingStars($rate){
        global $xoopsConfig;

		$rtn = '<div class="dt-rating" title="'.sprintf(__('%s rating: %s','dtransport'), $xoopsConfig['sitename'], $rate/6).'">
					<div class="dt-rate-stars" style="width: '.$rate.'px;">
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
		$db = XoopsDatabaseFactory::getDatabaseConnection();
		
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
			DTFunctions::getCategos($categos, $jumps + 1, $row['id_cat'], $exclude, $asobj, $active);
		}
		
		return true;
	}

    /**
     * Get IDs from a category tree
     * @param array Array where ids will be stored
     * @param int Id of category where the search will start
     * @param bool Only select categories active or inactive
     */
    public function categoryTreeId(&$ids, $parent = 0, $active = true){
        $db = XoopsDatabaseFactory::getDatabaseConnection();

        $result = $db->query("SELECT id_cat FROM ".$db->prefix("dtrans_categos")." WHERE parent='$parent' ".($active ? ($active==2 ? "" : " AND active=1") : " AND active=0 ")." ORDER BY `id_cat`");

        if($parent>0) $ids[] = $parent;
        while (list($idcat) = $db->fetchRow($result)){
            //$ids[] = $idcat;
            $this->categoryTreeId($ids, $idcat, $active);
        }

        return true;
    }

    /**
     * Get the path for a category
     */
    public function category_path($id){

        if($id<=0)
            return false;

        $cat = new DTCategory($id);

        $path[] = $cat->nameId();

        if($cat->parent()<=0){return $path;}

        $path = array_merge($path, $this->category_path($cat->parent()));

        return $path;

    }

	/**
	* @desc Genera un array con los datos de un elemento específico
	* @param object {@link DTSoftware()}
	* @return array
	*/
	public function createItemData(DTSoftware &$item){
		global $mc, $xoopsUser;
		
	    $rmfunc = RMFunctions::get();

		$data = array();
		$data['link'] = $item->permalink();		// Vinculo para detalles
		$data['dlink'] = $item->permalink(0, 'download'); 	// Vinculo de descarga
		$data['id'] = $item->id();
		$data['name'] = $item->getVar('name');
		$data['description'] = $item->getVar('shortdesc');
		$data['votes'] = $item->getVar('votes');
        $data['comments'] = $item->getVar('comments');
		$data['siterate'] = DTFunctions::ratingStars($item->getVar('siterate')*6);
		$data['rating'] = @number_format($item->getVar('raring')/$item->getVar('votes'), 1);

        // Image
        $img = new RMImage();
        $img->load_from_params($item->getVar('image'));
		$data['image'] = $img->get_smallest();
		$data['created'] = formatTimestamp($item->getVar('created'),'s');
		if ($item->getVar('created')<$item->getVar('modified')){
			$data['modified'] = formatTimestamp($item->getVar('modified'), 's');
		}
		$data['is_new'] = $item->getVar('created')>(time()-($mc['new']*86400));
		$data['is_updated'] = $data['is_new'] ? false : $item->getVar('modified')>(time()-($mc['update']*86400));
		$data['approved'] = $item->getVar('approved');
		$data['downs'] = $item->getVar('hits');
		$data['screens'] = $item->getVar('screens');
		$data['featured'] = $item->getVar('featured');
		$data['nameid'] = $item->getVar('nameid');
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
			$rtn = DTFunctions::getCatLocation($parent);
		}
		
		$link = DT_URL.'/'.($mc['urlmode'] ? "category/".$cat->id() : "category.php?id=".$cat->id());
		
		$rtn .= ($rtn !='' ? " &raquo; " : "")."<a href='$link'>".$cat->name()."</a>";
		return $rtn;
		
	}
	
	/**
	* @desc Determina el tiempo transcurrido del envio de una alerta al tiempo actual
	**/
	function checkAlert(){

		global $xoopsModuleConfig,$db,$xoopsConfig;

        $db = XoopsDatabaseFactory::getDatabaseConnection();
	
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
				
				if (!$sw->getVar('approved')) continue;

				if (!$alert->lastActivity()){
					if($sw->getVar('created')>=time()-$alert->limit()*86400){
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

    /**
     * Send a message in json format
     * @param string Message to be sent
     * @param int Indicates if message is an error
     * @param int Indicates if token must be sent
     */
    public function dt_send_message($message, $e = 0, $t = 1){
        global $xoopsSecurity;

        if($e){
            $data = array(
                'message' => $message,
                'error' => 1,
                'token' => $t?$xoopsSecurity->createToken():''
            );
        } else {

            $data = array(
                'error' => 0,
                'token' => $t?$xoopsSecurity->createToken():'',
            );
            $data = array_merge($data, $message);
        }

        echo json_encode($data);
        die();

    }

    /**
     * Show the meta values for a specific element
     * @param string Element type
     * @param object
     */
    public function meta_form($type, $id = 0, RMForm &$form = null){

        $tpl = RMTemplate::get();

        // Get existing metas
        $db = XoopsDatabaseFactory::getDatabaseConnection();
        $sql = "SELECT `name` FROM ".$db->prefix("dtrans_meta")." WHERE type='$type'";
        $result = $db->query($sql);

        $metaNames = array();
        $metas = array();

        while($row = $db->fetchArray($result)){
            $metaNames[] = $row['name'];
        }

        if($id>0){
            $sql = "SELECT * FROM ".$db->prefix("dtrans_meta")." WHERE type='$type' AND id_element='$id'";
            $result = $db->query($sql);

            while($row = $db->fetchArray($result)){
                $metas[] = $row;
            }
        }

        $tpl->add_style('metas.css', 'dtransport');
        $tpl->add_local_script('metas.js', 'dtransport');
        include_once DT_PATH.'/include/js_strings.php';

        ob_start();
        include $tpl->get_template("admin/dtrans_metas.php", 'module', 'dtransport');
        $metas = ob_get_clean();

        if($form){

            $form->addElement(new RMFormLabel(__('Custom Fields','dtransport'), $metas))->setDescription(__('Custom fields allows you to add extra information to elements, tah can be used on templates, plugins or another elements.','dtransport'));
            return true;

        }

        return $metas;

    }

    /**
     * Save meta data for elements
     */
    public function save_meta($type, $id){

        $metas = rmc_server_var($_REQUEST, 'dtMetas', array());

        $db = XoopsDatabaseFactory::getDatabaseConnection();

        $db->queryF("DELETE FROM ".$db->prefix("dtrans_meta")." WHERE `type`='$type' AND id_element=$id");

        if(empty($metas)) return false;

        $sql = "INSERT INTO ".$db->prefix("dtrans_meta")." (`type`,`id_element`,`name`,`value`) VALUES ";
        foreach($metas as $meta){
            $sql .= "('$type','$id','$meta[name]','$meta[value]'),";
        }

        $sql = rtrim($sql, ',');

        return $db->queryF($sql);

    }

    /**
     * Get featured items
     * @param int Allows to select items from a specific category
     * @param bool Determines if items will be assigned to a smarty variable
     * @param string Smarty varibale name. Useful when assign is true
     * @return array
     */
    public function featured_items($cat = 0, $assign = true, $var = 'featured_items'){
        global $xoopsTpl, $mc;

        // Descargas destacadas
        if($assign){
            $xoopsTpl->assign('lang_featured',__('<strong>Featured</strong> Downloads','dtransport'));
            $xoopsTpl->assign('lang_incatego', __('In <a href="%s">%s</a>','dtransport'));
            $xoopsTpl->assign('show_featured', $mc['dest_download']);
        }

        $items = array();
        $db = XoopsDatabaseFactory::getDatabaseConnection();

        if($cat>0){

            // Categories under current category
            $categos = array();
            $this->categoryTreeId($categos, $cat);

            $sql = "SELECT a.*, b.*, c.name AS namecat, c.id_cat
                    FROM ".$db->prefix("dtrans_software")." AS a, ".$db->prefix("dtrans_catsoft")." AS b
                    JOIN ".$db->prefix("dtrans_categos")." AS c
                    WHERE b.cat IN(".implode(",",$categos).") AND a.id_soft=b.soft AND a.approved=1 AND featured=1 AND c.id_cat=b.cat
                    GROUP BY b.soft
                    ORDER BY RAND() LIMIT 0,$mc[limit_destdown]";
        } else {
            $sql = "SELECT a.*, c.name as namecat, c.id_cat
                    FROM ".$db->prefix("dtrans_software")." AS a, ".$db->prefix("dtrans_catsoft")." AS b
                    JOIN ".$db->prefix("dtrans_categos")." AS c
                    WHERE a.approved='1' AND a.featured='1' AND a.id_soft=b.soft AND c.id_cat=b.cat
                    GROUP BY b.soft
                    ORDER BY RAND() LIMIT 0,$mc[limit_destdown]";
        }

        $result = $db->query($sql);
        while ($row = $db->fetchArray($result)){
            $item = new DTSoftware();
            $item->assignVars($row);
            $ocat = new DTCategory($row['id_cat']);
            $items[] = array_merge($this->createItemData($item), array('category'=>$row['namecat'],'categoryid'=>$row['id_cat'], 'categorylink'=>$ocat->permalink()));
        }

        if($assign)
            $xoopsTpl->assign($var, $items);

        return $items;
    }

    /**
     * Get recent items
     */
    public function recent_items($cat = 0, $assign = true, $var = 'recent_items'){
        global $xoopsTpl, $mc;

        $items = array();
        $db = XoopsDatabaseFactory::getDatabaseConnection();

        if($assign){
            $xoopsTpl->assign('lang_comments', __('%u Comments','dtransport'));
        }

        if($cat>0){

            // Categories under current category
            $categos = array();
            $this->categoryTreeId($categos, $cat);

            $sql = "SELECT a.*, b.*, c.name AS namecat, c.id_cat FROM ".$db->prefix("dtrans_software")." AS a, ".$db->prefix("dtrans_catsoft")." AS b
                    JOIN ".$db->prefix("dtrans_categos")." AS c
                    WHERE b.cat IN(".implode(",",$categos).") AND a.id_soft=b.soft AND a.approved=1 AND c.id_cat=b.cat
                    GROUP BY b.soft
                    ORDER BY a.created DESC LIMIT 0,$mc[limit_recents]";
        } else {
            $sql = "SELECT a.*, c.name as namecat, c.id_cat
                    FROM ".$db->prefix("dtrans_software")." AS a, ".$db->prefix("dtrans_catsoft")." AS b
                    JOIN ".$db->prefix("dtrans_categos")." AS c
                    WHERE approved='1' AND a.id_soft=b.soft AND c.id_cat=b.cat
                    GROUP BY b.soft
                    ORDER BY modified DESC LIMIT 0,".($mc['limit_recents']>0 ? $mc['limit_recents'] : '10');
        }

        $result = $db->query($sql);
        while ($row = $db->fetchArray($result)){
            $item = new DTSoftware();
            $item->assignVars($row);
            $ocat = new DTCategory($row['id_cat']);
            $items[] = array_merge($this->createItemData($item), array('category'=>$row['namecat'],'categoryid'=>$row['id_cat'], 'categorylink'=>$ocat->permalink()));
        }

        if($assign)
            $xoopsTpl->assign($var, $items);

        return $items;

    }
	
}
