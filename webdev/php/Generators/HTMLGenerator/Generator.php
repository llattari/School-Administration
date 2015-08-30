<?php

namespace HTMLGenertator;

//Including database connection
require_once __DIR__ . '/Header.php';
require_once __DIR__ . '/../listGenerator.php';
require_once __DIR__ . '/../../essentials/essentials.php';
require_once __DIR__ . '/../../Classes/debuging/Logger.php';
require_once __DIR__ . '/../../Classes/Messages.php';

//Starting the session
session_start();
// Default check whether the user is logged in or not
if(!isset($_SESSION['studentId'])){
    Header('Location: ../index.php');
}

class HTMLfile {
    private $header = null;
    private $subdir = 0;

    //Object constructor
    function __construct($pageName, $curCSS = NULL, $curJS = NULL, $other = NULL, $subdir = 0) {
	connectDB();
	$this->header = new Header($pageName);
	$this->header->addCSS($curCSS);
	$this->header->addJS($curJS);
	$this->subdir = $subdir;
    }

    /**
     * outputHeader()
     * 	    Outputs the header of the HTML file
     */
    function outputHeader() {
	if($_SESSION['ui']['darkTheme']){
	    $this->header->toogleMode(Header::DARKMODE);
	}
	?>
	<!DOCTYPE html>
	<html>
	    <?php echo (string) $this->header; ?>
	    <body>
		<?php \Message::show(); ?>
		<!-- Implementing the menu -->
		<?php
		require_once __DIR__ . '/../../menu.php';
		//Starting the content
		echo '<!--The content -->
                    <div id = "content" >';
		echo '<div id="mainContent">';
	    }

	    /**
	     * Outputs the footer of the HTML file
	     */
	    function outputFooter() {
		?>
	    </div>
	</div>
	</body>
	</html>
	<?php
    }

}
?>