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
    <title>One Place</title>
    <?php
      $db_user = "rks25";
      $db_pass = "21250send";
      $db_name = 'rks25db';
      $mysqli = new mysqli("localhost", $db_user, $db_pass, $db_name);
      if ($mysqli->connect_errno)
      {
	  echo "Failed to connect to MySQL: " . $mysqli->connect_error;
      }
      $placeid = $_GET['id'];
      $query_all = "select * from Checkins where placeID = $placeid order by checkin desc;";
      $query_mycount = "select count(*) as mycount from Checkins where placeID = $placeid and userID = $current_user_id;";
      $query_mylast = "SELECT * FROM Checkins where userID = $current_user_id and placeID = $placeid order by checkin desc;";
      $query_getname = "SELECT name FROM Places where placeID = $placeid";
      
      
      $rslt_all = $mysqli->query($query_all);
      $rslt_mycount = $mysqli->query($query_mycount);
      $rslt_mylast = $mysqli->query($query_mylast);
      $rslt_getname = $mysqli->query($query_getname);
      
      $num_checkins =  $rslt_all->num_rows;
      
      while ($row = $rslt_getname->fetch_assoc())
      {
	  $r_getname[] = $row;
      }
      while ($row = $rslt_all->fetch_assoc())
      {
	  $r_all[] = $row;
      }
      while ($row = $rslt_mycount->fetch_assoc())
      {
	  $r_mycount[] = $row;
      }
      while ($row = $rslt_mylast->fetch_assoc())
      {
	  $r_mylast[] = $row;
      }
      echo "<h2>".$r_getname[0]['name']."</h2><hr>";
      echo"<h3>Your last checkin :  ";
      if ($rslt_mylast->num_rows>0)
      {
	  echo $r_mylast[0]['checkin']."</h3>";	  
      }
      else
      {
	  echo "0";
      }
      echo "<h3> You have checked in this many times: ".$r_mycount[0]['mycount']."</h3>";
      echo "<h3> Total number of checkins from all users: ".$num_checkins."</h3>";
      echo "<br>";
    ?>
    <table width="6" cellspacing="5" cellpadding="30" border="1">
      <tr>
        <td><h3>Comments</h3></td>
      </tr>
      <?php
	foreach ($r_all as $r)
      	{
	    if ($r['userID'] == $current_user_id)
	    {
		echo "<tr><td style='font-weight:bold;'>".$r['comment']."</td></tr>";
	    }
	    else
	    {
		echo "<tr><td>".$r['comment']."</td></tr>";
	    }
	}	
      ?>
    </table>
  </head>
  <body>
    
  </body>
</html>
