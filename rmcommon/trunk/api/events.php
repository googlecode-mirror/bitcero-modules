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

class RMEvents
{
    
    private $_events = array();
    private $_preloads = array();
    
    public function __construct(){
        //$modules_list = XoopsLists::getDirListAsArray(XOOPS_ROOT_PATH . "/modules/");
        if ($modules_list = XoopsCache::read('system_modules_active')) {
            $i = 0;
            foreach ($modules_list as $module) {
                if (is_dir($dir = XOOPS_ROOT_PATH . "/modules/{$module}/preloads/")) {
                    $file_list = XoopsLists::getFileListAsArray($dir);
                    foreach ($file_list as $file) {
                        if (preg_match('/(\.php)$/i', $file)) {
                            $file = substr($file, 0, -4);
                            $this->_preloads[$i]['module'] = $module;
                            $this->_preloads[$i]['file'] = $file;
                            $i++;
                        }
                    }
                }
            }
        }
        
        // Set Events
        foreach ($this->_preloads as $preload) {
            include_once XOOPS_ROOT_PATH . '/modules/' . $preload['module'] . '/preloads/' . $preload['file']. '.php';
            $class_name = ucfirst($preload['module']) . ucfirst($preload['file']) . 'Preload' ;
            if (!class_exists($class_name)) {
                continue;
            }
            $class_methods = get_class_methods($class_name);
            foreach ($class_methods as $method) {
                if (strpos($method, 'event') === 0) {
                    $event_name = strtolower(str_replace('event', '', $method));
                    $event= array('class_name' => $class_name, 'method' => $method);
                    $this->_events[$event_name][] = $event;
                }
            }
        }
        
    }
    
    /**
    * Get an singleton instance for events api
    */
    public function get(){
        static $instance;
        
        if (isset($instance))
            return $instance;
        
        $instance = new RMEvents();
        return $instance;
    }
    
    public function load_extra_preloads($dir, $name){
        
        $dir = rtrim($dir, '/');
        $extra = array();
        
         if (is_dir($dir.'/preloads')){
            $file_list = XoopsLists::getFileListAsArray($dir.'/preloads');
            foreach ($file_list as $file) {
                if (preg_match('/(\.php)$/i', $file)) {
                    $file = substr($file, 0, -4);
                    $this->_preloads[$i]['theme'] = $module;
                    $this->_preloads[$i]['file'] = $file;
                    $extra[] = $file;
                    $i++;
                }
            }
        }
        
        foreach ($extra as $preload) {
            include_once $dir . '/preloads/' . $preload['file']. '.php';
            $class_name = ucfirst($name) . ucfirst($preload['file']) . 'Preload' ;
            if (!class_exists($class_name)) {
                continue;
            }
            $class_methods = get_class_methods($class_name);
            foreach ($class_methods as $method) {
                if (strpos($method, 'event') === 0) {
                    $event_name = strtolower(str_replace('event', '', $method));
                    $event= array('class_name' => $class_name, 'method' => $method);
                    $this->_events[$event_name][] = $event;
                }
            }
        }
        
    }
    
	/**
	* @desc Almacena toda la información de la API
	*/
    public function run_event($event_name, $value)
    {
        $event_name = strtolower(str_replace('.', '', $event_name));        
        $args = func_get_args();
        if (!isset($this->_events[$event_name])) return;
        
        foreach ($this->_events[$event_name] as $event) {
            $args[1] = $value;
            $value = call_user_func(array($event['class_name'], $event['method']), array_slice($args, 1));
        }
        
        return $value;
    }

}
