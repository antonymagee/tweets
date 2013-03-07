<?php

	session_start();

	include 'connect.php';

	$escaped_id = $_SESSION['id'];

	$query = "DELETE FROM `storedtweets` WHERE `memid` = '$escaped_id'";

	mysql_query($query)or die(mysql_error());

	header("location: index.php")

?>