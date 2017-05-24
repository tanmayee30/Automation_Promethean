<html>
<body>
<head><meta http-equiv="refresh" content="5" > </head>
<?php

$username="root";
$password="root";
$database="temp_database";

mysql_connect(localhost,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");

$query="SELECT * FROM tempLog ORDER BY datetime DESC, Bat_temp DESC, Milk_temp DESC,Aux_temp DESC,Comp_curr DESC";
$result=mysql_query($query);


$num=mysql_numrows($result);

mysql_close();
$tempValues = array();
?>
  <caption><th><h1>Data Logger</h1></th></caption>
  <table  border="7" cellspacing="14" cellpadding="10" style="width:100%; float:center;" align="center">
    <tr>
      <th style="background-color: #00ff00;">  <font face="Arial, Helvetica, sans-serif">DateTime</font></th>
      <th style="background-color: #00ff00"><font face="Arial, Helvetica, sans-serif">Battery Temp</font></th>
      <th style="background-color: #00ff00;"><font face="Arial, Helvetica, sans-serif">Milk Temp</font></th>
      <th style="background-color: #00ff00;"><font face="Arial,Helvetica, sans-serif">Ambient Temp</font></th>
      <th style="background-color: #00ff00;"><font face="Arial, Helvetica, sans-serif">Comp Current</font></th>
    </tr>

<?php

$i=0;
while ($i < $num)
{
	$dateAndTemps = array();
	$f1=mysql_result($result,$i,"datetime");
	$f2=mysql_result($result,$i,"Bat_temp");
        $f3=mysql_result($result,$i,"Milk_temp");
	$f4=mysql_result($result,$i,"Aux_temp");
	$f5=mysql_result($result,$i,"Comp_curr");
?>
<tr>
<td><b><font face="Arial, Helvetica, sans-serif"><?php echo $f1 ;?></font></b></td>
<td><font face="Arial, Helvetica, sans-serif"><?php echo $f2; ?></font></td>
<td><font face="Arial, Helvetica, sans-serif"><?php echo $f3; ?></font></td>
<td><font face="Arial, Helvetica, sans-serif"><?php echo $f4; ?></font></td>
<td><font face="Arial, Helvetica, sans-serif"><?php echo $f5; ?></font></td>
</td>
</tr>

<?php
$i++;}
?>
</table>
</body>
</html>
