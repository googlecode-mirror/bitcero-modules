<?php
// $Id: events.php 22 2009-09-13 07:42:57Z i.bitcero $
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

if (!defined('XOOPS_ROOT_PATH')) die("Sorry, there are not <a href='../'>nothing here</a>");

//include_once 'langs/english.php';

/**
* EXM System API
* 
* Todas las funciones para el control de al api estan incluidas en este archivo.
* Estas funciones permiten generar eventos, y sus funciones para capturarlos de manera
* que dichas funciones serán llamadas en el momento de ejecutar el evento.
* 
* Para registrar eventos y métodos primero es necesario registrar los objetos
* que no son otra cosa que módulos, plugins o cualquier componente que
* funciones bajo EXM System
* 
* api_register_object('nombre','ruta_del_objeto');
* 
* Después de registrar un objeto podremos registrar eventos y métodos para el.
* Los eventos llaman a los métodos (funciones de captura de eventos) para poder
* interactuar y modificar valores pasados por referencia
* 
* api_register_event('nombre_del_evento','nombre_de_objeto',['archivo_controlador']);
* 
* Nota: El archivo controlador en los eventos es opcional
* Nota: Actualmente el archivo controlador no es utilizado
* 
* api_register_method('nombre_de_la_funcion','nombre_del_evento','archivo_de_la_función','nombre_del_objeto')
* 
* LLAMAR A UN EVENTO
* 
* Para ejecutar un evento y los métodos asociados a el basta con hacer una llamada a la función:
* 
* EXMEventApi::get()->run_event('nombre_del_evento', 'nombre_del_objeto', parámetros);
* 
*/
include_once RMCPATH.'/class/textcleaner.php';

class RMEventsApi
{
	/**
	* @desc Almacena toda la información de la API
	*/
	private $api_events = array();
	/**
	* Store all errors generated in this clas
	*/
	private $errors = array();
	/**
	* Api events cache file
	*/
	private $api_file = '';
	
	/**
	* Class constructor
	* This method reads all api events according two options:
	*   1. If file for cache exists then reads this file
	*   2. If file does not exists then reads database for events
	* The data will be stored in array $this->api_events_events
	*/
	public function __construct(){
		global $rmc_config;
		
		$this->api_file = $rmc_config['eventsfile'];

		if (!file_exists($this->api_file)){
			$this->rebuild_cache();
		}else{
			$this->from_file($this->api_file);
		}
	}
	
	/**
	* Read the cache file and generate the api
	*/
	private function from_file(){
		$file = $this->api_file;
		$this->api_events = json_decode(TextCleaner::decrypt(file_get_contents($file)), true);
		return true;
		
	}
	
	/**
	* Read the api events from db
	*/
	private function from_db(){
		
		if (!class_exists("Database")) return array();
	    
	    if (FALSE === $db =& Database::getInstance()) return array();
	    
	    $sql = "SELECT * FROM ".$db->prefix("api_events");
	    $result = $db->query($sql);
	    
	    while ($row = $db->fetchArray($result)){
    		$methods = $this->load_methods($row['eid']);
    		if (empty($methods)) continue;
	        $this->api_events[$row['event']] = array('id'=>$row['id_event'], 'methods'=>$methods);
	    }
	    
	    return true;
	    
	}
	
	/**
	* Get an singleton instance for events api
	*/
	public function get(){
		static $instance;
		
		if (isset($instance))
			return $instance;
		
		$instance = new RMEventsApi();
		return $instance;
	}

	/**
	 * This method allows to EXM register a new object for api events
	 * 
	 * Objects are system components, such as plugins, apps, blocks, themes, etc
	 * and this method can register these and activate their events
	 * 
	 * @param string Object name (no blank spaces and special chars)
	 * @param string Object type (app, theme, plugin, etc)
	 * @param string Object location realtive to XOOPS_ROOT_PATH (phisical path to object)
	 * return bool
	 */
	public function register_object($name, $type, $path){
	    
	    $db = Database::getInstance();
	    $name = TextCleaner::getInstance()->sweetstring($name);
	    
	    if ($name=='' || $type=='')
    		return false;
	    
	    if ($this->get_object($name, $type)>0) 
    		return true;
    	
    	// Check path
    	if ($path=='' || !is_dir(XOOPS_ROOT_PATH.'/'.$path)){
			trigger_error(sprintf(__('The path %s is not a valid directory for object %s','global'), $path,  $name), E_USER_WARNING);
			return false;
    	}
	    
	    $sql = "INSERT INTO ".$db->prefix("api_objects")." (`name`,`type`,`path`) VALUES ('$name','$type','$path')";

	    if ($db->queryF($sql)){
	        return $db->getInsertId();
	    } else {
	        return false;
	    }
	   
	}
    
    public function object_registered($object){
        
    }

