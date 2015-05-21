<?php

/*
	get functionality
*/
	
function getApprovedPics($db,$pageNumber)
	{
		if(is_numeric($pageNumber)==TRUE)
		{
			$stmt = $db->prepare("SELECT * FROM beer_photos WHERE photo_approved='1' ORDER BY photo_id DESC LIMIT ".($pageNumber * 18).", 18");
			$stmt->execute();
			$rows = $stmt->fetchAll(PDO::FETCH_OBJ);
			$rows->totalRecords = $stmt->rowCount();
			return json_encode($rows);
		}
	}

function getBeerPic($db,$picID)
	{
		if(is_numeric($pageNumber)==TRUE)
		{
			$stmt = $db->prepare("SELECT imageID AS photo_url FROM beer_photos WHERE photo_id=:photo_id AND photo_approved=1 LIMIT 1");
			$stmt->bindValue(':photo_id', $picID, PDO::PARAM_INT);
			$stmt->execute();
			$rows = $stmt->fetch(PDO::FETCH_OBJ);			
			settype($rows->imageID,'int');
			return $rows->imageID;
		}
	}
	
function getPopularValue($db,$tbl,$beer_id)
	{
		if(isset($beer_id)==TRUE && is_int($beer_id)==TRUE)
		{
			$stmt = $db->prepare("SELECT value,COUNT(value) AS popVal FROM beer_".strtoupper($tbl)." WHERE beer_id=:beer_id GROUP BY value ORDER BY popVal DESC LIMIT 1");
			$stmt->bindValue(':beer_id', $beer_id, PDO::PARAM_INT);
			$stmt->execute();
			$rows = $stmt->fetch(PDO::FETCH_OBJ);
			settype($rows->value,'float');
			return $rows->value;
		}
	}
	
function getRatingAverage($db,$beer_id)
	{
		if(isset($beer_id)==TRUE && is_int($beer_id)==TRUE)
		{
			$stmt = $db->prepare("SELECT AVG(rating_score) AS rating_score FROM beer_ratings WHERE beer_id=:beer_id GROUP BY beer_id");
			$stmt->bindValue(':beer_id', $beer_id, PDO::PARAM_INT);
			$stmt->execute();
			$rows = $stmt->fetch(PDO::FETCH_OBJ);
			settype($rows->rating_score,'float');
			return $rows->rating_score;
		}
	}
	
function getUserID($db,$extUID) {
		if(isset($extUID)==TRUE && is_numeric($extUID)==TRUE)
		{
			$stmt = $db->prepare("SELECT user_id FROM users WHERE user_external_id=:user_id LIMIT 1");
			$stmt->bindValue(':user_id', $extUID, PDO::PARAM_INT);
			$stmt->execute();
			$rows = $stmt->fetch(PDO::FETCH_OBJ);
				settype($rows->user_id,'int');
			return $rows->user_id;
		}
	}
	
function getUserData($db, $uid) {
		if(isset($uid)==TRUE && is_numeric($uid)==TRUE)
		{
			$stmt = $db->prepare("SELECT * FROM users WHERE user_id=:user_id LIMIT 1");
			$stmt->bindValue(':user_id', $uid, PDO::PARAM_INT);
			$stmt->execute();
			$rows = $stmt->fetch(PDO::FETCH_OBJ);
			settype($rows->user_id,'int');
			settype($rows->user_approved,'int');
			return $rows;
		}
	}
	
function getBeerPhoto($db,$beer_id)
	{
		if(isset($beer_id)==TRUE && is_int($beer_id)==TRUE)
		{
			$stmt = $db->prepare("SELECT photo_url FROM beer_photos WHERE beer_id=:beer_id AND photo_approved=1 LIMIT 1");
			$stmt->bindValue(':beer_id', $beer_id, PDO::PARAM_INT);
			$stmt->execute();
			$rows = $stmt->fetch(PDO::FETCH_OBJ);
			settype($rows->photo_url,'string');
			return $rows->photo_url;
		}
	}

