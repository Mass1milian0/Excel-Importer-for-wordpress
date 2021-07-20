<?php
/*
Description: Imports xlsx in pages
Author: Massimiliano Biondi
*/

$postType = 'post'; //tipo di post, opzioni: page | post (pagina | articolo)
$postStatus = 'publish'; // Status, opzioni: 'draft' | 'publish' | 'pending'| 'future' | 'private' (Bozza | Pubblicato | In Aprovazione | Programmato [non implementato] | privato)
$inputFileType = 'Xlsx';
$inputFileName = 'sample.xlsx'; // nome file excel
$photopath = "../wp-content/uploads/2021";
$linkphotopath = "/wp-content/uploads/2021";
/*
Formato file excel:

ID | Mail | Telefono | Foto 
*/


require_once("../wp-load.php");
require_once 'vendor/autoload.php';
require_once 'downloader.php';
require_once ("addFeatured.php");


ob_implicit_flush(true);
echo '                                                                                                                                                                                                                               ';
ob_end_flush();

$userID = 1;
$categoryID = '2';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);

$reader->setReadDataOnly(true);

$worksheetdata = $reader->listWorksheetInfo($inputFileName);
$spreadsheet = $reader->load($inputFileName);
$worksheet = $spreadsheet->getActiveSheet();
$rows = $worksheet->toArray();
$compileddata =[];
$i = 0;
echo 'Compiled data for importing, compiling images... </br>' ;
foreach($rows as $column){
	
	foreach($column as $cell){
		
		array_push($compileddata,$cell);
	}
	$leadTitle = $compileddata[0];
	$content = '';
	for($i = 1;$i<count($compileddata) - 1;$i=$i+1){
		$content = $content . $compileddata[$i] . ' </br>';
	}
	
	$files = scandir($photopath);
	$files = array_diff(scandir($photopath), array('.', '..'));
	foreach($files as $file){
		if ($file == $leadTitle){
            $photos = scandir($photopath . '/' . $leadTitle);
	        $photos = array_diff(scandir($photopath . '/' . $leadTitle), array('.', '..'));
            foreach($photos as $photo){
                $content = $content . '<img src="' . $linkphotopath . '/' . $leadTitle . '/' . basename($photo) . '" " alt="image">';  
                echo 'imported image: ' . basename($photo) . '</br>';
            }
                

		}
	}
	
	$leadContent = $content;
	$timeStamp = $minuteCounter = 0;  // set all timers to 0;
	$iCounter = 1; // number use to multiply by minute increment;
	$minuteIncrement = 1; // increment which to increase each post time for future schedule
	$adjustClockMinutes = 0; // add 1 hour or 60 minutes - daylight savings
	
	// CALCULATIONS
	$minuteCounter = $iCounter * $minuteIncrement; // setting how far out in time to post if future.
	$minuteCounter = $minuteCounter + $adjustClockMinutes; // adjusting for server timezone
	
	$timeStamp = date('Y-m-d H:i:s', strtotime("+$minuteCounter min")); // format needed for WordPress
	
	$new_post = array(
	'post_title' => $leadTitle,
	'post_content' => $leadContent,
	'post_status' => $postStatus,
	'post_date' => $timeStamp,
	'post_author' => $userID,
	'post_type' => $postType,
	'post_category' => array($categoryID)
	);
 
	$post_id = wp_insert_post($new_post);
	$compileddata = [];
}



$finaltext = '';
 
if($post_id){

$finaltext .= 'fatto.<br>';

} else{

$finaltext .= 'Something went wrong and I didn\'t insert a new post.<br>';
}
echo $finaltext;
?>