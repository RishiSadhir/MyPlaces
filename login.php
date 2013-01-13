
<?php
  if(!isset($_SESSION))
  {
      session_start();
  }
  $pageurl = "login.php";
  
  $q_users = "SELECT * FROM Users";
  
  $db_user = "rks25";
  $db_pass = "21250send";
  $db_name = 'rks25db';
  $mysqli = new mysqli("localhost", $db_user, $db_pass, $db_name);
  if ($mysqli->connect_errno)
  {
  // Check Connection
      echo "Failed to connect to MySQL: " . $mysqli->connect_error;
  }
  $rslt = $mysqli->query($q_users);
  while ($row = $rslt->fetch_assoc())
  {
      $rows[] = $row;
  }
  if(isset($_POST['hidden']))
  {
      $bool = false;
      $uid;
      foreach ($rows as $r)
      {
	  if ($r['username'] == $_POST['username'] && $r['password'] == $_POST['password'])
	  {
	      $bool = true;
	      $uid = $r['userID'];
	  }
      }
      if ($bool)
      {
	  setcookie('authorised', 'true', time()+7200);
	  $_SESSION['userid'] = $uid;
	  header('Location: myplaces.php');
	  exit;
      }
      else
      {
	  echo '<script type="text/javascript">';
	    echo 'alert(could not log in!);';
	    echo '</script>';
	    header('Location: register.php');
	    exit;
      }
  }
?>

<html>
  <head>
    <title>Login</title>
  </head>
  <h1>Please login below:</h1>
  <form action="login.php" method="post">
    <input type="hidden" name="hidden" value="hidden" />
    Username: <input type="text" name="username" />
    Password: <input type="password" name="password" />
    <input type="submit" title="I agree" value="I agree" name="submit" />
  </form>
  
  <hr />
  
  <a href="http://cs1520.cs.pitt.edu/~rks25/php/recitation10/register.php">Register!</a>
  
</html>
