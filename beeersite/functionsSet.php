<?php

require_once('functionsSet_create.php');
require_once('functionsSet_update.php');
	
/*
function dbBeerEntry($db,$beerName,$breweryID,$styleID,$serving,$approved)
	{
		$stmt = $db->prepare("SELECT * FROM beers WHERE beer_id=:beer_id");
		$stmt->bindValue(':beer_id', $beer_id, PDO::PARAM_INT);
		$stmt->execute();
		if($stmt->rowCount() < 1) {
			$result = createBeerEntry($db,$beerName,$breweryID,$styleID,$serving,$approved);
		} else {
			$result = updateBeerEntry($db,$beerName,$breweryID,$styleID,$serving,$approved);
		}
		return $result;
	}
*/

function dbBeerEntry($db,$beerName,$breweryName,$styleName,$serving,$approved)
	{
		$stmt = $db->prepare("SELECT * FROM beers WHERE beer_id=:beer_id");
		$stmt->bindValue(':beer_id', $beer_id, PDO::PARAM_INT);
		$stmt->execute();
		if($stmt->rowCount() < 1) {
			$result = createBeerEntry($db,$beerName,dbBreweryEntry($db, $breweryName),dbStyleEntry($db, $styleName),$serving,$approved);
		} else {
			$result = updateBeerEntry($db,$beerName,dbBreweryEntry($db, $breweryName),dbStyleEntry($db, $styleName),$serving,$approved);
		}
		return $result;
	}
	
	
	
function dbWheelEntry($db,$beer_id,$user_id,$bitter,$body,$linger,$aroma,$citrus,$hoppy,$floral,$malty,$toffee,$burnt,$sweet,$sour)
	{
		$stmt = $db->prepare("SELECT * FROM beer_wheels WHERE beer_id=:beer_id AND user_id=:user_id");
		$stmt->bindValue(':beer_id', $beer_id, PDO::PARAM_INT);
		$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
		$stmt->execute();
		if($stmt->rowCount() < 1) {
			$result = createWheelEntry($db,$beer_id,$user_id,$bitter,$body,$linger,$aroma,$citrus,$hoppy,$floral,$malty,$toffee,$burnt,$sweet,$sour);
		} else {
			$result = updateWheelEntry($db,$beer_id,$user_id,$bitter,$body,$linger,$aroma,$citrus,$hoppy,$floral,$malty,$toffee,$burnt,$sweet,$sour);
		}
		return $result;
	}
	
function dbRatingEntry($db,$beer_id,$user_id,$ratingScore)
	{
		$stmt = $db->prepare("SELECT * FROM beer_ratings WHERE beer_id=:beer_id AND user_id=:user_id");
		$stmt->bindValue(':beer_id', $beer_id, PDO::PARAM_INT);
		$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
		$stmt->execute();
		if($stmt->rowCount() < 1) {
			$result = createRatingEntry($db,$beer_id,$user_id,$ratingScore);
		} else {
			$result = updateRatingEntry($db,$beer_id,$user_id,$ratingScore);
		}
		return $result;
	}
	
function dbUserEntry($db,$uType,$uName,$uExtID)
	{
		$stmt = $db->prepare("SELECT * FROM users WHERE user_external_id=:user_external_id");
		$stmt->bindValue(':user_external_id', $uExtID, PDO::PARAM_STR);
		$stmt->execute();
		if($stmt->rowCount() < 1) {
			$result = createUserEntry($db,$uType,$uName,$uExtID);
		} else {
			$result = updateUserEntry($db,$uType,$uName,$uExtID);
		}
		return $result;
	}
	
function dbBeerStatEntry($db,$tbl,$beer_id,$user_id,$statVal)
	{
		$stmt = $db->prepare("SELECT * FROM beer_".strtoupper($tbl)." WHERE beer_id=:beer_id AND user_id=:user_id");
		$stmt->bindValue(':beer_id', $beer_id, PDO::PARAM_INT);
		$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
		$stmt->execute();
		if($stmt->rowCount() < 1) {
			$result = createBeerStatEntry($db,$tbl,$beer_id,$user_id,$statVal);
		} else {
			$result = updateBeerStatEntry($db,$tbl,$beer_id,$user_id,$statVal);
		}
		return $result;
	}

function dbPhotoEntry($db,$beer_id,$user_id,$photoURL)
	{
		$stmt = $db->prepare("SELECT * FROM beer_photos WHERE beer_id=:beer_id AND user_id=:user_id");
		$stmt->bindValue(':beer_id', $beer_id, PDO::PARAM_INT);
		$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
		$stmt->execute();
		if($stmt->rowCount() < 1) {
			echo "CREATING NEW ENTRY... ";
			$result = createPhotoEntry($db,$beer_id,$user_id,$photoURL);
		} else {
			echo "UPDATING EXISTING ENTRY... ";
			$result = updatePhotoEntry($db,$beer_id,$user_id,$photoURL);
		}
		return $result;
	}	

function dbNoteEntry($db,$beer_id,$user_id,$noteText)
	{
		$stmt = $db->prepare("SELECT * FROM beer_reviews WHERE beer_id=:beer_id AND user_id=:user_id");
		$stmt->bindValue(':beer_id', $beer_id, PDO::PARAM_INT);
		$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
		$stmt->execute();
		if($stmt->rowCount() < 1) {
			$result = createNoteEntry($db,$beer_id,$user_id,$noteText,$noteDate);
		} else {
			$result = updateNoteEntry($db,$beer_id,$user_id,$noteText,$noteDate);
		}
		return $result;
	}

function dbReviewEntry($db,$beer_id,$user_id,$reviewText)
	{
		$stmt = $db->prepare("SELECT * FROM beer_reviews WHERE beer_id=:beer_id AND user_id=:user_id");
		$stmt->bindValue(':beer_id', $beer_id, PDO::PARAM_INT);
		$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
		$stmt->execute();
		if($stmt->rowCount() < 1) {
			$result = createReviewEntry($db,$beer_id,$user_id,$reviewText,$reviewDate);
		} else {
			$result = updateReviewEntry($db,$beer_id,$user_id,$reviewText,$reviewDate);
		}
		return $result;
	}

function dbStyleEntry($db,$styleName,$styleID='')
	{
		$stmt = $db->prepare("SELECT * FROM beer_styles WHERE style_name=:style_name LIMIT 1");
		$stmt->bindValue(':style_name', $styleName, PDO::PARAM_STR);
		$stmt->execute();
		if($stmt->rowCount() < 1) {
			$result = createStyleEntry($db,$styleName);
		} else {
			$result = getTheStyleEntryId($db,$styleName);//updateStyleEntry($db,$styleName,$styleID);
		}
		return $result;
	}
	
function dbBreweryEntry($db,$breweryName)
	{
		$stmt = $db->prepare("SELECT * FROM breweries WHERE brewery_name=:brewery_name LIMIT 1");
		$stmt->bindValue(':brewery_name', $breweryName, PDO::PARAM_STR);
		$stmt->execute();
		if($stmt->rowCount() < 1) {
			$result = createBreweryEntry($db,$breweryName);
		} else {
			$result = updateBreweryEntry($db,$breweryName);
		}
		return $result;
	}
?>