<?php
//Includes
require_once '../../webdev/php/Generators/HTMLGenerator/Generator.php';
require_once '../../webdev/php/Classes/Mark.php';
require_once '../../webdev/php/Classes/ClassClass.php';

$HTML = new HTMLGenertator\HTMLfile('View Marks', ['marks.css'], NULL, NULL, 1);
$marks = new MarkAdministration\Grade($_SESSION['studentId']);
$HTML->outputHeader();
//todo: re-implement mark system
?>

<h1>Marks</h1>
<?php
/* @var $allSubjects int */
$allSubjects = $marks->getAllClassIds();
for($i = 0; $i < count($allSubjects); $i++){
    $subjectId = $allSubjects[$i];
    $subject = $marks->getSubjectById($subjectId);
    ?>

    <h3>
        Subject: <?php echo StudentClass::getClassName($subjectId); ?>
    </h3>
    <p>Total this year: <?php echo $subject->getMark(); ?></p>
    <table summary="All your marks in the subject stated above">
        <thead>
    	<tr>
    	    <th>1<sup>st</sup> quarter</th>
    	    <th>2<sup>nd</sup> quarter</th>
    	    <th>3<sup>rd</sup> quarter</th>
    	    <th>4<sup>th</sup> quarter</th>
    	    <th>Total</th>
    	</tr>
        </thead>
        <tbody>
    	<tr>
		<?php
		$sum = 0;
		for($i = 0; $i < 5; $i++){
		    $quarter = $subject->getQuarter($i);
		    $sum += $quarter;
		    echo '<td>' . $quarter->getMark() . '</td>';
		}
		echo '<td>' . ($sum / 4) . '</td>';
		?>
    	</tr>
        </tbody>
    </table>
    <br />
    <?php
}

$HTML->outputFooter();
?>
