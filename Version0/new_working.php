<!DOCTYPE html>
<html>
<head>
  <meta content="width=device-width, initial-scale=1.0" name="viewport" >
  <!-- These files are needed for Bootstrap. Do not delete them -->
  <script
  src="https://code.jquery.com/jquery-3.2.1.js"
  integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
  crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" type="text/css" />

  <style>
  table {
    border-collapse: collapse;
  }

  th,td {
    padding:8px;
    text-align:left;
    border-bottom: 1px solid #ddd;
  }
  tr:nth-child(even){background-color: #f2f2f2}
  </style>

  <?php
  session_start();
  $serial = $_SESSION['serialNo'];
  $brand = $_SESSION['companyName'];
  $name = $_SESSION['testedBy'];
  $machine = $_SESSION['machineNum'];
  $page = $_SERVER['PHP_SELF'];
  header("Refresh:5; url=$page");
  ?>

  <nav class="navbar navbar-default">
    <div class="container-fluid">
      <div class="navbar-header">
        <a class="navbar-brand" href="http://www.promethean-power.com"><p> <img src="promethean-icon.ico"/> Promethean Spenta Technologies</p></a>
      </div>
      <!-- <div class="nav navbar-nav navbar-right" style="padding-top: 1%; padding-right: 6%;padding-bottom: 1%; ">
      </div> -->
  </div>
  </nav>

  <div class="container">
  	<div class="row">
  		<div style="float: left" style="height:100%;">
            <label>Serial Number:</label>
            <label><?php echo $serial; ?></label>
            <br />
            <label>Company Name:</label>
            <label><?php echo $brand; ?></label>
            <br />
            <label>Tested By:</label>
            <label><?php echo $name; ?></label>
            <br />
      </div>
      <div style="float: right;">
        <table>
          <tr>
            <td>
              <button type="submit" onclick="window.location.href='popup.php'" class="btn btn-success" style="width:115%">
                Retest
              </button>
            </td>
            <td>
              <button type="submit" class="btn btn-success" onclick="window.location.href='excelReport.php'" style="width:100%">
                Download
              </button>
            </td>
            <td>
              <button type="submit" onclick="window.location.href='firstpage.php'" class="btn btn-success" style="width:100%">
                New Test
              </button>
            </td>
          </tr>
        </table>
      </div>
    </div>
    <!-- Container ends below -->
  </div>

</head>

<h3><center>Compressor Test Log</center></h3>

<?php

/*This is Commented by me*/
 $fp = fopen("temp.json","w")or die("unable to open file");
// $input1 = $serial;
// $input2 = $brand;
// $input3 = $name;
 $posts=array('serial'=>$serial,'companyName'=>$brand,'personName'=>$name,'machineNum'=>$machine);
 $response=$posts;
 fwrite($fp,json_encode($response));
 fclose($fp);
/*This is Commented by me*/

$username="root";
$password="root";
$database="temp";
 mysql_connect(localhost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");

/*This was already Commented*/
//$query="SELECT * FROM tempLog ORDER BY Date DESC,Time DESC,SerialNo DESC,Bat_temp DESC, Milk_temp DESC,Aux_temp DESC,Comp_curr DESC, HP DESC, LP DESC temp_database.tempLog WHERE SerialNo='$input'" ;
//$query="SELECT * FROM tempLog WHERE SerialNo='".$serial."' ORDER BY Time DESC";
/*This was already Commented*/

 $query="SELECT * FROM compData WHERE SerialNo='".$serial."'";
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
    <th><font face="Arial, Helvetica, sans-serif">Brine in</font></th>
    <th><font face="Arial, Helvetica, sans-serif">Brine out</font></th>
    <th><font face="Arial, Helvetica, sans-serif">Suction</font></th>
    <th><font face="Arial, Helvetica, sans-serif">Discharge</font></th>
    <th><font face="Arial, Helvetica, sans-serif">Ambient Temp</font></th>
    <th><font face="Arial, Helvetica, sans-serif">Comp Current</font></th>
    <th><font face="Arial, Helvetica, sans-serif">HP</font></th>
    <th><font face="Arial, Helvetica, sans-serif">LP</font></th>
  </tr>

  <?php
 $i=0;
 while ($i < $num)
 {
   $dateAndTemps = array();
   $f1=mysql_result($result,$i,"Date");
   $f2=mysql_result($result,$i,"Time");
   $f3=mysql_result($result,$i,"SerialNo");
   $f5=mysql_result($result,$i,"Bat_temp");
   $f4=mysql_result($result,$i,"Milk_temp");
   $f6=mysql_result($result,$i,"suction");
   $f7=mysql_result($result,$i,"discharge");
   $f8=mysql_result($result,$i,"Aux_temp");
   $f9=mysql_result($result,$i,"Comp_curr");
   $f10=mysql_result($result,$i,"HP");
   $f11=mysql_result($result,$i,"LP");
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
      <td><font face="Arial, Helvetica, sans-serif"><?php echo $f10; ?></font></td>
      <td><font face="Arial, Helvetica, sans-serif"><?php echo $f11; ?></font></td>
    </td>
  </tr>

  <?php
 $i++;}
  ?>
</table>
</body>
</html>
