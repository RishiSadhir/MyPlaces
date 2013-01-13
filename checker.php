<?php

$form_data = array_merge($_GET, $_POST);

$db_user = "rks25";
$db_pass = "21250send";
$db_name = 'rks25db';
$mysqli = new mysqli("localhost", $db_user, $db_pass, $db_name);
if ($mysqli->connect_errno)
{
// Check Connection
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
}

$query = "SELECT username FROM Users";

$rslt = $mysqli->query($query);
while ($row = $rslt->fetch_assoc())
{
    $rows[] = $row;
}

$bool = false;
foreach ($rows as $r)
{
    if ($r['username'] == $form_data['username'])
    {
	$bool = true;
	break;
    }
}

if ($bool)
{
    echo "<h5 style='color:red;'> Taken </h5>";
}
else
{
    echo "<h5 style='color:green;'> Available </h5>";
}

?>