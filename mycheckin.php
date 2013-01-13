<?php
  if(!isset($_SESSION['userid']))
  {
      session_start();
  }
  
  if(!isset($_COOKIE['authorised']) || ($_COOKIE['authorised'] != 'true'))
  {
      include('login.php'); exit;
  }
  else
  {
      $current_user_id = $_SESSION['userid'];
  }
?>

<html>
  <head>
    <script type="text/javascript">
      function showHint(str)
      {
	  var xmlhttp;
	  if (str.length==0)
	  {
	      document.getElementById("txtHint").innerHTML="";
	      return;
	  }
	  if (window.XMLHttpRequest)
	  {
	      xmlhttp=new XMLHttpRequest();
	  }
	  else
	  {
	      xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	  
	  xmlhttp.onreadystatechange=function()
	  {
	      if (xmlhttp.readyState==4 && xmlhttp.status==200)
	      {
		  document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
	      }
	  }
	  xmlhttp.open("GET","source-places.php?param="+str,true);
	  xmlhttp.send();
      }
    </script>
    <?php
      $db_user = "rks25";
      $db_pass = "21250send";
      $db_name = 'rks25db';
      $mysqli = new mysqli("localhost", $db_user, $db_pass, $db_name);
      if ($mysqli->connect_errno)
      {
	  echo "Failed to connect to MySQL: " . $mysqli->connect_error;
      }
    ?>
  </head>
  <body>
    
    <h3>Search Places in the area:</h3>
    <form name ="input" method="post">
      Enter Place: <input type="text" name="Place" onkeyup="showHint(this.value)" />
    <p>Suggestions: <span id="txtHint"></span></p>
    
      Insert any comments:<textarea name="input"></textarea>
      <input type = "submit" value = "Submit" name="Submit">
    </form>
    
    <?php
	  $form_data = array_merge($_GET, $_POST);
      if (isset($form_data['Submit']))
      {
	  $query_getid = "SELECT * FROM  `Places`";
	  $rslt_places = $mysqli->query($query_getid);
	  $bool = false;
	  $placeid;
	  while ($rp = $rslt_places->fetch_assoc())
	  {
	      if ($rp['name'] == $form_data['Place'])
	      {
		  $bool = true;
		  $placeid = $rp['placeID'];
	      }
	  }
	  if ($bool == false)
	  {
	      echo '<script type="text/javascript">';
	      	echo "alert('That place does not exist!')";
	      	echo '</script>';
	  }
	  else
	  {
	      $query_insert = "INSERT INTO `rks25db`.`Checkins` (`userID`, `placeID`, `checkin`, `comment`) VALUES ('$current_user_id', '$placeid', CURRENT_TIMESTAMP, '".$form_data['input']."');";
	      if($rslt_insert = $mysqli->query($query_insert) == false)
	      {
		  echo $query_insert;
		  echo '<script type="text/javascript">';
		  echo "alert($query_insert)";
		  echo '</script>';
	      }
	      else
	      {
		  echo '<script type="text/javascript">';
		  echo "alert('Thank you for checking in!')";
		  echo '</script>';
	      }
	  }
      }
    ?>
  </body>
</html>
