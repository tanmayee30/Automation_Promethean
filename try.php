<html>
<body>
<head><meta http-equiv="refresh" content="5" > </head>
<?php
  
$username="root";
$password="root";
$database="temp_database";

mysql_connect(localhost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");

$query="SELECT * FROM tempLog";
$result=mysql_query($query);

$num=mysql_numrows($result);

mysql_close();
$tempValues = array();
?>

<table style="width:100%">
  <caption><th>Data Logger</th></caption>
<table border="4" cellspacing="4" cellpadding="4">
<tr>
<th>
<font face="Arial, Helvetica, sans-serif">DateTime</font>
</th>
<th>
<font face="Arial, Helvetica, sans-serif">Milk Temp</font>
</th>
<th>
<font face="Arial, Helvetica, sans-serif">Ambient Temp</font>
</th>
<th>
<font face="Arial,Helvetica, sans-serif">Battery Temp</font>
</th>
<th>
<font face="Arial, Helvetica, sans-serif">Comp Current</font>
</th>
</tr>
<?php

$i=0;
while ($i < $num) 
{
	$dateAndTemps = array();
	$f1=mysql_result($result,$i,"datetime");
	$f2=mysql_result($result,$i,"Milk_temp");
        $f3=mysql_result($result,$i,"Ambient_temp");
	$f4=mysql_result($result,$i,"Battery_temp");
	$f5=mysql_result($result,$i,"Comp_current");
?>
<tr>
<td>
<font face="Arial, Helvetica, sans-serif"><?php echo $f1 ;?></font>
</td>
<td>
<font face="Arial, Helvetica, sans-serif"><?php echo $f2; ?></font>
</td>
<td><font face="Arial, Helvetica, sans-serif"><?php echo $f3; ?></font></td>
<td><font face="Arial, Helvetica, sans-serif"><?php echo $f4; ?></font></td>
<td><font face="Arial, Helvetica, sans-serif"><?php echo $f5; ?></font></td>
</td>
</tr>
<?php
$i++;}
?>
</body>
</html>

