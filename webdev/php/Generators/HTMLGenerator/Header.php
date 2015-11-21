<?php

namespace HTMLGenertator;

class Header {

    private $title;
    private $cssFiles = Array('main.css', 'menu.css');
    private $jsFiles = Array('messageMovement.js');
    private $otherInformation = Array();
    private $mode = Header::NORMALMODE;
    private $metaInformation = Array('charset' => Header::DEFAULTCHARSET);

    //Constructor for the object
    public function __construct($pageTitle = NULL, $mode = NULL) {
	$this->title = $pageTitle;
	$this->mode = (int) $mode;
    }

    private function getDir() {
	$mainDir = substr(__DIR__, strpos(__DIR__, 'Schulverwaltung') - 1);
	$result = $mainDir . '/../../../';
	return $result;
    }

    // <editor-fold defaultstate="collapsed" desc="Setter">
    private function addFile($fileToAdd, $arrayToPush) {
	if (is_array($fileToAdd)) {
	    for ($i = 0; $i < count($fileToAdd); $i++) {
		array_push($arrayToPush, $fileToAdd[$i]);
	    }
	    return true;
	}
	array_push($arrayToPush, $fileToAdd);
	return false;
    }

    public function addCSS($cssFile) {
	return ($cssFile != NULL) ? $this->addFile($cssFile, $this->cssFiles) : false;
    }

    public function addJS($jsFile) {
	return ($jsFile != NULL) ? $this->addFile($jsFile, $this->jsFiles) : false;
    }

    public function addOther($otherStuff) {
	return ($otherStuff != NULL) ? $this->addFile($otherStuff, $this->otherInformation) : false;
    }

    public function toogleMode($mode) {
	$this->mode = $mode;
	return $this->mode;
    }

    public function setMetaInformation($newInformation) {
	if (is_array($newInformation)) {
	    foreach ($newInformation as $info => $value) {
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
	foreach ($this->metaInformation as $information => $value) {
	    $result.="<meta $information=\"$value\" />";
	}
	$result .= '<!-- Stylesheets -->';
	//Outputting the css files
	$currentDir = $this->getDir();
	foreach ($this->cssFiles as $file) {
	    $result .= '<link rel="stylesheet" href="' . $currentDir . 'stylesheets/main/' . $file . '" />' . "\n";
	    switch ($this->mode) {
		case HeaderMode::DARKMODE:
		    $result .= '<link rel="stylesheet" href="' . $currentDir . 'stylesheets/dark/' . $file . '" />' . "\n";
		    break;
		case HeaderMode::MOBILEMODE:
		    $result .= '<link rel="stylesheet" href="' . $currentDir . 'stylesheets/mobile/' . $file . '" />' . "\n";
		    break;
	    }
	}
	//Outputting the javascript files
	$result .= '<!-- Javascript -->';
	foreach ($this->jsFiles as $file) {
	    $result .= '<script type="text/javascript" src="' . $currentDir . 'js/' . $file . '"></script>' . "\n";
	}
	$result .= '</head>';
	return $result;
    }

}

class HeaderMode {

    const NORMALMODE = 0;
    const DARKMODE = 1;
    const MOBILEMODE = 2;
    const DEFAULTCHARSET = 'UTF-8';
    const WINCHARSET = 'ANSI';

}
