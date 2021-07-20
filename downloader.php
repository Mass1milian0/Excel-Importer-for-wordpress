<?php
require_once 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$inputFileType = 'Xlsx';				//tipo file
$inputFileName = 'sample.xlsx';			//nome file
$path = '../wp-content/uploads/2021/';	//directory ( '..'  significa cartella precedente)
$timer = 0; //tempo da aspettare per scaricare un altra foto in secondi

ob_implicit_flush(true);
echo '                                                                                                                                                                                                                               ';
ob_end_flush();

/*
----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
*/

$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);

$reader->setReadDataOnly(true);

$worksheetdata = $reader->listWorksheetInfo($inputFileName);
$spreadsheet = $reader->load($inputFileName);
$worksheet = $spreadsheet->getActiveSheet();
$rows = $worksheet->toArray();

for($i = 0;$i<count($rows);$i = $i + 1){
	preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $rows[$i][count($rows[$i]) - 1], $match);
	for ($k = 0; count($match) > $k; $k = $k + 2) {
		foreach($match[$k] as $a){
			
			$url = $a;
            $b = pathinfo($a);
            $ext = substr($b['extension'], 0, strpos($b['extension'], "?"));
			echo 'Downloading: ' . $url . '</br>';
			$downloadedFileContents = file_get_contents($url);
			
			
			if($downloadedFileContents === false){
				echo 'Failed to download file at: ' . $url;
			}
			
			if (! is_dir('../wp-content/uploads/2021/' . $rows[$i][0])){
				mkdir('../wp-content/uploads/2021/' . $rows[$i][0]);
			}
			
			$fileName = $path . $rows[$i][0] . '/' . uniqid() . '.' . $ext;
			$save = file_put_contents($fileName, $downloadedFileContents);
			echo 'waiting: ' . $timer . ' seconds </br>';
			sleep ($timer);
		}
}

}

echo 'Downloaded all urls, importing... </br>';

?>