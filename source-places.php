<?php

$db_user = "rks25";
$db_pass = "21250send";
$db_name = 'rks25db';
$mysqli = new mysqli("localhost", $db_user, $db_pass, $db_name);
if ($mysqli->connect_errno)
{
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
}

$q = $_GET['param'];
$qlen = strlen($q);

$query = "SELECT name FROM Places";

$rslt = $mysqli->query($query);
while ($row = $rslt->fetch_assoc())
{
    $places[] = $row['name'];
}

if($qlen > 0)
{
    $hint = "";
    
    for($i = 0; $i<count($places); $i++)
    {
	$tmp = strtolower($places[$i]);
	$q = strtolower($q);
	if(preg_match("/$q/i", $tmp))
	{	    
		echo $places[$i]." - ";
	}
    }
}
?>
