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
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" type="text/css">

  <nav class="navbar navbar-default">
    <div class="container-fluid">
      <div class="navbar-header">
        <a class="navbar-brand" href="http://www.promethean-power.com"><p> <img src="promethean-icon.ico"/> Promethean Spenta Technologies</p></a>
      </div>
    </div>
  </nav>

<?php
session_start();
echo "<script>console.log('STARTING SESSION NOW');</script>";

if(isset($_SESSION['serialNo'])){
	/*If SESSION still contains variables stored. Clear it*/
  echo "<script>console.log('SESSION CONTAINS STORED VARIABLES');</script>";
  echo '<script> console.log("'.$_SESSION['serialNo'].'"); </script>';
}
else {
  echo "<script>console.log('SESSION VARIABLE are EMPTY');</script>";
}

//check if Test is Started
if (isset($_POST['Start_Test']))
{
  $serialNo = $_POST['serialNo'];
  $companyName = $_POST['companyName'];
  $testedBy = $_POST['testedBy'];
  $machineNum = $_POST['machineNum'];

  $_SESSION['serialNo'] = $serialNo;
  $_SESSION['companyName'] = $companyName;
  $_SESSION['testedBy'] = $testedBy;
  $_SESSION['machineNum']=$machineNum;
  // echo '<script> console.log("'.$_SESSION['serialNo'].'"); </script>';
  // echo '<script> console.log("'.$_SESSION['companyName'].'"); </script>';
  // echo '<script> console.log("'.$_SESSION['testedBy'].'"); </script>';
  header("Location: new_working.php");
}//end of Start Test if loop
?>


</head>
<body>
<h2 style="text-align:center">Promethean Automation testbed</h2>
<div class="container">
	<div class="row">
		<div class="col-md-4 col-md-offset-4 well">
  <form role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="signupform">
    <div class="form-group">
      <label for="name">Serial Number:</label>
      <input type="text" name="serialNo" placeholder="Enter Serial Number" required value="<?php if($error) echo $serialNo; ?>" class="form-control" />
    </div>
    <div class="form-group">
      <label for="name">Company Name:</label>
      <input type="text" name="companyName" placeholder="Enter Company Name" value="<?php if($error) echo $companyName; ?>" class="form-control" />
    </div>
    <div class="form-group">
      <label for="name">Tested By</label>
      <input type="text" name="testedBy" placeholder="Enter Person Name" value="<?php if($error) echo $testedBy; ?>" class="form-control" />
    </div>
    <div class="form-group">
      <label for="name">Tested By</label>
      <input type="text" name="machineNum" placeholder="Enter Machine Number" value="<?php if($error) echo $machineNum; ?>" class="form-control" />
    </div>

    <div class="form-group">
      <input type="submit" name="Start_Test" value="Submit" class="btn btn-success" style="width:100%"/>
    </div>

  </form>
</div>
</div>
</div>
</body>
</html>