	/**
	* Register an event for a registered object.
	* 
	* @param string Event name
	* @param string|int Object name or object id (must be registered)
	* @param string Object type, only when object is not an integer
	* @return bool
	*/
	public function register_event($event, $object_id, $obj_type=''){
	    // No se ha proporcionado nombre
	    if (trim($event)=='')
	    	return $this->error(__('The event name must be specified in order to register it', 'global'), E_USER_WARNING);
	    	
	    // No se ha proporcionado el nombre del objeto
	    if (trim($object_id)=='')
	    	return $this->error(sprintf(__('The object name must be provided in order to register event %s', 'global'), $event), E_USER_WARNING);
	    
	    
	        
	    // verificamos que exista el objeto
	    $db =& Database::getInstance();
	    if (intval($object_id)<=0)
    		$object_id = $this->get_object($object_id, $obj_type);
	    
	    if ($object_id<=0)
	    	return $this->error(sprintf(__('Object %s does not exists as registered event object','global'), $object), E_USER_NOTICE);
	    
	    // Verificamos que no exista el evento
	    $eid = crc32($event);
	    $sql = "SELECT COUNT(*) FROM ".$db->prefix("api_events")." WHERE eid='$eid'";
	    list($num) = $db->fetchRow($db->query($sql));
	    if ($num>0) return _api_error('008');
	    
	    $sql = "INSERT INTO ".$db->prefix("api_events")." (`event`,`object`,`eid`) VALUES ('$event','$object_id','$eid')";
	    
	    if ($db->queryF($sql)){
	        return true;
	    } else {
	        return false;
	    }

	}
    
    /**
    * Register multiple events at once
    * @param array Events to register (only names)
    * @params int|string Object id, owner of events
    * @params string Object type
    */
    public function register_events($events, $object, $type){
        
        if (!is_array($events)) return;
        
        if (empty($events)) return;
        
        foreach($events as $event){
            $this->register_event($event, $object, $type);
        }
        
    }
	    
	/**
	 * Register new methods
	 * Methods registered for events are loaded when event is generated
	 * 
	 * Methods are functions that captures a particulary event and do some actions
	 * 
	 * Methods can be a class methods, only is needed to pass it
	 * with values pair 'class_name'=>'method_name'
	 * 
	 * @param string|array Method name
	 * @param string Event name
	 * @param string Path to file for include when method is called
	 * @return bool
	 */
	public function register_method($method, $event, $path, $object, $type=''){
	    
	    // No se ha proporcionado nombre
	    if (trim($method)=='' || trim($path=='') || $object=='')
    		return false;
	        
	    if (is_array($method)){
	        $method = json_encode($method);
	    }
	    
	    // Cargamos el id del $objeto
	    if (intval($object)<=0) $object = $this->get_object($object, $type);
	    if ($object<=0)
	    	return $this->error(sprintf(__('Attempt to register an method for %s object but this object is not registered.','global'), $object), E_USER_WARNING);
	    
	    // Generate the identifier of event
	    $db = Database::getInstance();
	    // Why CRC32?
	    // because if an element wish to register a method for an specified event
	    // and event has not been created (by example when requires an installed app
	    // and this app has not been installed) then the method will be active
	    // when the event will come active
	    $event = crc32($event);
        
	    // Verify that file exists
	    if (!is_file(XOOPS_ROOT_PATH.$path))
	    	return $this->error(sprintf(__('Attempt to register a method for %s object but file path does not exists','global'), $object), E_USER_WARNING);
	    
	    include_once XOOPS_ROOT_PATH.$path;
	    
	    if (!function_exists($method)) 
	    	return $this->error(sprintf(__('Attempt to register a method for %s object but %s method does not exists in specified file','global'), $object, $method), E_USER_WARNING);

	    $sql = "INSERT INTO ".$db->prefix("api_methods")." (`method`,`event`,`file`,`object`) VALUES ('$method','$event','$path','$object')";

	    if ($db->queryF($sql)){
	        return true;
	    } else {
	        return false;
	    }
	    
	}
    
    public function register_methods($methods, $object, $type=''){
        
        if (!is_array($methods)) return;
        
        if (empty($methods)) return;

        foreach ($methods as $method => $data){
            $this->register_method($method, $data['event'],$data['file'], $object, $type);
        }
        
    }
	    
	/**
	 * Delete an object from database and all its events
	 * 
	 * @param string Object name
	 * @param string Object type (app, theme, plugin, etc)
	 */
	public function remove_object($name,$type){
	    
	    if ($name=='' || $type=='') return;
	    
	    $db = Database::getInstance();    
	    // Obtenemos el id del objeto junto con todos los ids de los eventos registrados para el
	    $t1 = $db->prefix("api_events");
	    $t2 = $db->prefix("api_objects");
	    $t3 = $db->prefix("api_methods");
	    
	    $object = $this->get_object($name, $type);
	    if ($object<=0) return true;

	    $db->queryF("DELETE FROM $t1 WHERE object='$object'"); // Métodos de evento
	    $db->queryF("DELETE FROM $t3 WHERE object='$object'"); // Métodos de objeto
	    $db->queryF("DELETE FROM $t2 WHERE id_object='$object'");
	    
	    if ($db->error()!=''){ return fase; } else { return true; }
	    
	}
	    
