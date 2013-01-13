<!------------------------------------------>
<!-- rishi sadhir                         -->
<!-- cs1520                               -->
<!-- register.php                         -->
<!-- Provids the user a registration page -->
<!------------------------------------------>

<html>
  <head>
    <title>Register</title>
    <script type="text/javascript">
      function loadXMLDoc(uname) {
	var xmlhttp;
	if (window.XMLHttpRequest) {
	    // code for IE7+, Firefox, Chrome, Opera, Safari
	    xmlhttp=new XMLHttpRequest();
	} else {
		// code for IE6, IE5
	    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function() {
  		if (xmlhttp.readyState==4 && xmlhttp.status==200) {
    			document.getElementById("usercheck").innerHTML = xmlhttp.responseText; 
    		}
  	}
	xmlhttp.open("GET", "checker.php?username="+uname,true);
	xmlhttp.send();
      }

    </script>
    <?php
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
      // userID         username        password        first_name      last_name       email
      }
    ?>
  </head>
  <body>
    <h1>Register Below:</h1>
    <hr />

    <form method="post">
      
      <table id="logintable" width="15" cellspacing="" cellpadding="" border="0">
	<tr>
	  <td>Username</td><td><input type="text" name="Username" id="Username"/></td><td><input type="button" name="Check" onclick="loadXMLDoc(document.getElementById('Username').value)" value="Check" /></td><td><div id="usercheck"></div></td>
	</tr>
	<tr>
	  <td>Password</td><td><input type="text" name="Password" /></td>
	</tr>
	<tr>
	  <td>First</td><td><input type="text" name="First" /></td>
	</tr>
	<tr>
	  <td>Last</td><td><input type="text" name="Last" /></td>
	</tr>
	<tr>
	  <td>Email</td> <td> <input type="text" name="Email"/></td>
	</tr>
      </table>
	<input type="submit" name="submit" value="submit" />      
	<input type="hidden" name="hidden" value="hidden" />
    </form>
    <?php
      $form_data = array_merge($_GET, $_POST);
      if (isset($form_data['submit']))
      {
	  $checked = false;
	  foreach ($rows as $r)
	  {
	      if($form_data['Username'] == $r['username'] )
	      {
		  $checked = true;
	      }
	  }
	  if ($checked)
	  {
	      echo '<script type="text/javascript">';
	      echo "alert('That username already exists!')";
	      echo '</script>';
	  }
	  else
	  {
	      $q_insert = 'INSERT INTO `rks25db`.`Users` (`userID`, `username`, `password`, `first_name`, `last_name`, `email`) VALUES (NULL, "'.$form_data['Username'].'", "'.$form_data['Password'].'", "'.$form_data['First'].'", "'.$form_data['Last'].'", "'.$form_data['Email'].'");';
	      $i_rslt = $mysqli->query($q_insert);
	      if ($i_rslt)
	      {
		  echo '<script type="text/javascript">';
		    echo 'alert("Successfully registered!")';
		    echo '</script>';
		    echo '<a href="myplaces.php"></a>';
	      }
	      else
	      {
		  echo '<script type="text/javascript">';
		    echo 'alert("Could not register!")';
		    echo '</script>';
	      }
	  }
	  
      }
      else
      {
	  echo "<h3>Welcome! Please Register Above!</h3>";
      }
    ?>
  </body>
  
</html>
