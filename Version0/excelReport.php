<?php
session_start();
$serial = $_SESSION["serialNo"];
$brand = $_SESSION["companyName"];
$name = $_SESSION["testedBy"];

$FAN_No = "";
$Refrigerent = "R-404a";
$RefrigerentVal = "2.7Kg";

$username="root";
$password="root";
$database="temp";
$serial=786;
mysql_connect(localhost,$username,$password);
@mysql_select_db($database)or die ("Unable to select databse");

$query="SELECT * FROM compData WHERE SerialNo='".$serial."'";
$r=mysql_query($query);
$num=mysql_numrows($r);

$query1 = "select * from compData where Milk_temp between 25.0 and 25.5 and SerialNo='".$serial."'";
$r1 = mysql_query($query1);
$num1 = mysql_numrows($r1);

$query2 = "select * from compData where Bat_temp between -10.0 and -9.4 and serialNo='".$serial."'";
$r2 = mysql_query($query2);
$num2 = mysql_numrows($r2);

$setDate = "SELECT Date FROM compData WHERE SerialNo='".$serial."' LIMIT 1";
$result=mysql_query($setDate);
$Date=mysql_numrows($result);

$setBrand = "SELECT company FROM compData WHERE SerialNo='".$serial."' LIMIT 1";
$result1 = mysql_query($setBrand);
$Brand = mysql_numrows(result1);

$setName = "SELECT person FROM compData WHERE SerialNo='".$serial."' LIMIT 1";
$result2 = mysql_query($setName);
$Name = mysql_numrows(result2);
$setMachine = "SELECT machineNo FROM compData WHERE SerialNo'".$serial."' LIMIT 1";
$result3 = mysql_query($setMachine);
$machine = mysql_numrows(result3);
$i=0;
$f1 = mysql_result($result,$i,"Date");
$f2 = mysql_result($result1,$i,"company");
$f3 = mysql_result($result2,$i,"person");
$f4 = mysql_result($result3,$i,"machineNo");

$s1 = mysql_result($r1,$i,"Time");
$s2 = mysql_result($r2,$i,"Time");
$start = strtotime($s1);
$end = strtotime($s2);
$diff = $end-$start;
$totaltime =  round(abs($end-$start)/60,2)."minute";
//mysql.close();

$data = [
["Date" => $f1, "serialNo" => $serial,"Brand"=>$f2, "Fan no"=>$FAN_No,"Refrigerent"=>$Refrigerent,"Refrigerent charged"=>$RefrigerentVal,"Tested by"=>$f3,"Time"=>$totaltime]];
header("Content-Type: text/plain");
$flag = false;
foreach($data as $row) {
  if(!$flag) {
    echo implode("\t", array_keys($row)) . "\r\n";
    $flag = true;
  }
  echo implode("\t", array_values($row)) . "\r\n\n";
}


$conn = new mysqli(localhost,$username,$password);
mysqli_select_db($conn,$database);

$setSql = "SELECT Time,Milk_temp,Bat_temp,suction,discharge,Aux_temp,comp_curr,HP,LP FROM compData  WHERE SerialNo='".$serial."'";
$setRec = mysqli_query($conn,$setSql);

$columnHeader = '';
$columnHeader =  "Time" ."\t". "Brine in" ."\t" . "Brine out" . "\t" ."Suction" ."\t". "Discharge" ."\t"."Aux temp" . "\t" . "Comp current" . "\t" . "HP" . "\t" . "LP" . "\t";

$setData = '';

while($rec = mysqli_fetch_row($setRec)){
	$rowData = '';
	if($rec>0){
	foreach($rec as $value){
		$value = '"' . $value . '"' . "\t";
		$rowData .= $value;
	}
	$setData .= trim($rowData)."\n";
}}

header("Content-type: application/octet-stream");
//header("Content-Disposition: attachment; filename='".$serial."'report.xls"); 
header("Content-Disposition: attachment; filename='".$serial."_".$brand."report.xls"); 
header("Pragma: no-cache");
header("Expires: 0");
echo ucwords($columnHeader) . "\n" . $setData . "\n";
?>