	/**
	 * Load all methods registered for a single event.
	 * 
	 * @param int Event id
	 * @return array Para metodo => archivo
	 */
	private function load_methods($event){
	    
	    $db =& Database::getInstance();
	    $tm = $db->prefix("api_methods");
	    
	    $sql = "SELECT * FROM $tm WHERE event='$event'";
	    $result = $db->query($sql);
	    
	    $methods = array();
	    while ($row = $db->fetchArray($result)){
	       $methods[$row['method']] = $row['file'];
	    }
	    
	    return empty($methods) ? null : $methods;
	    
	}
	
	/**
	 * Execute the methods assigned to an event.
	 *
	 * @param string Nombre del evento
	 * @param mixed The value on wich the events methods will applied on
	 * @param mixed $var, ... Additional parameters to apply
	 */
	public function run_event($event, $value){

	    // Verificamos la cache
	    if (empty($this->api_events)){
			if (false === $this->rebuild_cache())
				return $value;
	    }

	    // Verificamos que exista el evento solicitado
	    if (!isset($this->api_events[$event])) return $value;

	    $args = func_get_args();
	    reset($this->api_events[$event]);
	    
	    //$methods = $this->api_events[$event][$event]['methods'];
	    foreach ($this->api_events[$event]['methods'] as $method => $file){
    		
    		if (trim($file)=='' || !file_exists(XOOPS_ROOT_PATH.'/'.$file)) continue;
    		
    		$args[1] = $value;
	        include_once XOOPS_ROOT_PATH.'/'.$file;
	        $value = call_user_func_array($method, array_slice($args, 1));
			
	    }
	    
	    return $value;
	    
	}
	    
	/**
	 * @desc Genera el archivo cache para la API de EXM System
	 * Este método es llamado después de registrar eventos, métodos u objetos
	 */
	public function rebuild_cache(){
		
	    $this->from_db();
	    
	    if (empty($this->api_events)) return false;
	    file_put_contents($this->api_file, TextCleaner::encrypt(json_encode($this->api_events)));
	    
	    return true;
	    
	}
	    
	/**
	 * @desc Obtiene todos los eventos registrados para un objeto
	 * @param int Id del objeto al que pertenecen los eventos
	 * @param bool Devolver los métodos como parte del array
	 * @return array Matriz evento => (id, methods)
	 */
	private function load_events($object, $ms = false){
	    $db =& Database::getInstance();
	    $te = $db->prefix("api_events");
	    
	    $sql = "SELECT * FROM $te WHERE object='$object'";
	    
	    $result = $db->query($sql);
	    
	    $events = array();
	    
	    while ($row = $db->fetchArray($result)){
	        $events[$row['event']] = array('id'=>$row['id_event'],'methods'=>$this->load_methods($row['id_event']));        
	    }
	    
	    return $events;
	}
	    
	/**
	 * Get the object identifier
	 * @param string Object name
	 * @param string Object type
	 * @return int
	 */
	public function get_object($name, $type){
	    
	    if ($name=='' && $type=='') return false;
	    
	    $db = Database::getInstance();
	    $sql = "SELECT id_object FROM ".$db->prefix("api_objects")." WHERE name='$name' AND type='$type'";
	    list($id) = $db->fetchRow($db->query($sql));
	    
	    return $id;
	    
	}
	    
	/**
	 * @desc Comprueba si un objeto especifico ha sido registrado
	 * @param string Nombre del objeto
	 * @return bool
	 */
	private function object_exists($object){
	    
	    if (!file_exists($this->api_file)) $this->rebuild_cache();
	    
	    return isset($this->api_events[$object]);
	}
	    
	/**
	 * @desc Comprueba si un evento especifico ha sido registrado
	 * @param string Nombre del evento
	 * @param string Nombre del objeto al que pertenece el evento
	 * @return bool
	 */
	public function event_exists($event, $object){
	    
	    if (!file_exists($this->api_file)) $this->rebuild_cache();
	    
	    return isset($this->api_events[$event]);
	    
	}
	    
	/**
	 * @desc Comprueba si un método especifico ha sido registrado
	 * @param string Nombre del metodo
	 * @param string Nombre del evento al que pertenece el metodo
	 * @param string Nombre del objeto al que pertenece el evento
	 * @return bool
	 */
	function method_exists($method, $event){
	    
	    if (!file_exists($this->api_file)) $this->rebuild_cache();
	    
	    return isset($this->api_events[$event]['methods'][$method]);
	    
	}

	/**
	* Check if an event has methods registered
	* @param string Event name
	* @param string Object name
	* @return bool
	*/
	public function have_methods($event){

	    if (!empty($this->api_events[$event]['methods'])) return true;
	    
	}
	
	/**
	* This method triggers an error and return false
	* 
	* @param string $message
	* @param int $level
	*/
	private function error($message, $level){
		trigger_error($message, $level);
		return false;
	}

}
