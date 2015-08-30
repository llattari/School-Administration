<?php

class MenuEntry {
    private $link, $caption;
    private $subItems = Array();

    /**
     * __construct($link, $caption)
     * 	    Creates an instance of the object
     * @param string $link
     * @param string $caption
     */
    public function __construct($link = NULL, $caption = NULL) {
	if($link && $caption){
	    $this->link = $link;
	    $this->caption = $caption;
	}else{
	    $this->link = '';
	    $this->caption = '';
	}
    }

    /**
     * addItem($newItem)
     * 	    Add an item to the list of menu items
     * @param MenuEntry $newItem
     * @return boolean
     * 	    Returns true if it works otherwise false
     */
    public function addItem($newItem = NULL) {
	if($newItem instanceof MenuEntry){
	    array_push($this->subItems, $newItem);
	    return true;
	}
	return false;
    }

    // Getter
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

    // More object functions
    /**
     * __toString()
     * 	    Returns the string version of the object
     * @return string
     */
    public function __toString() {
	//Getting the submenus
	$submenus = '<ul>';
	for($i = 0; $i < count($this->subItems); $i++){
	    $submenus .= (string) $this->subItems[$i];
	}
	$submenus .= '</ul>';
	//Is this active?
	$active = $this->isActive($this->link) ? ' class="active"' : '';
	//Return the result
	return "<li$active>
		<a href=\"" . getRootURL($this->link) . '">' . $this->caption . '</a>' .
		$submenus . '</li>';
    }

    // Private functions
    /**
     * isActive()
     * 	    Returns wheather the website is active or not
     * @return boolean
     */
    private function isActive() {
	//Is the current URI = Menu URI
	if(isin($this->link, $_SERVER['REQUEST_URI'])){
	    return true;
	}
	//for every subItem
	for($i = 0; $i < count($this->subItems); $i++){
	    if(isIn($this->subItems[$i]->getLink(), $_SERVER['REQUEST_URI'])){
		return true;
	    }
	}
	return false;
    }

}

?>