<?php
// $Id$
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/**
 * Clase controladora para campos de edicin de contenido
 */
class RMFormEditor extends RMFormElement
{
	private $_width = '';
	private $_height = '';
	private $_default = '';
	private $_type = 'tiny';
	private $_theme = '';
	private $_action = '';
	/**
	* Indica si se muestra o no el combo para cambiar tipo de editor
	*/
	private $_change = 0;
	/**
	* Especifica el parámetro que sirve como base para el cambio
	* de tipo de edito
	*/
	private $_eles = array();
	/**
	 * Variables utilizadas con el editor tiny
	 */
	private $_tinycss = '';
    /**
    * Almacena los botones (orden) que se utilizaran en el editor exmCode
    */
    private $ex_plugins = 'dropdown,texts,fonts,email,link,xcode,xquote,rmimage,emotions,more,chars';
    private $ex_buttons = 'bold,italic,underline,strikeout,separator_t,left,center,right,separator_t,fontsize,font,fontcolor,separator_t,more,bottom,top,separator_b,link,email,xcode,xquote,separator_b,images,emotions,separator_b,chars,page';
	/**
	 * @param string $caption Texto del campo
	 * @param string $name Nombre de este campo
	 * @param string $width Ancho del campo. Puede ser el valor en formato pixels (300px) o en porcentaje (100%)
	 * @param string $height Alto de campo. El valor debe ser pasado en formato pixels (300px).
	 * @param string $default Texto incial al cargar el campo. POr defecto se muestra vaco.
	 * @param string $type Tipo de Editor. Posibles valores: FCKeditor, DHTML
	 */
	function __construct($caption, $name, $width='100%', $height='300px', $default='', $type='', $change=1, $ele=array('op')){
        
        $rmc_config = RMFunctions::get()->configs();
        
		$tcleaner = TextCleaner::getInstance();
		$this->setCaption($caption);
		$this->setName($name);
		$this->_width = $width;
		$this->_height = $height;
		$this->_default = isset($_REQUEST[$name]) ? $tcleaner->stripslashes($_REQUEST[$name]) : $tcleaner->stripslashes($default);
		$this->_type = $type=='' ? $rmc_config['editor_type'] : $type;
        $this->_type = strtolower($this->_type);
        $this->_change = $change;
        $this->_eles = $ele;
        
	}
	/**
	 * Establece el tipo de editor a utilizar
	 */
	public function setType($value){
		$this->_type = $value;
	}
	public function getType(){
		return $this->_type;
	}
	/**
	 * Establece el tema a utilizar en tiny
	 */
	public function setTheme($theme){
		$this->_theme = $theme;
	}
	/**
	* @desc Cambia el valor action del formulario en el cambio de tipo de editor
	*/
	public function editorFormAction($action){
		$this->_action = $action;
	}
	/**
	 * Generamos el cdigo HTML para el editor seleccionado
	 * @return string
	 */
	public function render(){
		/**
		* Agregamos la opción para cambiar el tipo de editor
		*/
		$ret = '';
        
		switch ($this->_type){
			case 'simple':
				$ret .= $this->renderTArea();
				break;
			case 'xoops':
				$ret .= $this->renderExmCode();
				break;
			case 'html':
				$ret .= $this->renderHTML();
				break;
            case 'tiny':
            default:
                $ret .= $this->renderTiny();
                break;
		}
		
		return $ret;
	}
	
	public function renderTArea(){
		return "<div id=\"ed-container\" style=\"width: $this->_width\"><textarea id='".$this->getName()."' name='".$this->getName()."' style='width: ".$this->_width."; height: ".$this->_height.";'>".$this->_default."</textarea></div>";
	}
	/**
	 * Set de funciones útiles nicamente con el editor TinyMCE
	 */
	public function tinyCSS($url, EXMForm &$form){
        $form->tinyCSS($url);
	}
    
