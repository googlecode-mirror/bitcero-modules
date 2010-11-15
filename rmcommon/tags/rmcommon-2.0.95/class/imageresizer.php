<?php
// $Id$
// --------------------------------------------------------------
// Red México Common Utilities
// A framework for Red México Modules
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class RMImageResizer
{
    private $file = ''; #Archivo de origen
    private $filetarget = ''; # Archivo destino
    /**
     * @param string $file Archivo existente
     * @param string $filetarget Archivo destino
     */
    public function __construct($file='', $filetarget=''){
        $this->file = $file;
        $this->filetarget = $filetarget;
    }
    /**
     * Establece el archivo destino
     * @param string $file Ruta completa al archivo destino
     */
    public function setTargetFile($file){
        $this->filetarget = $file;
    }
    /**
     * Obtiene la ruta completa del archivo destino
     */
    public function getTargetFile(){
        return $this->filetarget;
    }
    /**
     * Establece el archivo origen
     * @param string $file Ruta completa al archivo origen
     */
    public function setSourceFile($file){
        $this->file = $file;
    }
    /**
     * Obtiene la ruta completa del archivo origen
     */
    public function getSourceFile(){
        return $this->file;
    }
    /**
     * Comprueba que los archivos de entrada y salida
     * hayan sido especificados
     * @return bool
     */
    private function checkFiles(){
        if ($this->file==''){
            $this->addError(_RMS_CF_FILEEMPTY);
            return false;
        }
        if ($this->filetarget==''){
            $this->addError(_RMS_CF_NOTARGET);
            return false;
        }
        return true;
    }
    /**
     * Redimensiona un im?gen en forma de cuadro.
     * Si la im?gen es rectangular recorta la medida mas grande
     * @param int $tw Ancho de la im?gen a generar
     * @param int $th Alto de la im?gen a generar
     * @param int $red Valor Rojo para la im?gen generada
     * @param int $green Valor Verde para la im?gen generada
     * @param int $blue Valor Azul para la im?gen generada
     */
    public function resizeAndCrop($tw,$th,$red=255,$green=255,$blue=255){
        
        if (!$this->checkFiles()) return false;
        
        if (!file_exists($this->file)){
            $this->addError(_RMS_CF_FILENOEXISTS);
            return false;
        }
        
        list($wo, $ho) = getimagesize($this->file);
        $height = $ho * $percent;; $width = $wo * $percent;;
        
        $format = $this->getFormat();

        $image = $this->createImage($format);
        if ($wo==$tw && $ho==$th) return true;

        if ($wo < $ho) {
            $height = ($tw / $wo) * $ho;
        } else {
            $width = ($th / $ho) * $wo;
        }
        
        if ($width < $tw){
            //if the width is smaller than supplied thumbnail size
            $width = $tw;
            $height = ($tw/ $wo) * $ho;
        }
        
        if ($height < $th){
            $height = $th;
            $width = ($th / $ho) * $wo;
        }
        
        $thumb = imagecreatetruecolor($width , $height); 
        $bgcolor = imagecolorallocate($thumb, $red, $green, $blue); 
        imagefilledrectangle($thumb, 0, 0, $width, $height, $bgcolor);
        imagealphablending($thumb, true);
    
        imagecopyresampled($thumb, $image, 0, 0, 0, 0, $width, $height, $wo, $ho);
        $thumb2 = imagecreatetruecolor($tw, $th);
        // true color for best quality
        $bgcolor = imagecolorallocate($thumb2, $red, $green, $blue); 
        imagefilledrectangle($thumb2, 0, 0,    $tw , $th , $bgcolor);
        imagealphablending($thumb2, true);
    
        $w1 =($width/2) - ($tw/2);
        $h1 = ($height/2) - ($th/2);
    
        imagecopyresampled($thumb2, $thumb, 0,0, $w1, $h1, $tw, $th,$tw, $th);
        
        return $this->imageFromFormat($format, $thumb2);
    }
    /**
     * Redimensiona una im?gen a un ancho espec?fico
     * @param int $width Ancho de la im?gen
     * @param bool $force Crea una im?gen anque esta sea mas peque?a que el ancho dado
     */
    public function resizeWidth($width, $force = false){
        if (!$this->checkFiles()) return false;
        
        $datos = getimagesize($this->file);
        $ratio = ($datos[0] / $width);
        $height = round($datos[1] / $ratio);
        
        if (!$force){
            if ($width >= $datos[0]){
                if ($this->file != $this->filetarget){
                    copy($this->file, $this->filetarget);
                }
                return true;
            }
        }
        
        $thumb = imagecreatetruecolor($width,$height);
        $bgcolor = imagecolorallocate($thumb, 255,255,255); 
        imagefilledrectangle($thumb, 0, 0, $width, $height, $bgcolor);
        imagealphablending($thumb, true);
        $format = $this->getFormat();
        $image = $this->createImage($format);
        imagecopyresampled ($thumb, $image, 0, 0, 0, 0, $width, $height, $datos[0], $datos[1]);
        return $this->imageFromFormat($format, $thumb);
    }
    /**
     * Redimensiona una im?gen limitando el ancho o alto
     * a un valor dado
     * @param int $width Ancho de la im?gen
     * @param int $height Alto de la im?gen
     * @param bool $force Crea una im?gen aunque esta sea mas peque?a que el ancho dado
     */
    public function resizeWidthOrHeight($width, $height, $force = false){
        
        if (!$this->checkFiles()) return false;
        $datos = getimagesize($this->file);
        if ($datos[0] >= $datos[1]){
            $ratio = ($datos[0] / $width);
            $height = round($datos[1] / $ratio);
        } else {
            $ratio = ($datos[1] / $height);
            $width = round($datos[0] / $ratio);
        }
        
        $thumb = imagecreatetruecolor($width,$height);
        $format = $this->getFormat();
        $image = $this->createImage($format);
        imagecopyresampled ($thumb, $image, 0, 0, 0, 0, $width, $height, $datos[0], $datos[1]);
        return $this->imageFromFormat($format, $thumb);
        
    }
    /**
     * Creamos la im?gen en memoria
     * @return object
     */
    public function createImage($format){
        switch($format){
            case 'image/jpeg':
                return imagecreatefromjpeg($this->file);
                break;
            case 'image/gif';
                return imagecreatefromgif($this->file);
                break;
            case 'image/png':
                return imagecreatefrompng($this->file);
                break;
        }
    }
    /**
     * Guarda la im?gen modificada
     */
    public function imageFromFormat($format, $image, $quality=90){
        switch ($format){
            case 'image/jpeg':
                return imagejpeg($image, $this->filetarget, $quality);
                   break;
            case 'image/gif';
                return imagegif($image, $this->filetarget);
                break;
            case 'image/png':
               return imagepng($image, $this->filetarget);
                break;
        }
    }
    /**
     * Obtiene el formato de la im?gen
     * @return string
     */
    private function getFormat(){
        if(preg_match("/.jpg/i", $this->file)){
            $format = 'image/jpeg';
        } elseif (preg_match("/.gif/i", $this->file)){
            $format = 'image/gif';
        } elseif (preg_match("/.png/i", $this->file)){
            $format = 'image/png';
        }
        
        return $format;
    }
}