function getBeerByID($db,$beer_id)
	{
		//echo $beer_id;
		if(empty($beer_id)==TRUE || is_int($beer_id)==FALSE)
		{
			$rows['error']='beer id number required.';
		}
			//echo $beer_id;
			//$stmt = $db->prepare('SELECT * FROM beers b LEFT JOIN breweries bw ON b.brewery_id=bw.brewery_id LEFT JOIN beer_styles bs ON b.style_id=bs.style_id WHERE b.beer_id = :beer_id AND b.beer_approved=1 LIMIT 1');
			$stmt = $db->prepare('SELECT notes, style, abv, ibu, og, tg, serving,bitter,linger,aroma,body,citrus,hoppy,floral,malty,toffee,burnt,sweet,sour, imageURL, imageURL photoURL, id, rating, "1" num, name, brewery, imageURL from full_reviews where auto_id = :beer_id');	
			//$stmt = $db->prepare('SELECT * from full_reviews');	
			$stmt->bindValue(':beer_id', $beer_id, PDO::PARAM_INT);
			$stmt->execute();
			$rows = $stmt->fetch(PDO::FETCH_OBJ);
			//$rows = getAdditionalBeerInfo($db,$rows);
			//$rows = beerfilterOut($rows);
			$json->beerArray = $rows;
			$json->userInfo = $userData;
			
			echo json_encode($json);
//		return $rows;		
	}
	
		
function getBeerByNameSearch($db,$beerName)
	{
		if(empty($beerName)==TRUE || is_string($beerName)==FALSE) 
		{
			echo "BEER NAME EMPTY OR NOT A STRING.";
			$rows->error='beer name required.';
		}
		elseif(empty($beerName)==FALSE && is_string($beerName)==TRUE)
		{
			//echo "...preparing...";
			//$stmt = $db->prepare('SELECT * FROM beers b LEFT JOIN breweries bw ON b.brewery_id = bw.brewery_id LEFT JOIN beer_styles bs ON b.style_id = bs.style_id LEFT JOIN beer_photos bp ON b.beer_id = bp.beer_id WHERE b.beer_name = :beerName AND b.beer_approved=1 LIMIT 1');
			//$stmt->bindValue(':beerName', $beerName, PDO::PARAM_STR);
			//$stmt->execute();
			
			//$rows = $stmt->fetch(PDO::FETCH_OBJ);
			//var_dump($rows);
			
			// $stmt = $db->prepare('SELECT * FROM beers b LEFT JOIN breweries bw ON b.brewery_id = bw.brewery_id LEFT JOIN beer_styles bs ON b.style_id = bs.style_id LEFT JOIN beer_photos bp ON b.beer_id = bp.beer_id WHERE b.beer_name = :beerName AND b.beer_approved=1 LIMIT 1');
			
			//$stmt = $db->prepare('SELECT notes, style, abv, ibu, og, tg, serving,bitter,linger,aroma,body,citrus,hoppy,floral,malty,toffee,burnt,sweet,sour, imageURL, imageURL photoURL, max(auto_id) id, avg(rating) rating, count(name) num, name, brewery, imageURL from full_reviews where name LIKE :beerName or brewery LIKE :brewery or style LIKE :style group by name, brewery order by id desc');	
			$stmt = $db->prepare('SELECT notes, style, abv, ibu, og, tg, serving, avg(bitter) bitter , avg(linger) linger, avg(aroma) aroma, avg(body) body, avg(citrus) citrus ,avg(hoppy) hoppy, avg(floral) floral, avg(malty) malty, avg(toffee) toffee, avg(burnt) burnt,avg(sweet) sweet, avg(sour) sour, imageURL, imageURL photoURL, max(auto_id) id, avg(rating) rating, count(name) num, name, brewery, imageURL from full_reviews where name LIKE :beerName or brewery LIKE :brewery or style LIKE :style group by name, brewery order by id desc');	
			$stmt->bindValue(':beerName', '%' . $beerName . '%', PDO::PARAM_STR);
			$stmt->bindValue(':brewery', '%' . $beerName . '%', PDO::PARAM_STR);
			$stmt->bindValue(':style', '%' . $beerName . '%', PDO::PARAM_STR);

			$stmt->execute();
			
			$rows = $stmt->fetchAll(PDO::FETCH_OBJ);
			//var_dump($rows_tmp);
			//echo ">>>>>".$rows_tmp[0];
			//$rows->beer_id = $rows_tmp[0];
			//var_dump($rows);
			//$rows = getAdditionalBeerInfo($db,$rows);
			//$rows = beerfilterOut($rows);
					
		}
		return $rows;
	}
	
