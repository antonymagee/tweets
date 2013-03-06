<?php

include 'connect.php'; 

$id = $_GET['id'];

$escaped_id = mysql_real_escape_string($id);

$query = $sql = "UPDATE storedtweets ".
       "SET approved = 1 ".
       "WHERE id = $escaped_id" ;

//$query = "DELETE FROM `storedtweets` WHERE `id` = '$escaped_id'";

mysql_query($query);

header("location: index.php");



?>