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
  
$i=0;
while ($i < $num)
{
        $dateAndTemps = array();
        $datetime = mysql_result($result,$i,"datetime");
        $temp = mysql_result($result,$i,"temperature");

        $dateAndTemps["Date"] = $datetime;
        $dateAndTemps["Temp"] = $temp;

        $tempValues[$i]=$dateAndTemps;
        $i++;
}
$result = json_encode($tempValues);
echo $result;
?>