	/**
	 * Genera el cdigo HTML para el editor TINY
	 * @return string
	 */
	private function renderTiny(){
		global $rmc_config, $xoopsUser;
		TinyEditor::getInstance()->add_config('elements',$this->getName(), true);
		RMTemplate::get()->add_xoops_style('mce_editor.css','rmcommon');
		RMTemplate::get()->add_script(RMCURL."/include/js/editor.js");
		RMTemplate::get()->add_script(RMCURL."/include/js/quicktags.js");
		RMTemplate::get()->add_head(TinyEditor::getInstance()->get_js());
		$rtn = "\n
		<div id=\"ed-container\" style=\"width: $this->_width\">
        <div id=\"es-editor\" style=\"width: $this->_width;\">
        <a id=\"edButtonHTML\" class=\"\" onclick=\"switchEditors.go('".$this->getName()."', 'html');\">HTML</a>
        <a id=\"edButtonPreview\" class=\"active\" onclick=\"switchEditors.go('".$this->getName()."', 'tinymce');\">Visual</a>
        </div>
        <div id=\"quicktags\"><script type=\"text/javascript\">edToolbar('".$this->getName()."')</script></div>
        <textarea id='".$this->getName()."' name='".$this->getName()."' style='width: ".$this->_width."; height: ".$this->_height.";' class='".$this->getClass()."'>".$this->_default."</textarea></div>";
		return $rtn;
	}
	
	/**
	* HTML Editor
	* @since 1.5
	*/
	private function renderHTML(){
		RMTemplate::get()->add_script(RMCURL."/include/js/quicktags.js");
		RMTemplate::get()->add_style('editor_html.css','rmcommon');
		$rtn = "\n<div class='ed-container html_editor_container' style='width: $this->width;' id='".$this->getName()."-ed-container'>
		<div class=\"quicktags\"><script type=\"text/javascript\">edToolbar('".$this->getName()."')</script></div>
		<div class='txtarea_container'><textarea id='".$this->getName()."' name='".$this->getName()."' style='width: ".$this->_width."; height: ".$this->_height.";' class='".$this->getClass()."'>".$this->_default."</textarea></div>
		</div>";
		return $rtn;
	}
	
	private function renderExmCode(){
		RMTemplate::get()->add_script(RMCURL."/api/editors/exmcode/editor-exmcode.php?id=".$this->getName());
		RMTemplate::get()->add_script(RMCURL."/include/js/colorpicker.js");
		RMTemplate::get()->add_style('editor-exmcode.css','rmcommon');
		RMTemplate::get()->add_style('colorpicker.css','rmcommon');
        $lang = is_file(ABSPATH.'/api/editors/exmcode/language/'.EXMLANG.'.js') ? EXMLANG : 'en_US';
        RMTemplate::get()->add_script(RMCURL.'/api/editors/exmcode/language/'.$lang.".js");
		$rtn = 	"<div class='ed-container' id='".$this->getName()."-ed-container' width='$this->_width'>";
		$rtn .= "<div class='ed_buttons' id='".$this->getName()."-ec-container'>";
        $rtn .= "<div class='row_top'></div><div class='row_bottom'></div>";
		$rtn .= "</div>";
		$rtn .= "<textarea id='".$this->getName()."' name='".$this->getName()."' style='width: 99%; height: ".$this->_height.";' class='".$this->getClass()."'>".$this->_default."</textarea>";
		$rtn .= "</div>";
        // buttons
        $tplugins = RMEvents::get()->run_event('rmcommon.exmcode_plugins', $this->ex_plugins);
        $tplugins = explode(',',$tplugins);
        foreach ($tplugins as $p){
            $plugins .= $plugins=='' ? $p.': true' : ','.$p.': true';
        }
        RMTemplate::get()->add_head("<script type=\"text/javascript\">\nvar ".$this->getName()."_buttons = \"".RMEvents::get()->run_event('rmcommon.exmcode_buttons', $this->ex_buttons)."\";\nvar ".$this->getName()."_plugins = {".$plugins."};\n</script>");
		return $rtn;
	}
    
    /**
    * Establece los botones a mostrar en el editor
    * 
    * @param string $buttons
    */
    public function exmcode_buttons($buttons){
        if ($buttons=='') return;
        
        $this->ex_buttons = $buttons;
    }
}
