<?php

/*
	set functionality
*/

function updateBeerEntry($db,$beerName,$breweryID,$styleID,$serving,$approved)
	{
		$beer['beer_name']=$beerName;
		$beer['brewery_id']=intval($breweryID);
		$beer['style_id']=intval($styleID);
		$beer['serving']=$serving;
		$beer['beer_date']=date('Y-m-d');
		$beer['beer_approved']=$approved;
		$stmt = $db->prepare("UPDATE beers SET ".implode("`,`",array_keys($beer))."`) VALUES (:".implode(",:",array_keys($beer)).")");
		foreach($beer as $n=>$v) {
			$stmt->bindValue(':'.$n, $v);
		}
		$stmt->execute();
		return intval($db->lastInsertId());
	}

	
function updateWheelEntry($db,$beer_id,$user_id,$bitter,$body,$linger,$aroma,$citrus,$hoppy,$floral,$malty,$toffee,$burnt,$sweet,$sour)
	{
		$wheelSpokes=array('bitter','body','linger','aroma','citrus','hoppy','floral','malty','toffee','burnt','sweet','sour');
		foreach($wheelSpokes as $spoke) {
			$wheel[$spoke] = $$spoke;
			$updateSQL[]=$spoke.'=:'.$spoke;
		}
		$stmt = $db->prepare("UPDATE beer_wheels SET ".implode(',',$updateSQL)." WHERE beer_id=:beer_id AND user_id=:user_id");
		foreach($wheel as $n=>$v) {
			$stmt->bindValue(':'.$n, $v);
		}
		$stmt->bindValue(':beer_id', $beer_id);
		$stmt->bindValue(':user_id', $user_id);
		$stmt->execute();
		return intval($db->lastInsertId());
	}

function updateRatingEntry($db,$beer_id,$user_id,$ratingScore)
	{
		$rate['beer_id'] = $beer_id;
		$rate['user_id'] = $user_id;
		$rate['rating_score'] = $ratingScore;
		$stmt = $db->prepare("UPDATE beer_ratings SET rating_score=:rating_score WHERE beer_id=:beer_id AND user_id=:user_id");
		foreach($rate as $n=>$v) {
			$stmt->bindValue(':'.$n, $v);
		}
		$stmt->execute();
		return intval($db->lastInsertId());
	}
	
function updateUserEntry($db,$uType,$uName,$uExtID)
	{
		$usr['user_type'] = $uType;
		$usr['user_display_name'] = $uName;
		$usr['user_external_id'] = $uExtID;
		foreach($usr as $n=>$v) {
			$updateSQL[]=$n.'=:'.$n;
		}
		$stmt = $db->prepare("UPDATE users SET ".implode(',',$updateSQL)." WHERE user_id=:user_id");
		foreach($usr as $n=>$v) {
			$stmt->bindValue(':'.$n, $v);
		}
		$stmt->bindValue(':user_id', $user_id);
		$stmt->execute();
		return intval($db->lastInsertId());
	}	
	
function updateBeerStatEntry($db,$tbl,$beer_id,$user_id,$statVal)
	{
		$stat['beer_id'] = $beer_id;
		$stat['user_id'] = $user_id;
		$stat['value'] = $statVal;
		$stmt = $db->prepare("UPDATE beer_".strtoupper($tbl)." SET value=:value WHERE beer_id=:beer_id AND user_id=:user_id");
		$stmt->bindValue(':beer_id', $beer_id, PDO::PARAM_INT);
		$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
		$stmt->bindValue(':value', $statVal, PDO::PARAM_STR);
		$stmt->execute();
		return intval($db->lastInsertId());
	}

function updatePhotoEntry($db,$beer_id,$user_id,$photoURL)
	{
		$photo['beer_id'] = $beer_id;
		$photo['user_id'] = $user_id;
		$photo['photo_url'] = $photoURL;
		$photo['photo_date'] = date('Y-m-d');
		$stmt = $db->prepare("UPDATE beer_photos SET photo_url=:photo_url WHERE beer_id=:beer_id AND user_id=:user_id");
		$stmt->bindValue(':user_id', $beer_id, PDO::PARAM_INT);
		$stmt->bindValue(':beer_id', $beer_id, PDO::PARAM_INT);
		$stmt->bindValue(':photo_url', $photoURL, PDO::PARAM_STR);
		$stmt->bindValue(':photo_date', $photo['photo_date'], PDO::PARAM_STR);
		$stmt->execute();
		return intval($db->lastInsertId());
	}	

function updateNoteEntry($db,$beer_id,$user_id,$noteText)
	{
		$note['beer_id'] = $beer_id;
		$note['user_id'] = $user_id;
		$note['review_note'] = $noteText;
		$note['review_note_date'] = date('Y-m-d');
		$stmt = $db->prepare("UPDATE beer_reviews SET review_note=:review_note WHERE beer_id=:beer_id AND user_id=:user_id");
		$stmt->bindValue(':beer_id', $beer_id, PDO::PARAM_INT);
		$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
		$stmt->bindValue(':review_note', $noteText, PDO::PARAM_STR);
		//$stmt->bindValue(':review_note_date', $note['review_note_date'], PDO::PARAM_STR);
		$stmt->execute();
		return intval($db->lastInsertId());
	}

function updateReviewEntry($db,$beer_id,$user_id,$reviewText)
	{
		$review['beer_id'] = $beer_id;
		$review['user_id'] = $user_id;
		$review['review_content'] = $reviewText;
		$review['review_content_date']=date('Y-m-d');
		$stmt = $db->prepare("UPDATE beer_reviews SET review_content=:review_content,review_content_date=:review_content_date WHERE beer_id=:beer_id AND user_id=:user_id");
		$stmt->bindValue(':beer_id', $beer_id, PDO::PARAM_INT);
		$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
		$stmt->bindValue(':review_content', $reviewText, PDO::PARAM_STR);
		$stmt->bindValue(':review_content_date', $review['review_content_date'], PDO::PARAM_STR);
		$stmt->execute();
		return intval($db->lastInsertId());
	}

function getTheStyleEntryId($db,$styleName)
	{
		$stmt = $db->prepare("SELECT * FROM beer_styles WHERE style_name=:style_name LIMIT 1");
		$stmt->bindValue(':style_name', $styleName, PDO::PARAM_STR);
		$stmt->execute();
		$rows = $stmt->fetch(PDO::FETCH_ASSOC);
		settype($rows->style_id,'int');
		return $rows->style_id;
	}
	
function updateStyleEntry($db,$styleName,$styleID)
	{
		$style['style_name'] = $styleName;
		$stmt = $db->prepare("UPDATE beer_styles SET style_name=:style_name WHERE style_id=:style_id");
		$stmt->bindValue(':style_name', $styleName, PDO::PARAM_STR);
		$stmt->bindValue(':style_id', $styleName, PDO::PARAM_STR);
		$stmt->execute();
		return intval($db->lastInsertId());
	}

	
function updateBreweryEntry($db,$breweryName)
	{
		$stmt = $db->prepare("UPDATE breweries SET brewery_name=:brewery_name");
		$stmt->bindValue(':brewery_name', $breweryName, PDO::PARAM_STR);
		$stmt->execute();
		return intval($db->lastInsertId());
	}
?>