<?php
$serial = $_POST["serialNo"];
$username="root";
$password="root";
$database="temp_database";

$conn = new mysqli(localhost,$username,$password);
mysqli_select_db($conn,$database);

$setSql = "SELECT * FROM tempLog WHERE SerialNo='".$serial."'";
$setRec = mysqli_query($conn,$setSql);

$columnHeader = '';
$columnHeader = "Date" . "\t" . "Time" . "\t" . "SerialNo" . "\t" . "Brine out" ."\t" . "Brine in" . "\t" . "Aux temp" . "\t" . "Comp current" . "\t" . "HP" . "\t" . "LP" . "\t";

$setData = '';

while($rec = mysqli_fetch_row($setRec)){
	$rowData = '';
	foreach($rec as $value){
		$value = '"' . $value . '"' . "\t";
		$rowData .= $value;
	}
	$setData .= trim($rowData)."\n";
}

header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename='".$serial."'report.xls"); 
header("Pragma: no-cache");
header("Expires: 0");

echo ucwords($columnHeader) . "\n" . $setData . "\n";
?>
