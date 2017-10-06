<?php
session_start();
$serialNo = $_SESSION['serialNo'];
?>
<html>
<b align="left">
 <th>Serial no:</th>
   <?php $serial= $_SESSION['serialNo'];
   $_SESSION["serialNo"]=$serialNo;
   echo $serialNo;?>
 </b>
</br>
<?php
$username="root";
$password="root";
$database="temp";
mysql_connect(localhost,$username,$password);
@mysql_select_db($database)or die("Unable to select datbase");
?>
<head>
<script>
    function myFunction() {
        var x;
        var r = confirm("Do you want to clear data?");
        if (r == true) {
	    <?php
            $query="DELETE  FROM compData WHERE SerialNo='".$serialNo."'";
	    $result=mysql_query($query);
	    mysql_close();?>
	    x = "Your Data is Cleared";
            window.location.href = "firstpage.php";
  }
        else {
            x = "You pressed Cancel!";
        }
        document.getElementById("demo").innerHTML = x;
    }
</script>
</head>
<body>
<button onclick="myFunction()">Retest</button>

<p id="demo"></p>
</body>
</html>