function getBeerByNameSearchAgg($db,$beerName)
	{
		if(empty($beerName)==TRUE || is_string($beerName)==FALSE) 
		{
			echo "BEER NAME EMPTY OR NOT A STRING.";
			$rows->error='beer name required.';
		}
		elseif(empty($beerName)==FALSE && is_string($beerName)==TRUE)
		{
			//echo "...preparing...";
			//$stmt = $db->prepare('SELECT * FROM beers b LEFT JOIN breweries bw ON b.brewery_id = bw.brewery_id LEFT JOIN beer_styles bs ON b.style_id = bs.style_id LEFT JOIN beer_photos bp ON b.beer_id = bp.beer_id WHERE b.beer_name = :beerName AND b.beer_approved=1 LIMIT 1');
			//$stmt->bindValue(':beerName', $beerName, PDO::PARAM_STR);
			//$stmt->execute();
			
			//$rows = $stmt->fetch(PDO::FETCH_OBJ);
			//var_dump($rows);
			
			// $stmt = $db->prepare('SELECT * FROM beers b LEFT JOIN breweries bw ON b.brewery_id = bw.brewery_id LEFT JOIN beer_styles bs ON b.style_id = bs.style_id LEFT JOIN beer_photos bp ON b.beer_id = bp.beer_id WHERE b.beer_name = :beerName AND b.beer_approved=1 LIMIT 1');
			
			//$stmt = $db->prepare('SELECT notes, style, abv, ibu, og, tg, serving,bitter,linger,aroma,body,citrus,hoppy,floral,malty,toffee,burnt,sweet,sour, imageURL, imageURL photoURL, max(auto_id) id, avg(rating) rating, count(name) num, name, brewery, imageURL from full_reviews where name LIKE :beerName or brewery LIKE :brewery or style LIKE :style group by name, brewery order by id desc');	
			$stmt = $db->prepare('SELECT notes, style, abv, ibu, og, tg, serving, avg(bitter) bitter , avg(linger) linger, avg(aroma) aroma, avg(body) body, avg(citrus) citrus ,avg(hoppy) hoppy, avg(floral) floral, avg(malty) malty, avg(toffee) toffee, avg(burnt) burnt,avg(sweet) sweet, avg(sour) sour, imageURL, imageURL photoURL, max(auto_id) id, avg(rating) rating, count(name) num, name, brewery, imageURL from full_reviews where name = :beerName group by name, brewery order by id desc');	
			$stmt->bindValue(':beerName', $beerName, PDO::PARAM_STR);
			//$stmt->bindValue(':brewery', '%' . $beerName . '%', PDO::PARAM_STR);
			//$stmt->bindValue(':style', '%' . $beerName . '%', PDO::PARAM_STR);

			$stmt->execute();
			
			$rows = $stmt->fetchAll(PDO::FETCH_OBJ);
			//var_dump($rows_tmp);
			//echo ">>>>>".$rows_tmp[0];
			//$rows->beer_id = $rows_tmp[0];
			//var_dump($rows);
			//$rows = getAdditionalBeerInfo($db,$rows);
			//$rows = beerfilterOut($rows);
					
		}
		return $rows;
	}
	
	
function getAllBeersByUserId($db,$userId)
	{
		//echo "yes";
			$stmt = $db->prepare('SELECT * from full_reviews where userID = :userId order by id desc');	
			$stmt->bindValue(':userId', $userId, PDO::PARAM_STR);
			//$stmt->bindValue(':brewery', '%' . $beerName . '%', PDO::PARAM_STR);
			//$stmt->bindValue(':style', '%' . $beerName . '%', PDO::PARAM_STR);

			$stmt->execute();
			
			$rows = $stmt->fetchAll(PDO::FETCH_OBJ);
			//var_dump($rows_tmp);
			//echo ">>>>>".$rows_tmp[0];
			//$rows->beer_id = $rows_tmp[0];
			//var_dump($rows);
			//$rows = getAdditionalBeerInfo($db,$rows);
			//$rows = beerfilterOut($rows);
					

		return $rows;
	}


