<?php

$server_name = $_SERVER['SERVER_NAME'];

if (preg_match("/^enormousbrain\.(.)*$/", $server_name)) { // Development site
	define('HOST','beeerdb.db.8498345.hostedresource.com'); // MySQL Host name
	define ("USERNAME","beeerdb"); // DB Username
	define ("PASSWORD","B333rs!!"); // DB Password
	define ("DB","beeerdb"); // DB Name
} else {
	define('HOST','beeerdbinst323.cal64hwnq8li.us-west-2.rds.amazonaws.com'); // MySQL Host name
	define ("USERNAME","beeeruser"); // Live DB Application Username
	define ("PASSWORD","b333rpa55"); // Live DB Application Password
	define ("DB","beeerdb"); // DB Name
}

try {
	$db = new PDO('mysql:host='.HOST.';dbname='.DB.';', USERNAME, PASSWORD);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

?>