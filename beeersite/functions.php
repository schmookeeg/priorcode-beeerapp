<?php
error_reporting(E_ERROR);
//function CleanQueryParameter($string, $datatype='string')
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
		/*
echo "CLEANING... $string <br>\n";
		if(get_magic_quotes_gpc()){
			$string = stripslashes($string);					// Additional filtering for NUL-byte
			echo "STRIP SLASHES... $string <br>\n";
		}
		elseif (function_exists('mysql_real_escape_string'))
		{
			$string = mysql_real_escape_string($string);
			echo "REAL ESCAPE STR... $string <br>\n";
		}
		else
		{
			$string = addslashes($string);
			echo "ADDSLASHES... $string <br>\n";
		}
*/
		
		settype($string, $datatype);
		
		return $string;

	}

function objMerge($current,$add)
{
	//echo'<pre>';print_r($add);echo'</pre>';
	foreach($add as $n => $v)
	{
		$current->$n = $v;
	}
	//echo'<pre>';print_r($current);echo'</pre>';
	return $current;
}

require_once('functionsAutoComplete.php');
require_once('functionsGet.php');
require_once('functionsSet.php');
require_once('functionsMain.php');
?>