function getBeerByNameSearchAggSingle($db,$beerName)
	{
		if(empty($beerName)==TRUE || is_string($beerName)==FALSE) 
		{
			echo "BEER NAME EMPTY OR NOT A STRING.";
			$rows->error='beer name required.';
		}
		elseif(empty($beerName)==FALSE && is_string($beerName)==TRUE)
		{
			//echo "...preparing...";
			//$stmt = $db->prepare('SELECT * FROM beers b LEFT JOIN breweries bw ON b.brewery_id = bw.brewery_id LEFT JOIN beer_styles bs ON b.style_id = bs.style_id LEFT JOIN beer_photos bp ON b.beer_id = bp.beer_id WHERE b.beer_name = :beerName AND b.beer_approved=1 LIMIT 1');
			//$stmt->bindValue(':beerName', $beerName, PDO::PARAM_STR);
			//$stmt->execute();
			
			//$rows = $stmt->fetch(PDO::FETCH_OBJ);
			//var_dump($rows);
			
			// $stmt = $db->prepare('SELECT * FROM beers b LEFT JOIN breweries bw ON b.brewery_id = bw.brewery_id LEFT JOIN beer_styles bs ON b.style_id = bs.style_id LEFT JOIN beer_photos bp ON b.beer_id = bp.beer_id WHERE b.beer_name = :beerName AND b.beer_approved=1 LIMIT 1');
			
			//$stmt = $db->prepare('SELECT notes, style, abv, ibu, og, tg, serving,bitter,linger,aroma,body,citrus,hoppy,floral,malty,toffee,burnt,sweet,sour, imageURL, imageURL photoURL, max(auto_id) id, avg(rating) rating, count(name) num, name, brewery, imageURL from full_reviews where name LIKE :beerName or brewery LIKE :brewery or style LIKE :style group by name, brewery order by id desc');	
			$stmt = $db->prepare('SELECT notes, style, abv, ibu, og, tg, serving, avg(bitter) bitter , avg(linger) linger, avg(aroma) aroma, avg(body) body, avg(citrus) citrus ,avg(hoppy) hoppy, avg(floral) floral, avg(malty) malty, avg(toffee) toffee, avg(burnt) burnt,avg(sweet) sweet, avg(sour) sour, imageURL, imageURL photoURL, max(auto_id) id, avg(rating) rating, count(name) num, name, brewery, imageURL from full_reviews where name = :beerName group by name, brewery order by id desc');	
			$stmt->bindValue(':beerName', $beerName, PDO::PARAM_STR);
			//$stmt->bindValue(':brewery', '%' . $beerName . '%', PDO::PARAM_STR);
			//$stmt->bindValue(':style', '%' . $beerName . '%', PDO::PARAM_STR);

			$stmt->execute();
			
			$rows = $stmt->fetch(PDO::FETCH_OBJ);
			//var_dump($rows_tmp);
			//echo ">>>>>".$rows_tmp[0];
			//$rows->beer_id = $rows_tmp[0];
			//var_dump($rows);
			//$rows = getAdditionalBeerInfo($db,$rows);
			//$rows = beerfilterOut($rows);
					
		}
		return $rows;
	}
	
