<style type="text/css">
    #mError, #mSuc{
        position: absolute;
        left: 0em;
        right: 0em;
        width: 40%;
        padding: 1.3em;
        margin: 2em auto;
        z-index: 5;
    }

    #mError{
        background-color: red;
        border: 2px solid #990000;
    }

    #mSuc{
        background-color: #159330;
        border: 2px solid green;
    }

</style>

<script type="text/javascript">
    function show(element) {
	element.style.visiblity = "visible";
	setTimeout(hide(), 2000);
    }

    function hide(element) {
	element.style.visiblity = "hidden";
    }
</script>

<?php

class Message {

    //Showing the message
    public static function show() {
	if(Message::hasMessage()){
	    echo '<div id="m' . $_GET['mType'] . '" onload="show(this)">' .
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
	$finalString = '?';
	if($destination != '#'){
	    $finalString = $destination . $finalString;
	}
	$finalString .="message=$message&mType=";
	if($suc){
	    $finalString .="Suc";
	}else{
	    $finalString .="Error";
	}
	Header("Location: $finalString");
    }

}
?>
