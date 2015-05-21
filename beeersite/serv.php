<?php

error_reporting(E_ERROR);

require_once('db_connection.php');

if (empty($_REQUEST['do'])==FALSE && empty($_REQUEST['userID'])==FALSE && $_REQUEST['userID'] != "") {
		switch( clean($_REQUEST["do"]) )
		{				
			case "dbBeer":			
					$json = json_decode ($_REQUEST['beeer'], true); 
					
					$beeerArr = [];
					
					
					
					foreach ($json as $key => $val) 
					{
						if($key != "imageURL")
						{
							if($key == "createDate")
								{
								// $beeerArr[ $key ] = date('Y-m-d h:i:s', $val);
								// $beeerArr[ $key ] = $val;
						
								
								$val = substr($val,0,10);
								$val = date('Y-m-d h:i:s', $val);
								$beeerArr[ $key ] = $val;
								}
							else
								{
					    		$beeerArr[ $key ] = $val;
					    		}
					    }
					}
					
					$beeerArr[ "userID" ] = $_REQUEST['userID'];
					
					$stmt = $db->prepare("SELECT * FROM full_reviews WHERE userID=:user_id AND createDate=:createDate");
					
					
					
					$stmt->bindValue(':user_id', $beeerArr['userID']);
					$stmt->bindValue(':createDate', $beeerArr['createDate']);
					
					$logMe = $beeerArr['createDate'];
					
					$stmt->execute();
					$matchingReviewCOunt = $stmt->rowCount();
					
					print $matchingReviewCOunt . "<br>";
					print $beeerArr['createDate'] . "<br>";
					print $beeerArr['userID'] . "<br>";
					
					if($matchingReviewCOunt < 1) {
						print "INSERT<br>";
						// create
						$stmt = $db->prepare("INSERT INTO full_reviews (".implode(',',array_keys($beeerArr)).") VALUES (:".implode(",:",array_keys($beeerArr)).")");
						foreach($beeerArr as $n=>$v) { $pdo[':'.$n]=$v; }
						$stmt->execute($pdo);
						return intval($db->lastInsertId());
					} else {
						print "UPDATE<br>";
						// update
						$setSQL = "";
						foreach($beeerArr as $n=>$v) {
								if($n != "userID" && $n != "createDate")
								{
									if($setSQL == "")
									{
										$setSQL = "$n=:$n ";
									}else
									{
										$setSQL .= ", $n=:$n ";
									}
								}
							}
						print $setSQL . "<br>";
						
						try {
							$stmt = $db->prepare( "UPDATE full_reviews SET $setSQL WHERE userID=:user_id AND createDate=:createDate");
							$stmt->debugDumpParams();
							$stmt->bindValue(':user_id', $_REQUEST['userID']);
							$stmt->bindValue(':createDate', $beeerArr['createDate']);
							$stmt->debugDumpParams();
							print "<br>";
							foreach($beeerArr as $n=>$v) 
							{
								print $n . "=" .  $v . "<br>";
								if($n != "userID" && $n != "createDate")
								{
									$stmt->bindValue(':'.$n, $v);
								}
							}
							$stmt->debugDumpParams();
							print "<br>";
							$stmt->execute();
							/////////////////////////////////////////////////		
							$filename = 'service_log.txt';						
							$somecontent = "EXECUTED.  NO ERR " . $logMe;
				
							// Let's make sure the file exists and is writable first.
							if (is_writable($filename)) {
							
							    // In our example we're opening $filename in append mode.
							    // The file pointer is at the bottom of the file hence
							    // that's where $somecontent will go when we fwrite() it.
							    if (!$handle = fopen($filename, 'w')) {
							         echo "Cannot open file ($filename)";
							         exit;
							    }
							
							    // Write $somecontent to our opened file.
							    if (fwrite($handle, $somecontent) === FALSE) {
							        echo "Cannot write to file ($filename)";
							        exit;
							    }
							
							    echo "Success, wrote ($somecontent) to file ($filename)";
							
							    fclose($handle);
							
							} else {
							    echo "The file $filename is not writable";
							}
							//////////////////////////////////////////////////

						} catch (PDOException $e) {
						    $err = "Error!: " . $e->getMessage() . "<br/>";
						    print $err;
						    /////////////////////////////////////////////////
						    $filename = 'service_log.txt';								
							$somecontent = "ERRRRRRrrrrr: $err ( UPDATE full_reviews SET $setSQL WHERE userID=:user_id AND name=:beer_name )" ;
				
							// Let's make sure the file exists and is writable first.
							if (is_writable($filename)) {
							
							    // In our example we're opening $filename in append mode.
							    // The file pointer is at the bottom of the file hence
							    // that's where $somecontent will go when we fwrite() it.
							    if (!$handle = fopen($filename, 'w')) {
							         echo "Cannot open file ($filename)";
							         exit;
							    }
							
							    // Write $somecontent to our opened file.
							    if (fwrite($handle, $somecontent) === FALSE) {
							        echo "Cannot write to file ($filename)";
							        exit;
							    }
							
							    echo "Success, wrote ($somecontent) to file ($filename)";
							
							    fclose($handle);
							
							} else {
							    echo "The file $filename is not writable";
							}
							//////////////////////////////////////////////////
						    die();
						}
						
						
					}
					
					
				break;
			case "dbPhoto":
					//dbPhotoEntry($db,$_REQUEST['beerName'],$_REQUEST['userID'],$_REQUEST['photoURL']);
					
					/////////////////////////////////////////////////		
							$filename = 'service_log.txt';						
							$somecontent = $_REQUEST['beerName'].", ".$_REQUEST['userID'].", ".$_REQUEST['photoURL'] ;
				
							// Let's make sure the file exists and is writable first.
							if (is_writable($filename)) {
							
							    // In our example we're opening $filename in append mode.
							    // The file pointer is at the bottom of the file hence
							    // that's where $somecontent will go when we fwrite() it.
							    if (!$handle = fopen($filename, 'w')) {
							         echo "Cannot open file ($filename)";
							         exit;
							    }
							
							    // Write $somecontent to our opened file.
							    if (fwrite($handle, $somecontent) === FALSE) {
							        echo "Cannot write to file ($filename)";
							        exit;
							    }
							
							    echo "Success, wrote ($somecontent) to file ($filename)";
							
							    fclose($handle);
							
							} else {
							    echo "The file $filename is not writable";
							}
							//////////////////////////////////////////////////
					
					$stmt = $db->prepare("SELECT * FROM full_reviews WHERE userID=:user_id AND name=:beer_name");
					$stmt->bindValue(':user_id', $_REQUEST['userID']);
					$stmt->bindValue(':beer_name', $_REQUEST['beerName']);
					$stmt->execute();
					$matchingReviewCOunt = $stmt->rowCount();
					
					if($matchingReviewCOunt < 1) {
						// create
						$stmt = $db->prepare("INSERT INTO full_reviews (name,userID,imageURL) VALUES (:name,:userID,:imageURL)");
						$pdo[':name']=$_REQUEST['beerName'];
						$pdo[':userID']=$_REQUEST['userID'];
						$pdo[':imageURL']=$_REQUEST['photoURL'];
						$stmt->execute($pdo);
						return intval($db->lastInsertId());
					} else {
						// update			
						try {
							//echo "UPDATING: UPDATE full_reviews SET imageURL=:photo_url WHERE userID=:user_id AND name=:beer_name";	
							
							$stmt = $db->prepare( "UPDATE full_reviews SET imageURL=:photo_url WHERE userID=:user_id AND name=:beer_name");
							
							$stmt->bindValue(':user_id', $_REQUEST['userID']);
							$stmt->bindValue(':beer_name', $_REQUEST['beerName']);
							$stmt->bindValue(':photo_url', $_REQUEST['photoURL']);
														
							$stmt->execute();
							
							echo "success";

						} catch (PDOException $e) {
						    print "Error!: " . $e->getMessage() . "<br/>";
						    die();
						}
						
						
					}
				break;
		}
	}
	if (empty($_REQUEST['do'])==FALSE && (empty($_REQUEST['userID'])==TRUE || $_REQUEST['userID'] == "")) {
		switch( clean($_REQUEST["do"]) )
		{				
	
				case "getBeersRecent":
					
				
					$stmt = $db->prepare("SELECT * FROM full_reviews WHERE userID <> 'undefined' AND  userID <> ''  ORDER BY auto_id DESC LIMIT :skip, :max");
					
					if(isset($_REQUEST['skip']))
					{
						$stmt->bindValue(':skip', (int) trim($_REQUEST['skip']), PDO::PARAM_INT);
					}else
					{
						$stmt->bindValue(':skip', 0, PDO::PARAM_INT);
					}
					
					if(isset($_REQUEST['max']))
					{
						$stmt->bindValue(':max', (int) trim($_REQUEST['max']), PDO::PARAM_INT);
					}else
					{
						$stmt->bindValue(':max', 10, PDO::PARAM_INT);
					}
					
					$stmt->execute();
					$rows = $stmt->fetchAll(PDO::FETCH_OBJ);
					echo json_encode($rows);
				break;
				case "searchBeers":
					$stmt = $db->prepare('SELECT * FROM full_reviews WHERE name LIKE ? LIMIT :skip, :max');
					
					if(isset($_REQUEST['skip']))
					{
						$stmt->bindValue(':skip', (int) trim($_REQUEST['skip']), PDO::PARAM_INT);
					}else
					{
						$stmt->bindValue(':skip', 0, PDO::PARAM_INT);
					}
					
					if(isset($_REQUEST['max']))
					{
						$stmt->bindValue(':max', (int) trim($_REQUEST['max']), PDO::PARAM_INT);
					}else
					{
						$stmt->bindValue(':max', 10, PDO::PARAM_INT);
					}
					
					$query->execute(array($_REQUEST['srch'].'%'));
					while ($results = $query->fetch())
					{
					    echo $results['column'];
					}
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



?>