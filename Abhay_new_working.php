<!DOCTYPE html>
<html lang="en">
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<head>


<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" crossorigin="anonymous">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" crossorigin="anonymous"></script>

<h3><center>Compressor Test Log</center></h3>
</head>

<body>
<div style="margin: 0 auto">
Download:  <input type="text" name="serialNo" placeholder="Enter serial number.." <br/>

<!--This button is inserted-->

  <form class="form-horizontal" action="functions.php" method="post" name="upload_excel" enctype="multipart/form-data">
    <div class="form-group">
      <div class="col-md-4 col-md-offset-4">
        <input type="submit" name="Export" class="btn btn-success" value="export to excel"/>
      </div>
    </div>
  </form>

<!--This button is inserted-->

<b><th>Serial no:</b></th>
</div>


<?php
$serial = $_POST["serialNo"];

//$serial = 9;
echo $serial;


/*********************************Temporary*****************************/
//Connecting to MySQL Database
$conn = new mysqli($servername, $username, $password, $dbname);

/*This is to echo the results from MySQL to HTML Page*/

// $MySQL_Query = "SELECT MilkTemp, AuxTemp, BatteryTemp FROM entries where SerialNumber =  $serial";
//
// $result = $conn->query($MySQL_Query);   //Store retrieved result in result
//
//
// if ($result->num_rows > 0)  //If Number of Rows > 0
// {
//     // output data of each row
//     while($row = $result->fetch_assoc())
//     {
//         echo "<br/> id: ". $row["MilkTemp"]. " - Name: ". $row["AuxTemp"]. " " . $row["BatteryTemp"] . "<br/>";
//     }
// }//if ends here
// else
// {
//     echo "0 results";
// }







/*Write this function in functions.php, which will export MySQL Data to CSV */
if(isset($_POST["Export"]))
{

     header('Content-Type: text/csv; charset=utf-8');
     header('Content-Disposition: attachment; filename=data.csv');
     $output = fopen("php://output", "w");
     fputcsv($output, array('MilkTemp', 'AuxTemp', 'BatteryTemp'));
     $query = "SELECT MilkTemp, AuxTemp, BatteryTemp FROM entries where SerialNumber =  $serial";
     $result = mysqli_query($con, $query);
     while($row = mysqli_fetch_assoc($result))
     {
          fputcsv($output, $row);
     }
     fclose($output);
}
/*********************************Temporary*****************************/





$fp = fopen("file2.json","w")or die("unable to open file");
$input = $serial;

$posts=array('serial'=>$input);
    $response=$posts;
//    $fp = fopen('file2.json','w');
    fwrite($fp,json_encode($response));
    fclose($fp);

//php ends here
?>

<br>

<style>
table
{
    border-collapse: collapse;
}

th,td
{
    padding:8px;
    text-align:left;
    border-bottom: 1px solid #ddd;
}
tr:nth-child(even){background-color: #f2f2f2}
</style>
</head>
<?php
$username="root";
$password="root";
$database="temp_database";
mysql_connect(localhost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");

//$query="SELECT * FROM tempLog ORDER BY Date DESC,Time DESC,SerialNo DESC,Bat_temp DESC, Milk_temp DESC,Aux_temp DESC,Comp_curr DESC, HP DESC, LP DESC temp_database.tempLog WHERE SerialNo='$input'" ;
$query="SELECT * FROM tempLog WHERE SerialNo=$serial ORDER BY Time DESC";
$result=mysql_query($query);
$num=mysql_numrows($result);
mysql_close();
$tempValues = array();
?>
<table id="dataTbl"  cellspacing="14" cellpadding="10" style="width: 100%">

    <tr>
      <!--style="background-color: #00ff00;"-->
      <th><font face="Arial, Helvetica, sans-serif">Date</font></th>
      <th><font face="Arial, Helvetica, sans-serif">Time</font></th>
      <th><font face="Arial, Helvetica, sans-serif">Serial No</font></th>
      <th><font face="Arial, Helvetica, sans-serif">Battery Temp</font></th>
      <th><font face="Arial, Helvetica, sans-serif">Milk Temp</font></th>
      <th><font face="Arial, Helvetica, sans-serif">Ambient Temp</font></th>
      <th><font face="Arial, Helvetica, sans-serif">Comp Current</font></th>
      <th><font face="Arial, Helvetica. sans-serif">HP</font></th>
      <th><font face="Arial, Helvetica. sans-serif">LP</font></th>
    </tr>

<?php
$i=0;
while ($i < $num)
{
	$dateAndTemps = array();
	$f1=mysql_result($result,$i,"Date");
	$f2=mysql_result($result,$i,"Time");
        $f3=mysql_result($result,$i,"SerialNo");
	$f4=mysql_result($result,$i,"Bat_temp");
        $f5=mysql_result($result,$i,"Milk_temp");
	$f6=mysql_result($result,$i,"Aux_temp");
	$f7=mysql_result($result,$i,"Comp_curr");
	$f8=mysql_result($result,$i,"HP");
	$f9=mysql_result($result,$i,"LP");
?>
<tr>
<td><b><font face="Arial, Helvetica, sans-serif"><?php echo $f1 ;?></font></b></td>
<td><font face="Arial, Helvetica, sans-serif"><?php echo $f2; ?></font></td>
<td><font face="Arial, Helvetica, sans-serif"><?php echo $f3; ?></font></td>
<td><font face="Arial, Helvetica, sans-serif"><?php echo $f4; ?></font></td>
<td><font face="Arial, Helvetica, sans-serif"><?php echo $f5; ?></font></td>
<td><font face="Arial, Helvetica, sans-serif"><?php echo $f6; ?></font></td>
<td><font face="Arial, Helvetica, sans-serif"><?php echo $f7; ?></font></td>
<td><font face="Arial, Helvetica, sans-serif"><?php echo $f8; ?></font></td>
<td><font face="Arial, Helvetica, sans-serif"><?php echo $f9; ?></font></td>
</td>
</tr>

<?php
$i++;}
?>
</table>
</body>
</html>
