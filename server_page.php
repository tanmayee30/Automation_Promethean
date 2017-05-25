<!DOCTYPE html>
<html lang="en">
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<head>

  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>

  <!-- These are for Date Picker -->
  <script src="https://code.jquery.com/jquery-2.2.3.min.js"   integrity="sha256-a23g1Nt4dtEYOj7bR+vTu7+T8VP13humZFBJNIYoEJo="   crossorigin="anonymous"></script>
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.0/js/bootstrap-datepicker.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.1/css/bootstrap-datepicker3.min.css">


  <script>
  var from="from-datepicker";
  var to="to-datepicker";
  $( document ).ready(function()
  {
    pickStartDate(from);
    $("#xx").on('click', function (event)
  	{
  			exportTableToCSV.apply(this, [$('#dataTbl'), 'data.csv']);
  	});//download Button for CSV
  }); //ready ends here
  function pickStartDate(containerID)
  {
      $("#"+containerID).datepicker({
          format: 'yyyy-mm-dd',
      });
      $("#"+containerID).on("change", function ()
  	{
    var start = $(this).val();
    console.log("From:\t"+start);
    //This start date is the 'From' Date received here
  	$("#"+containerID).datepicker('hide');
  	pickEndDate(to, start);
      });
  }//pickStartDate ends here
  function pickEndDate(containerID, startValue)
  {
      $("#"+containerID).datepicker({
          format: 'yyyy-mm-dd',
      });
      $("#"+containerID).on("change", function ()
  	{
    var end = $(this).val();
    console.log("To:\t"+end);
    //This end is the 'To' Date received here
    $("#"+containerID).datepicker('hide');
    }); //change ends here
  }//pickEndDate ends here
  function exportTableToCSV($table, filename)
  {
      var $rows = $table.find('tr:has(td),tr:has(th)'),
  			            // Temporary delimiter characters unlikely to be typed by keyboard
  			            // This is to avoid accidentally splitting the actual contents
  			            tmpColDelim = String.fromCharCode(11), // vertical tab character
  			            tmpRowDelim = String.fromCharCode(0), // null character
  			            // actual delimiter characters for CSV format
  			            colDelim = '","',
  			            rowDelim = '"\r\n"',
  			            // Grab text from table into CSV formatted string
  			            csv = '"' + $rows.map(function (i, row) {
  			                var $row = $(row), $cols = $row.find('td,th');
  			                return $cols.map(function (j, col) {
  			                    var $col = $(col), text = $col.text();
  			                    return text.replace(/"/g, '""'); // escape double quotes
  			                }).get().join(tmpColDelim);
  			            }).get().join(tmpRowDelim)
  			                .split(tmpRowDelim).join(rowDelim)
  			                .split(tmpColDelim).join(colDelim) + '"',
  			            // Data URI
  			            csvData = 'data:application/csv;charset=utf-8,' + encodeURIComponent(csv);
  			            //console.log(csv);
  			        	if (window.navigator.msSaveBlob) { // IE 10+
  			        		//alert('IE' + csv);
  			        		window.navigator.msSaveOrOpenBlob(new Blob([csv], {type: "text/plain;charset=utf-8;"}), "csvname.csv")
  			        	}
  			        	else {
  			        		$(this).attr({ 'download': filename, 'href': csvData, 'target': '_blank' });
  			        	}
  }	//exportTableToCSV() ends here
  </script>

<style>
#dataTbl td, th
{
    text-align: center;
    vertical-align: middle;
    font-family:Arial,Verdana,sans-serif;
    padding: 10px;
    border: 1px solid green;
}
</style>


</head>
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

  <div style="width:100%; float: center" align="center">
  Load Charts
  	From:<input type="text" id="from-datepicker" style="margin-right:30px;margin-left:30px;" />
  	To:<input type="text" id="to-datepicker" style="margin-right:30px;margin-left:30px;" />
  </div>
  <br />

  <div style="width:100%; float: center" align="center">
  <a href="#" id="xx" style="text-decoration:none;">
  	<button style="float:center" id="button">Export Data </button>
  </a>
  </div>
  <br />

  <table id="dataTbl" cellspacing="14" cellpadding="10" style="width:70%; float:center;" align="center">
    <tr>
      <!--style="background-color: #00ff00;"-->
      <th> <font face="Arial, Helvetica, sans-serif">DateTime</font></th>
      <th><font face="Arial, Helvetica, sans-serif">Battery Temp</font></th>
      <th><font face="Arial, Helvetica, sans-serif">Milk Temp</font></th>
      <th><font face="Arial,Helvetica, sans-serif">Ambient Temp</font></th>
      <th><font face="Arial, Helvetica, sans-serif">Comp Current</font></th>
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
