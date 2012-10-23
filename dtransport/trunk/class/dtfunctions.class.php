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
	public function makeHeader($title=''){
		global $xoopsTpl, $xoopsUser;

        $func = RMUtilities::get();
        $mc = $func->module_config('dtransport');

        $xoopsTpl->assign('dt_header_title', $title);

        $header = array(
            'cansubmit' => $this->canSubmit(),
            'searchlink' => $mc['permalinks'] ? DT_URL.'/'.trim($mc['htbase'], '/').'/search/' : DT_URL.'/?p=search'
        );

        $header['links'][] = array(
            'title' => __('Downloads','dtransport'),
            'link' => DT_URL
        );

        $header['links'][] = array(
            'title' => __('My Downloads','dtransport'),
            'link' => $mc['permalinks'] ? DT_URL.'/cp/' : DT_URL.'/?p=cpanel'
        );

        if ($this->canSubmit()){
            $header['links'][] = array(
                'title' => __('Submit Download','dtransport'),
                'link' => $mc['permalinks'] ? DT_URL.'/submit/' : DT_URL.'/?p=explore&amp;f=submit'
            );
        }

        $header['links'][] = array(
            'title' => __('Recent Downloads','dtransport'),
            'link' => $mc['permalinks'] ? DT_URL.'/recent/' : DT_URL.'/?p=explore&amp;f=recent'
        );

        $header['links'][] = array(
            'title' => __('Popular Downloads','dtransport'),
            'link' => $mc['permalinks'] ? DT_URL.'/popular/' : DT_URL.'/?p=explore&amp;f=popular'
        );

        $header['links'][] = array(
            'title' => __('Best Rated','dtransport'),
            'link' => $mc['permalinks'] ? DT_URL.'/rated/' : DT_URL.'/?p=explore&amp;f=rated'
        );

        $ev = RMEvents::get();
        $header = $ev->run_event("dtransport.make.header", $header);

        $xoopsTpl->assign('header', $header);

        $tpl = RMTemplate::get();
        $tpl->add_xoops_style('main.css','dtransport');

        return true;

	}
    
    /**
    * Create the header for control panel
    */
    public function cpHeader($item=null, $title=''){
        global $xoopsTpl, $mc;
        
        $links[] = array(
            'title' => __('My Downloads','dtransport'),
            'link'  => DT_URL.($mc['permalinks'] ? '/cp/' : '/?p=cpanel')
        );
        
        if($item){
            
            $links[] = array(
                'title' => __('Files','dtransport'),
                'link'  => DT_URL.($mc['permalinks'] ? '/cp/files/'.$item->id().'/' : '/?p=cpanel&amp;action=files&amp;id='.$item->id())
            );
            
            $links[] = array(
                'title' => __('Screenshots','dtransport'),
                'link'  => DT_URL.($mc['permalinks'] ? '/cp/screens/'.$item->id().'/' : '/?p=cpanel&amp;action=screens&amp;id='.$item->id())
            );
            
            $links[] = array(
                'title' => __('Features','dtransport'),
                'link'  => DT_URL.($mc['permalinks'] ? '/cp/features/'.$item->id().'/' : '/?p=cpanel&amp;action=features&amp;id='.$item->id())
            );
            
            $links[] = array(
                'title' => __('Logs','dtransport'),
                'link'  => DT_URL.($mc['permalinks'] ? '/cp/logs/'.$item->id().'/' : '/?p=cpanel&amp;action=logs&amp;id='.$item->id())
            );
            
        }
        
        $xoopsTpl->assign('header_elements',array('title'=>$title, 'links'=>$links));
        
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

        $rate = $rate*7.5;

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
	public function getCategos(&$categos, $jumps = 0, $parent = 0, $exclude = array(), $asobj = false, $active=1){
		$db = XoopsDatabaseFactory::getDatabaseConnection();
		
		if (!is_array($exclude)) $exclude = array($exclude);		
		$result = $db->query("SELECT * FROM ".$db->prefix("dtrans_categos")." WHERE parent='$parent' ".($active ? ($active==2 ? "" : " AND active=1") : " AND active=0 ")." ORDER BY `id_cat`");
		
		
		while ($row = $db->fetchArray($result)){

			if (is_array($exclude) && (in_array($row['parent'], $exclude) || in_array($row['id_cat'], $exclude))){
				$exclude[] = $row['id_cat'];
			} else {
                $cat = new DTCategory();
                $cat->assignVars($row);
                $rtn = array();
				if ($asobj){
					$rtn['object'] = $cat;
					$rtn['jumps'] = $jumps;
				} else {
					$rtn = $row;
					$rtn['jumps'] = $jumps;
                    $rtn['link'] = $cat->permalink();
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
		
        if(!$mc)
            $mc = RMUtilities::module_config('dtransport');
	    $rmfunc = RMFunctions::get();

		$data = array();
		$data['link'] = $item->permalink();		// Vinculo para detalles
		$data['dlink'] = $item->permalink(0, 'download'); 	// Vinculo de descarga
		$data['id'] = $item->id();
		$data['name'] = $item->getVar('name');
		$data['description'] = $item->getVar('shortdesc');
		$data['votes'] = $item->getVar('votes');
        $data['comments'] = $item->getVar('comments');
		$data['siterate'] = DTFunctions::ratingStars($item->getVar('siterate'));
		$data['rating'] = @number_format($item->getVar('rating')/$item->getVar('votes'), 1);
        $data['language'] = $item->getVar('langs');
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
		
        $data['metas'] = $this->get_metas('down', $item->id());
        
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
    * Obtiene los campos personalizados de un elemento
    */
    public function get_metas($type, $id, $all = false){
        // Get existing metas
        $db = XoopsDatabaseFactory::getDatabaseConnection();
        $sql = "SELECT * FROM ".$db->prefix("dtrans_meta")." WHERE type='$type' AND id_element='$id'";
        $result = $db->query($sql);

        while($row = $db->fetchArray($result)){
            if($all)
                $metas[] = $row;
            else
                $metas[$row['name']] = $row['value'];
        }
        
        return $metas;
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
            if(!in_array($row['name'], $metaNames))
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

        if(empty($metas)) return true;

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
     * @param string Smarty varibale name. Useful when assign is true
     * @param string Type of items (all, featured, recent, daily, rated, updated
     * @return array
     */
    public function get_items($cat = 0, $type='all', $limit=10){
        global $xoopsTpl, $mc;

        $items = array();
        $db = XoopsDatabaseFactory::getDatabaseConnection();

        switch($type){
            case 'featured':
                $filter = "a.approved=1 AND a.featured=1 AND a.delete=0";
                $order = "ORDER BY RAND()";
                break;
            case 'recent':
                $filter = "a.approved=1 AND a.delete=0";
                $order = "ORDER BY a.created DESC";
                break;
            case 'daily':
                $filter = "a.approved=1 AND a.daily=1 AND a.delete=0";
                $order = "ORDER BY RAND()";
                break;
            case 'rated':
                $filter = "a.approved=1 AND a.delete=0";
                $order = "ORDER BY a.rating DESC";
                break;
            case 'updated':
                $filter = "a.approved=1 AND a.delete=0";
                $order = "ORDER BY a.modified DESC";
                break;
            default:
                $filter = 'a.delete=0';
                $order = "ORDER BY created DESC";
                break;
        }

        if($cat>0){
            // Categories under current category
            $categos = array();
            $this->categoryTreeId($categos, $cat);

            $sql = "SELECT a.*, b.*, c.name AS namecat, c.id_cat
                    FROM ".$db->prefix("dtrans_software")." AS a, ".$db->prefix("dtrans_catsoft")." AS b
                    JOIN ".$db->prefix("dtrans_categos")." AS c
                    WHERE b.cat IN(".implode(",",$categos).") AND a.id_soft=b.soft AND $filter AND c.id_cat=b.cat
                    GROUP BY b.soft
                    $order LIMIT 0,$limit";
        } else {
            $sql = "SELECT a.*, c.name as namecat, c.id_cat
                    FROM ".$db->prefix("dtrans_software")." AS a, ".$db->prefix("dtrans_catsoft")." AS b
                    JOIN ".$db->prefix("dtrans_categos")." AS c
                    WHERE $filter AND a.id_soft=b.soft AND c.id_cat=b.cat
                    GROUP BY b.soft
                    $order LIMIT 0,$limit";
        }

        $result = $db->query($sql);
        while ($row = $db->fetchArray($result)){
            $item = new DTSoftware();
            $item->assignVars($row);
            $ocat = new DTCategory($row['id_cat']);
            $items[] = array_merge($this->createItemData($item), array('category'=>$row['namecat'],'categoryid'=>$row['id_cat'], 'categorylink'=>$ocat->permalink()));
        }

        return $items;
    }

    /**
     * Get items by tag(s)
     */
    public function items_by($elements, $by, $exclude=0, $type = 'all', $start=0, $limit=10){

        if(!is_array($elements) AND $elements<=0)
            return;

        if(!is_array($elements))
            $elements = array($elements);
            
        switch($type){
            case 'featured':
                $filter = "s.approved=1 AND s.featured=1 AND s.delete=0";
                $order = "ORDER BY RAND()";
                break;
            case 'recent':
                $filter = "s.approved=1 AND s.delete=0";
                $order = "ORDER BY s.created DESC";
                break;
            case 'daily':
                $filter = "s.approved=1 AND s.daily=1 AND s.delete=0";
                $order = "ORDER BY RAND()";
                break;
            case 'rated':
                $filter = "s.approved=1 AND s.delete=0";
                $order = "ORDER BY s.rating DESC";
                break;
            case 'updated':
                $filter = "s.approved=1 AND s.delete=0";
                $order = "ORDER BY s.modified DESC";
                break;
            default:
                $filter = 's.approved=1 AND s.delete=0';
                $order = "ORDER BY created DESC";
                break;
        }

        $db = XoopsDatabaseFactory::getDatabaseConnection();
        $softt = $db->prefix('dtrans_software');
        
        switch($by){
            case 'tags':
                $byt = $db->prefix('dtrans_softtag');
                $sql = "SELECT s.* FROM $softt AS s, $byt AS t WHERE t.id_tag IN (".implode(",", $elements).") AND t.id_soft!=$exclude AND s.id_soft=t.id_soft AND $filter GROUP BY t.id_soft $order LIMIT $start, $limit";
                break;
            case 'platforms':
                $byt = $db->prefix('dtrans_platsoft');
                $sql = "SELECT s.* FROM $softt AS s, $byt AS t WHERE t.id_platform IN (".implode(",", $elements).") AND t.id_soft!=$exclude AND s.id_soft=t.id_soft AND $filter GROUP BY t.id_soft $order LIMIT $start, $limit";
                break;
            case 'licenses':
                $byt = $db->prefix('dtrans_licsoft');
                $sql = "SELECT s.* FROM $softt AS s, $byt AS t WHERE t.id_lic IN (".implode(",", $elements).") AND t.id_soft!=$exclude AND s.id_soft=t.id_soft AND $filter GROUP BY t.id_soft $order LIMIT $start, $limit";
                break;
        }

        $result = $db->query($sql);
        $items = array();
        while ($row = $db->fetchArray($result)){
            $item = new DTSoftware();
            $item->assignVars($row);
            $cats = $item->categories(true);
            $cat = $cats[array_rand($cats, 1)];
            $items[] = array_merge($this->createItemData($item), array('category'=>$cat->name(),'categoryid'=>$cat->id(), 'categorylink'=>$cat->permalink()));
        }

        return $items;

    }

    /**
     * Envia un encabezado statuis 404 al navegador
     */
    /**
     * Generate an 404 error
     */
    function error_404(){
        header("HTTP/1.0 404 Not Found");
        if (substr(php_sapi_name(), 0, 3) == 'cgi')
            header('Status: 404 Not Found', TRUE);
        else
            header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');

        echo "<h1>".__('ERROR 404. Document not Found','docs')."</h1>";
        die();
    }
}
