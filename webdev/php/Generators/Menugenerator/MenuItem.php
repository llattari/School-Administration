<?php

class MenuEntry {

    private $link, $caption, $width;
    private $subItems = Array();

    /**
     * __construct($link, $caption)
     * 	    Creates an instance of the object
     * @param string $link
     * @param string $caption
     */
    public function __construct($link = NULL, $caption = NULL) {
	if ($link && $caption) {
	    $this->link = $link;
	    $this->caption = $caption;
	} else {
	    $this->link = '';
	    $this->caption = '';
	}
	$this->width = 100;
    }

    /**
     * addItem($newItem)
     * 	    Add an item to the list of menu items
     * @param MenuEntry $newItem
     * @return boolean
     * 	    Returns true if it works otherwise false
     */
    public function addItem($newItem = NULL) {
	if ($newItem instanceof MenuEntry) {
	    array_push($this->subItems, $newItem);
	    return true;
	}
	return false;
    }

    public function setWidth($newWidth) {
	$width = (float) $newWidth;
	if ($width <= 0 || $width > 100) {
	    $this->width = 100;
	    return false;
	}
	$this->width = $width;
	return true;
    }

    // <editor-fold defaultstate="collapsed" desc="Getter">
    /**
     * getLink()
     * 	    Returns the link where the menu items leads
     * @return string
     */
    public function getLink() {
	return $this->link;
    }

    /**
     * getCaption()
     * 	    Returns the caption of the link
     * @return string
     */
    public function getCaption() {
	return $this->caption;
    }

    /**
     * getSubItems()
     * 	    Returns all the submenu items
     * @return array
     * 	    Array of MenuEntry
     */
    public function getSubItems() {
	return $this->subItems;
    }

    // </editor-fold>

    /**
     * __toString()
     * 	    Returns the string version of the object
     * @return string
     */
    public function __toString() {
	//Getting the submenus
	$submenus = '<ul>';
	for ($i = 0; $i < count($this->subItems); $i++) {
	    $submenus .= (string) $this->subItems[$i];
	}
	$submenus .= '</ul>';
	//Return the result
	return "<li style=\"width:" . $this->width . "%\">
		<a href=\"" . getRootURL($this->link) . '">' . $this->caption . '</a>' .
		$submenus . '</li>';
    }

}

?>