function getAllBeerByNameSearch($db,$beerName)
	{
		if(empty($beerName)==TRUE || is_string($beerName)==FALSE) 
		{
			echo "BEER NAME EMPTY OR NOT A STRING.";
			$rows->error='beer name required.';
		}
		elseif(empty($beerName)==FALSE && is_string($beerName)==TRUE)
		{
			//echo "...preparing...";
			//$stmt = $db->prepare('SELECT * FROM beers b LEFT JOIN breweries bw ON b.brewery_id = bw.brewery_id LEFT JOIN beer_styles bs ON b.style_id = bs.style_id LEFT JOIN beer_photos bp ON b.beer_id = bp.beer_id WHERE b.beer_name = :beerName AND b.beer_approved=1 LIMIT 1');
			//$stmt->bindValue(':beerName', $beerName, PDO::PARAM_STR);
			//$stmt->execute();
			
			//$rows = $stmt->fetch(PDO::FETCH_OBJ);
			//var_dump($rows);
			
			// $stmt = $db->prepare('SELECT * FROM beers b LEFT JOIN breweries bw ON b.brewery_id = bw.brewery_id LEFT JOIN beer_styles bs ON b.style_id = bs.style_id LEFT JOIN beer_photos bp ON b.beer_id = bp.beer_id WHERE b.beer_name = :beerName AND b.beer_approved=1 LIMIT 1');
			
			$stmt = $db->prepare('SELECT notes, style, abv, ibu, og, tg, serving,bitter,linger,aroma,body,citrus,hoppy,floral,malty,toffee,burnt,sweet,sour, imageURL, imageURL photoURL, auto_id id, rating, name, name, brewery, imageURL from full_reviews where name = :beerName order by id desc');	
			//$stmt->bindValue(':beerName', '%' . $beerName . '%', PDO::PARAM_STR);
			$stmt->bindValue(':beerName', $beerName, PDO::PARAM_STR);
			//$stmt->bindValue(':brewery', '%' . $beerName . '%', PDO::PARAM_STR);
			//$stmt->bindValue(':style', '%' . $beerName . '%', PDO::PARAM_STR);


			$stmt->execute();
			
			$rows = $stmt->fetchAll(PDO::FETCH_OBJ);
			//var_dump($rows_tmp);
			//echo ">>>>>".$rows_tmp[0];
			//$rows->beer_id = $rows_tmp[0];
			//var_dump($rows);
			//$rows = getAdditionalBeerInfo($db,$rows);
			//$rows = beerfilterOut($rows);
					
		}
		return $rows;
	}	
	
function getBeerByName($db,$beerName)
	{
		if(empty($beerName)==TRUE || is_string($beerName)==FALSE) 
		{
			echo "BEER NAME EMPTY OR NOT A STRING.";
			$rows->error='beer name required.';
		}
		elseif(empty($beerName)==FALSE && is_string($beerName)==TRUE)
		{
			//echo "...preparing...";
			//$stmt = $db->prepare('SELECT * FROM beers b LEFT JOIN breweries bw ON b.brewery_id = bw.brewery_id LEFT JOIN beer_styles bs ON b.style_id = bs.style_id LEFT JOIN beer_photos bp ON b.beer_id = bp.beer_id WHERE b.beer_name = :beerName AND b.beer_approved=1 LIMIT 1');
			//$stmt->bindValue(':beerName', $beerName, PDO::PARAM_STR);
			//$stmt->execute();
			
			//$rows = $stmt->fetch(PDO::FETCH_OBJ);
			//var_dump($rows);
			
			// $stmt = $db->prepare('SELECT * FROM beers b LEFT JOIN breweries bw ON b.brewery_id = bw.brewery_id LEFT JOIN beer_styles bs ON b.style_id = bs.style_id LEFT JOIN beer_photos bp ON b.beer_id = bp.beer_id WHERE (b.beer_name = :beerName or b.brewery = :brewery_name or b.style = :style) AND b.beer_approved=1 LIMIT 1');
			
			$stmt = $db->prepare('SELECT notes, style, abv, ibu, og, tg, serving,bitter,linger,aroma,body,citrus,hoppy,floral,malty,toffee,burnt,sweet,sour, imageURL, imageURL photoURL, max(auto_id) id, avg(rating) rating, count(name) num, name, brewery, imageURL from full_reviews where name = :beerName group by name, brewery order by id desc');	
			$stmt->bindValue(':beerName', $beerName, PDO::PARAM_STR);
			$stmt->execute();
			
			$rows = $stmt->fetchAll(PDO::FETCH_OBJ);
			//var_dump($rows_tmp);
			//echo ">>>>>".$rows_tmp[0];
			//$rows->beer_id = $rows_tmp[0];
			//var_dump($rows);
			//$rows = getAdditionalBeerInfo($db,$rows);
			//$rows = beerfilterOut($rows);
					
		}
		return $rows[0];
	}
		
