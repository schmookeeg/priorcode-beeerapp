<?php

/*
	set functionality
*/

function createBeerEntry($db,$beerName,$breweryID,$styleID,$serving,$approved)
	{
		$beer['beer_name']=$beerName;
		$beer['brewery_id']=intval($breweryID);
		$beer['style_id']=intval($styleID);
		$beer['serving']=$serving;
		$beer['beer_date']=date('Y-m-d');
		$beer['beer_approved']=$approved;
		$stmt = $db->prepare("INSERT INTO beers (".implode(",",array_keys($beer)).") VALUES (:".implode(",:",array_keys($beer)).")");
		foreach($beer as $n=>$v) { $pdo[':'.$n]=$v; }
		$stmt->execute($pdo);
		return intval($db->lastInsertId());
	}

	
function createWheelEntry($db,$beer_id,$user_id,$bitter,$body,$linger,$aroma,$citrus,$hoppy,$floral,$malty,$toffee,$burnt,$sweet,$sour)
	{
		$wheelSpokes=array('bitter','body','linger','aroma','citrus','hoppy','floral','malty','toffee','burnt','sweet','sour');
		foreach($wheelSpokes as $n => $spoke) {
			$wheel[$spoke] = $$spoke;
		}
		$wheel['beer_id'] = $beer_id;
		$wheel['user_id'] = $user_id;
		$stmt = $db->prepare("INSERT INTO beer_wheels (".implode(',',array_keys($wheel)).") VALUES (:".implode(",:",array_keys($wheel)).")");
		foreach($wheel as $n=>$v) { $pdo[':'.$n]=$v; }
		$stmt->execute($pdo);
		return intval($db->lastInsertId());
	}
	
function createRatingEntry($db,$beer_id,$user_id,$ratingScore)
	{
		$rate['beer_id'] = intval($beer_id);
		$rate['user_id'] = intval($user_id);
		$rate['rating_score'] = intval($ratingScore);
		$stmt = $db->prepare("INSERT INTO beer_ratings (".implode(',',array_keys($rate)).") VALUES (:".implode(",:",array_keys($rate)).")");
		foreach($rate as $n=>$v) { $pdo[':'.$n]=$v; }
		$stmt->execute($pdo);
		return intval($db->lastInsertId());
	}
	
function createUserEntry($db,$uType,$uName,$uExtID)
	{
		$usr['user_type'] = $uType;
		$usr['user_display_name'] = $uName;
		$usr['user_external_id'] = $uExtID;
		$stmt = $db->prepare("INSERT INTO users (".implode(',',array_keys($usr)).") VALUES (:".implode(",:",array_keys($usr)).")");
		foreach($usr as $n => $v) { $pdo[':'.$n]=$v; }
		$stmt->execute($pdo);
		return intval($db->lastInsertId());
	}
	
function createBeerStatEntry($db,$tbl,$beer_id,$user_id,$statVal)
	{
		$stat['beer_id'] = $beer_id;
		$stat['user_id'] = $user_id;
		$stat['value'] = $statVal;
		$stmt = $db->prepare("INSERT INTO beer_".strtoupper($tbl)." (".implode(',',array_keys($stat)).") VALUES (:".implode(",:",array_keys($stat)).")");
		$pdo[':beer_id']=$beer_id;
		$pdo[':user_id']=$user_id;
		$pdo[':value']=$statVal;
		$stmt->execute($pdo);
		return intval($db->lastInsertId());
	}

function createPhotoEntry($db,$beer_id,$user_id,$photoURL)
	{
		$photo['beer_id'] = $beer_id;
		$photo['user_id'] = $user_id;
		$photo['photo_url'] = $photoURL;
		$photo['photo_date'] = date('Y-m-d');
		$stmt = $db->prepare("INSERT INTO beer_photos (".implode(',',array_keys($photo)).") VALUES (:".implode(",:",array_keys($photo)).")");
		$pdo[':user_id']=$beer_id;
		$pdo[':beer_id']=$beer_id;
		$pdo[':photo_url']=$photoURL;
		$pdo[':photo_date']=$photo['photo_date'];
		$stmt->execute($pdo);
		return intval($db->lastInsertId());
	}	

function createNoteEntry($db,$beer_id,$user_id,$noteText)
	{
		$note['beer_id'] = $beer_id;
		$note['user_id'] = $user_id;
		$note['review_note'] = $noteText;
		$note['review_note_date'] = date('Y-m-d');
		$stmt = $db->prepare("INSERT INTO beer_reviews (".implode(',',array_keys($note)).") VALUES (:".implode(",:",array_keys($note)).")");
		$pdo[':beer_id']=$beer_id;
		$pdo[':user_id']=$user_id;
		$pdo[':review_note']=$noteText;
		$pdo[':review_note_date']=$note['review_note_date'];
		$stmt->execute($pdo);
		return intval($db->lastInsertId());
	}

function createReviewEntry($db,$beer_id,$user_id,$reviewText)
	{
		$review['beer_id'] = $beer_id;
		$review['user_id'] = $user_id;
		$review['review_content'] = $reviewText;
		$review['review_content_date'] = date('Y-m-d');
		$stmt = $db->prepare("INSERT INTO beer_reviews (".implode(',',array_keys($review)).") VALUES (:".implode(",:",array_keys($review)).")");
		$pdo[':beer_id']=$beer_id;
		$pdo[':user_id']=$user_id;
		$pdo[':review_content']=$reviewText;
		$pdo[':review_content_date']=$note['review_content_date'];
		$stmt->execute($pdo);
		return intval($db->lastInsertId());
	}

function createStyleEntry($db,$styleName)
	{
		$stmt = $db->prepare("INSERT INTO beer_styles (style_name) VALUES (:style_name)");
		$pdo[':style_name']=$styleName;
		$stmt->execute($pdo);
		return intval($db->lastInsertId());
	}
	
function createBreweryEntry($db,$breweryName)
	{
		$stmt = $db->prepare("INSERT INTO breweries (brewery_name) VALUES (:brewery_name)");
		$pdo[':brewery_name']=$breweryName;
		$stmt->execute($pdo);
		return intval($db->lastInsertId());
	}
?>