<?php

require_once 'MenuItem.php';

//Calculates the additionalDepth
global $additionalDepth;
$uri = $_SERVER['REQUEST_URI'];
$subst = substr($uri, strpos($uri, 'loggedIn/') + strlen('loggedIn/'));
$additionalDepth = substr_count($subst, '/');

/**
 * getRootURL($url)
 * 	Returns the root url of the input path
 * @global int $additionalDepth
 * @param string $url
 * @return string
 */
function getRootURL($url) {
    global $additionalDepth;
    for ($i = 0; $i < $additionalDepth; $i++) {
	$url = '../' . $url;
    }
    return $url;
}

class MenuGenerator {

    private $menuItems = Array();

    /**
     * addItem($newItem)
     * 	    Adds an item from the menu
     * @param MenuEntry $newItem
     * @return boolean
     * 	    Returns if the action was successfull
     */
    public function addItem($newItem = NULL) {
	if ($newItem == NULL || !($newItem instanceof MenuEntry)) {
	    return false;
	}
	array_push($this->menuItems, $newItem);
	return true;
    }

    /**
     * setItem($newItem)
     * 	    Sets all the MenuItems of the menu
     * @param array $newItem
     * 	    Prepared menu
     * @return boolean
     * 	    Returns true on success otherwise false
     */
    public function setItem($newItem) {
	for ($i = 0; $i < count($newItem); $i++) {
	    if (!($newItem[$i]instanceof MenuEntry)) {
		return false;
	    }
	}
	$this->menuItems = $newItem;
	return true;
    }

    /**
     * removeItem($newItem)
     * 	    Removes an item from the menu
     * @param MenuEntry $newItem
     * @return boolean
     * 	    Returns if the action was successfull
     */
    public function removeItem($newItem = NULL) {
	if ($newItem == NULL || !($newItem instanceof MenuEntry)) {
	    return false;
	}
	$found = false;
	for ($i = 0; $i < count($this->menuItems); $i++) {
	    if ($this->menuItems[$i] == $newItem) {
		unset($this->menuItems[$i]);
		$found = true;
	    }
	}
	return $found;
    }

    /**
     * getLength()
     * @return integer
     */
    public function getLength() {
	return count($this->menuItems);
    }

    /**
     * Gets the string of the object
     * @return string
     */
    public function __toString() {
	$finalString = '<nav id="menu"><ul>';
	$menuItems = count($this->menuItems);
	$itemWidth = roundDown(100.0 / $menuItems, 4);
	for ($i = 0; $i < $menuItems; $i++) {
	    $this->menuItems[$i]->setWidth($itemWidth);
	    $finalString .= (string) $this->menuItems[$i];
	}
	$finalString .= '</ul></nav>';
	return $finalString;
    }

}

?>