function getBreweryByName($db,$breweryName)
	{
		if(isset($breweryName)==TRUE && is_string($breweryName)==TRUE)
		{
			$stmt = $db->prepare("SELECT * FROM breweries 
				LEFT JOIN beer_photos bp ON b.beer = bp.beer_id
				WHERE brewery_name=:breweryName AND brewery_approved=1 LIMIT 1");
			$stmt->bindValue(':breweryName', $breweryName, PDO::PARAM_STR);
			$stmt->execute();
			$rows = $stmt->fetch(PDO::FETCH_ASSOC);
			settype($rows->brewery_id,'int');
			return $rows;
		}
		else
		{
			$rows['error']='brewery name required.';
		}
	}
		
function getAllBeers($db)
	{
		$stmt = $db->prepare('SELECT *,
			(SELECT AVG(rating_score) FROM beer_ratings br WHERE br.beer_id=b.beer_id) AS rating 
			FROM beers b
			LEFT JOIN breweries bw ON b.brewery_id = bw.brewery_id
			WHERE b.beer_approved=1
			');
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_OBJ);
		foreach($rows as $r)
			{
				$r = beerfilterOut($r);
				$rows = objMerge($rows,$r);				
			}
		return $rows;
	}
	
function getRecentBeers($db)
	{
		/*$stmt = $db->prepare('SELECT *,
			(SELECT AVG(rating_score) FROM beer_ratings br WHERE br.beer_id=b.beer_id) AS rating 
			FROM beers b
			LEFT JOIN breweries bw ON b.brewery_id = bw.brewery_id
			LEFT JOIN beer_photos bp ON b.beer_id = bp.beer_id
			WHERE b.beer_approved=1
			ORDER BY beer_date DESC');*/
		$stmt = $db->prepare('SELECT imageURL photoURL, max(auto_id) id, avg(rating) rating, count(name) num, name, brewery, imageURL from full_reviews group by name, brewery order by id desc');
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_OBJ);
		//foreach($rows as $r)
		//	{
		//		$r = beerfilterOut($r);
		//		$rows = objMerge($rows,$r);				
		//	}
		return $rows;
	}
	
function getRatingBeers($db)
	{
		/*$stmt = $db->prepare('SELECT *,
			(SELECT AVG(rating_score) FROM beer_ratings br WHERE br.beer_id=b.beer_id) AS rating 
			FROM beers b
			LEFT JOIN breweries bw ON b.brewery_id = bw.brewery_id
			LEFT JOIN beer_photos bp ON b.beer_id = bp.beer_id
			WHERE b.beer_approved=1
			ORDER BY rating DESC
			');*/
			
		$stmt = $db->prepare("SELECT imageURL photoURL, avg(rating) rating, count(name) num, name, brewery, imageURL from full_reviews group by name, brewery order by rating desc");
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_OBJ);
		//foreach($rows as $r)
		//	{
		//		$r = beerfilterOut($r);
		//		$rows = objMerge($rows,$r);				
		//	}
		return $rows;
	}
		
function getPopularBeers($db)
	{
		/*$stmt = $db->prepare('SELECT *,COUNT(review_id) AS numReviews,
			(SELECT AVG(rating_score) FROM beer_ratings brv WHERE brv.beer_id=br.beer_id) AS rating 
			FROM beer_reviews br
			LEFT JOIN beers b ON b.beer_id = br.beer_id
			LEFT JOIN breweries bw ON b.brewery_id = bw.brewery_id
			LEFT JOIN beer_photos bp ON b.beer_id = bp.beer_id
			WHERE b.beer_approved=1
			GROUP BY br.beer_id
			ORDER BY numReviews DESC
		');*/
		$stmt = $db->prepare("SELECT imageURL photoURL, avg(rating) rating, count(name) num, name, brewery, imageURL from full_reviews group by name, brewery order by num desc");
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_OBJ);
		//foreach($rows as $r)
		//	{
		//		$r = beerfilterOut($r);
		//		$rows = objMerge($rows,$r);				
		//	}
		return $rows;
	}
		
