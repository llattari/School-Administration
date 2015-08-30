<?php

namespace HTMLGenertator;

class Header {
    const NORMALMODE = 0;
    const DARKMODE = 1;
    const MOBILEMODE = 2;
    const DEFAULTCHARSET = 'UTF-8';

    private $title;
    private $cssFiles = Array('main.css', 'menu.css');
    private $jsFiles = Array();
    private $mode = Header::NORMALMODE;
    private $metaInformation = Array('charset' => Header::DEFAULTCHARSET);

    //Constructor for the object
    public function __construct($pageTitle = '', $mode = Header::NORMALMODE) {
	$this->title = $pageTitle;
	$this->mode = (int) $mode;
    }

    private function getDir() {
	$mainDir = substr(__DIR__, strpos(__DIR__, 'Schulverwaltung') - 1);
	$result = $mainDir . '/../../../';
	return $result;
    }

    // <editor-fold defaultstate="collapsed" desc="Setter">

    public function addCSS($cssFile) {
	if($cssFile != NULL){
	    if(is_array($cssFile)){
		for($i = 0; $i < count($cssFile); $i++){
		    array_push($this->cssFiles, $cssFile[$i]);
		}
		return true;
	    }
	    array_push($this->cssFiles, $cssFile);
	    return true;
	}
	return false;
    }

    public function addJS($jsFile) {
	if($jsFile != NULL){
	    if(is_array($jsFile)){
		for($i = 0; $i < count($jsFile); $i++){
		    array_push($this->jsFiles, $jsFile[$i]);
		}
		return true;
	    }
	    array_push($this->jsFiles, $jsFile);
	    return true;
	}
	return false;
    }

    public function toogleMode($mode) {
	$this->mode = $mode;
	return $this->mode;
    }

    public function setMetaInformation($newInformation) {
	if(is_array($newInformation)){
	    foreach($newInformation as $info => $value){
		$this->metaInformation[$info] = $value;
	    }
	    return true;
	}
	return false;
    }

    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Getter of the class">
    public function getTitle() {
	return $this->title;
    }

    public function getMode() {
	return $this->mode;
    }

    // </editor-fold>

    /**
     * __toString()
     * 	    Converts the object into a String
     * @return string
     * 	    Returns a fully valid header information
     */
    public function __toString() {
	$result = '<head>
	<title>' . $this->title . '</title>
	<!-- Meta information -->';
	//Outputting meta information about the site.
	foreach($this->metaInformation as $information => $value){
	    $result.="<meta $information=\"$value\" />";
	}
	$result .= '<!-- Stylesheets and Javascript -->';
	//Outputting the css files
	foreach($this->cssFiles as $file){
	    $result .= '<link rel="stylesheet" href="' . $this->getDir() . 'stylesheets/main/' . $file . '" />' . "\n";
	    switch($this->mode){
		case Header::DARKMODE:
		    $result .= '<link rel="stylesheet" href="' . $this->getDir() . 'stylesheets/dark/' . $file . '" />' . "\n";
		    break;
		case Header::MOBILEMODE:
		    $result .= '<link rel="stylesheet" href="' . $this->getDir() . 'stylesheets/mobile/' . $file . '" />' . "\n";
		    break;
	    }
	}
	//Outputting the javascript files
	foreach($this->jsFiles as $file){
	    $result.='<script type="text/javascript" src="' . $this->getDir() . 'js/' . $file . '"></script>' . "\n";
	}
	$result .= '</head>';
	return $result;
    }

}
