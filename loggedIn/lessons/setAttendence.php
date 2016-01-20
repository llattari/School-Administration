<?php

require_once '../../webdev/php/essentials/databaseEssentials.php';
require_once '../../webdev/php/essentials/essentials.php';
require_once '../../webdev/php/Classes/Messages.php';
connectDB();
$destination = 'index.php';

# Getting the values form the old form
$lessonId = (int) $_POST['lessonId'];
$attendedPOST = isset($_POST['attend']) ? $_POST['attend'] : []; //Array of int
$homeworkPOST = isset($_POST['homework']) ? $_POST['homework'] : []; //Array of int
#Checking invalid class id
if ($lessonId < 1) {
    Message::castMessage('Invalid class id', false, $destination);
}

#Filtering invalid ints from array
$attendedId = array_filter(array_map('intval', $attendedPOST), 'validId');
$homeworkId = array_filter(array_map('intval', $homeworkPOST), 'validId');

#Creating a bool array for sql
$boolArray = [];
for ($i = 0; $i < count($attendedId); $i++) {
    $value = 'true, ';
    $value.= in_array($attendedId[$i], $homeworkId) ? 'true' : 'false';
    $boolArray[$attendedId[$i]] = $value;
    $homeworkId = array_diff($homeworkId, [$attendedId[$i]]);
}

$homeworkButNotHere = array_merge($homeworkId);
for ($i = 0; $i < count($homeworkButNotHere); $i++) {
    $boolArray[$homeworkButNotHere[$i]] = 'false, true';
}

#preparing SQL statement
$sql = 'INSERT INTO lesson__attended(lessonId, studentId, attended, homeworkDone) VALUES';
foreach ($boolArray as $studentId => $value) {
    $sql .= "($lessonId, $studentId, $value),";
}
$sql = rtrim($sql, ',') . ';';
echo $sql . '<br />';
if (safeQuery($sql)) {
    Message::castMessage('Sucessfully altered data', true, $destination);
} else {
    Message::castMessage('Something went wrong during querry time.', false, $destination);
}

#Outputting the data
echo "LessonID: $lessonId <br />";
echo 'Attend: ';
var_dump($attendedId);
echo '<br />HW: ';
var_dump($homeworkId);
?>