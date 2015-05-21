<?php
define('autoCompleteLimit',1);
/*
	Auto-Complete functionality
*/

function autocompleteBeer($db)
	{
		$stmt = $db->prepare("SELECT beer_name FROM beers WHERE beer_name LIKE ':name%' AND approved = 1 ORDER BY beer_name ASC LIMIT ".autoCompleteLimit);
		$stmt->bindValue(':name', $_POST['beer_name'], PDO::PARAM_STR);
		$stmt->execute();
		$rows = $stmt->fetch(PDO::FETCH_OBJ);
		return json_encode($rows);
	}
	
function autocompleteBrewery($db)
	{
		$stmt = $db->prepare("SELECT brewery_name FROM breweries WHERE brewery_name LIKE ':name%' AND approved = 1 ORDER BY brewery_name ASC LIMIT ".autoCompleteLimit);
		$stmt->bindValue(':name', $_POST['brewery_name'], PDO::PARAM_STR);
		$stmt->execute();
		$rows = $stmt->fetch(PDO::FETCH_OBJ);
		return json_encode($rows);
	}
	
function autocompleteStyles($db)
	{
		$stmt = $db->prepare("SELECT style_name FROM beer_styles WHERE style_name LIKE ':name%' ORDER BY style_name ASC LIMIT ".autoCompleteLimit);
		$stmt->bindValue(':name', $_POST['style_name'], PDO::PARAM_STR);
		$stmt->execute();
		$rows = $stmt->fetch(PDO::FETCH_OBJ);
		return json_encode($rows);
	}

?>