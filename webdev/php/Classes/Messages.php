<?php

class Message {

    //Showing the message
    public static function show() {
	if (Message::hasMessage()) {
	    echo '<div id="m' . $_GET['mType'] . '">' .
	    $_GET['message']
	    . '</div>';
	}
    }

    public static function hasMessage() {
	return isset($_GET['message']);
    }

    /**
     * castMessage($message, $suc, $desctination)
     * @param String $message The message that should appear
     * @param boolean $suc Was the operation successfull
     * @param String $destination Where will the message lead you [optional]
     */
    public static function castMessage($message = '', $suc = false, $destination = '#') {
	if (isIn('?', $destination)) {
	    $concat = '&';
	} else {
	    $concat = '?';
	}
	if ($destination != '#') {
	    $finalString = $destination . $concat;
	}
	$finalString .="message=$message&mType=";
	if ($suc) {
	    $finalString .="Suc";
	} else {
	    $finalString .="Error";
	}
	Header("Location: $finalString");
    }

}

?>
