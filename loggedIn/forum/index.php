<?php

require_once '../../webdev/php/Generators/HTMLGenerator/Generator.php';

$HTML = new HTMLGenertator\HTMLfile('Classes', NULL, NULL, NULL);

$HTML->outputHeader();
?>
<p>
    Hello world
</p>
<?php

$HTML->outputFooter();
?>