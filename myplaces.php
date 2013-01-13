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

    <?php
      $q_markers = <<<qmarkers
SELECT * FROM `Places` WHERE 1
qmarkers;

      $q_all_users = <<<qallu
SELECT p.placeID, count(c.placeID) as allCount
FROM `Places` p
inner join `Checkins` c on p.placeID=c.placeID
group by p.name
qallu;

      $q_this_user = "SELECT p.placeID as placeID, count(c.placeID) as thisCount FROM `Places` p inner join `Checkins` c on p.placeID=c.placeID where c.userID = $current_user_id group by p.name";
      
      $db_user = "rks25";
      $db_pass = "21250send";
      $db_name = 'rks25db';
      $mysqli = new mysqli("localhost", $db_user, $db_pass, $db_name);      
      	  if ($mysqli->connect_errno)
     	    {
	      // Check Connection
	      echo "Failed to connect to MySQL: " . $mysqli->connect_error;
	    }
      $rslt = $mysqli->query($q_markers);
      $all =  $mysqli->query($q_all_users);
      $mine =  $mysqli->query($q_this_user);
      $i = 0;
    ?>

    
    <script type="text/javascript"
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCK2AJCo3yZBgNCtPx5J_7gzuoC4CGmgH4&sensor=true">
    </script>
    <script type="text/javascript">
      var infowindow;
      
      function initialize()
      {
	  var mapOptions =
	      {
		  center: new google.maps.LatLng(40.444805, -79.962679),
		  zoom: 15,
		  mapTypeId: google.maps.MapTypeId.ROADMAP
	      };
	  var map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
	  infowindow = new google.maps.InfoWindow({
	      maxWidth: 355
	  });	  
	  <?php
	  $i = 0;

	    while ($row = $rslt->fetch_assoc())
	    {
		$rows[] = $row;
	    }
	    while ($a = $all->fetch_assoc())
	    {
		$everyones[] = $a;
	    }
	    while ($t = $mine->fetch_assoc())
	    {
		$mines[] = $t;
	    }

	    foreach($rows as $r)
	    {
		$lat = $r['latitude'];
		$long = $r['longitude'];
		$name = '"'.$r['name'].'"';
		$placeid = $r['placeID'];
		$all_num = 0; $me_num = 0;
		foreach ($everyones as $e)
		{
		    
		    if ($e['placeID'] == $placeid)
		    {
			$all_num = $e['allCount'];
			break;
		    }
		}
		foreach ($mines as $m)
		{
		    if ($m['placeID'] == $placeid)
		    {
			$me_num = $m['thisCount'];
			break;
		    }		    
		}
		echo "var myLatLng$i = new google.maps.LatLng($lat, $long);";
		echo "var marker$i = new google.maps.Marker({position: myLatLng$i, map: map, title: $name});";
		// Just pass this guy all the needed information from the query
		echo "popupDirections(marker$i, $name, $placeid, $all_num, $me_num);";
		$i++;		
	    }
	  ?>

	  function popupDirections(marker, name, placeid, a_checkins, m_checkins)
	  {
	      var content = '<div id="title"><h2><a href=oneplace.php?id='+placeid+'>'+name+'</a></h2></div>';
	      var content2 = '<div id=bdy><h4>My Checkins : '+m_checkins+'<h4></div>';
	      var content3 = '<div id=bdy2><h4>All Checkins : '+a_checkins+'<h4></div>';	      

	      google.maps.event.addListener(marker, 'click', function(){
		  infowindow.setContent(content+content2+content3);
		  infowindow.open(map, marker);
	      });
	  }
      }
    </script>
    
  </head>
  <body onload="initialize()">
    <h3>My Places</h3>    
    <hr />
    <div id="map_canvas" style="width:100%; height:90%">
    </div>    
  </body>
</html>
