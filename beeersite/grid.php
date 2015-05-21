<?php

error_reporting(E_ERROR);

require_once('db_connection.php');

if (empty($_REQUEST['do'])==FALSE) 
	{
		switch( clean($_REQUEST["do"]) )
		{				
			case "popular":			
				
				$statement=$db->prepare("SELECT avg(rating) rat, count(name) num, name, brewery from full_reviews group by name, brewery order by num desc");
				$statement->execute();
				$results=$statement->fetchAll(PDO::FETCH_ASSOC);
				$json=json_encode($results);
				print $json;

			break;

			case "rating":			
				
				$statement=$db->prepare("SELECT avg(rating) rat, count(name) num, name, brewery from full_reviews group by name, brewery order by rat desc");
				$statement->execute();
				$results=$statement->fetchAll(PDO::FETCH_ASSOC);
				$json=json_encode($results);
				print $json;

			break;			
			
			case "recent":			
				
				$statement=$db->prepare("SELECT max(auto_id) id, avg(rating) rat, count(name) num, name, brewery from full_reviews group by name, brewery order by id desc");
				$statement->execute();
				$results=$statement->fetchAll(PDO::FETCH_ASSOC);
				$json=json_encode($results);
				print $json;

			break;		
			
			
		}
	}


function clean($string, $datatype='string')
	{
	
	$string = rawurldecode($string);
	
	$string = preg_replace(array("/DELETE/i", "/SELECT/i", "/UPDATE/i","/UNION/i","/INSERT/i","/DROP/i","/--/i"), "", $string);

   	$search = array(
		'@&lt;script[^&gt;]*?&gt;.*?&lt;/script&gt;@si',  	// Strip out javascript
		'@&lt;[\/\!]*?[^&lt;&gt;]*?&gt;@si',            		// Strip out HTML tags
		'@&lt;style[^&gt;]*?&gt;.*?&lt;/style&gt;@siU',   	// Strip style tags properly
		'@&lt;![\s\S]*?--[ \t\n\r]*&gt;@'         			// Strip multi-line comments
	);
	$string = preg_replace($search, '', $string);			// Actual filtering
	
	$string = trim($string);								// Strips whitespace (or other characters) from the beginning and end of a string (including NUL-byte)
	
	settype($string, $datatype);
	
	return $string;

	}

?>