function getBeerSearchResults($db,$uid,$search)
	{
		//echo'<pre>';print_r($search);echo'</pre>';
		$stmt = $db->prepare('SELECT *,
			(SELECT AVG(rating_score) FROM beer_ratings br WHERE br.beer_id=b.beer_id) AS rating 
			FROM beers b
			LEFT JOIN breweries bw ON b.brewery_id = bw.brewery_id
			LEFT JOIN beer_photos bp ON b.beer_id = bp.beer_id
			WHERE b.beer_name LIKE :beer_search AND b.beer_approved=1 
			LEFT JOIN beer_photos bp ON b.beer_id = bp.beer_id
			');
		$stmt->bindValue(':beer_search', '%'.$search.'%', PDO::PARAM_STR);
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_OBJ);
		foreach($rows as $r)
			{
				$r = beerfilterOut($r);
				$rows = objMerge($rows,$r);				
			}
		return $rows;
	}

function getAdditionalBeerInfo($db,$r)
	{
		settype($r->beer_id,'int');
		settype($r->style_id,'int');
		settype($r->brewery_id,'int');
		
		//settype($r->rating,'int');
		$r->rating = getRatingAverage($db,$r->beer_id);

		$beerStats = array('abv','ibu','og','tg');
		foreach ($beerStats as $bs) {
			$r->$bs = getPopularValue($db,$bs,$r->beer_id);
		}
		
		$wheelSpokes=array('bitter','body','linger','aroma','citrus','hoppy','floral','malty','toffee','burnt','sweet','sour');
		foreach ($wheelSpokes as $ws) {
			$r->$ws = getBeerWheelAvg($db,$r->beer_id,$ws);
		}
		
		$reviews=array('note','note_date','content','content_date');
		foreach ($reviews as $rev) {
			//echo'<pre>';print_r($r);echo'</pre>';
			$r->$rev = getBeerComments($db,$r->beer_id,$rev);
		}

		$r->photoURL = getBeerPhoto($db,$r->beer_id);

		return $r;
	}

function getBeerWheelAvg($db,$beer_id,$spoke)
	{
		if(isset($beer_id)==TRUE && is_int($beer_id)==TRUE && isset($spoke)==TRUE && is_string($spoke)==TRUE)
		{
			$stmt = $db->prepare("SELECT AVG(".$spoke.") AS ".$spoke." FROM beer_wheels WHERE beer_id=:beer_id LIMIT 1");
			$stmt->bindValue(':beer_id', $beer_id, PDO::PARAM_INT);
			$stmt->execute();
			$rows = $stmt->fetch(PDO::FETCH_OBJ);
			settype($rows->$spoke,'float');
			return $rows->$spoke;
		}
	}

function getBeerComments($db,$beer_id,$type)
	{
		if(isset($beer_id)==TRUE && is_int($beer_id)==TRUE && isset($type)==TRUE && is_string($type)==TRUE)
		{
			$field='review_'.$type;
			$stmt = $db->prepare("SELECT ".$field." FROM beer_reviews WHERE beer_id=:beer_id AND review_approved = 1 LIMIT 1");
			$stmt->bindValue(':beer_id', $beer_id, PDO::PARAM_INT);
			$stmt->execute();
			$rows = $stmt->fetch(PDO::FETCH_OBJ);
			settype($rows->$field,'string');
			return $rows->$field;
		}
	}

/*
	get utilities
*/
	
function beerfilterOut($beer)
	{
		$beer->id = $beer->beer_id;	
		$beer->name = $beer->beer_name;	
		$beer->style = $beer->style_name;	
		$beer->notes = $beer->note;	
		$beer->brewery = $beer->brewery_name;	
		$beer->photoURL = $beer->photo_url;	
		$beer->createDate = microtime(date('U',strtotime($beer->beer_date)))*100;	
		$filterOut=array(
			'user_id',		
			'beer_id',		
			'beer_name',		
			'beer_date',		
			'style_id',		
			'style_name',		
			'brewery_id',		
			'brewery_name',		
			'beer_approved',		
			'brewery_approved',		
			'review_id',		
			'note',		
			'note_date',		
			'content',		
			'content_date',		
			'photo_id',	
			'photo_url',	
			'photo_date',
			'photo_approved'	
			);
		foreach($filterOut as $fo) {
			unset($beer->$fo);
		}
		return $beer;	
	}


?>