<?php

	require_once('db_connection.php');
	require_once('functions.php');
		
	if (empty($_REQUEST['do'])==FALSE) {
		switch( clean($_REQUEST["do"]) )
		{				
			case "getApprovedPics":
					GetApprovedPics ( $_REQUEST['page'] );
				break;
			case "getBeer":
					//echo "TRYING TO GET BEER INFO...".$_REQUEST['userID'].", ".$_REQUEST['beerName'];
					getBeer( $db , $_REQUEST['userID'], $_REQUEST['beerName'] );
				break;
			case "getBeerByID":
					//echo "TRYING TO GET BEER INFO...".$_REQUEST['userID'].", ".$_REQUEST['beerName'];
					getBeerByID( $db , $_REQUEST['ID'] );
				break;
			case "getBeers":
					getBeers( $db , $_REQUEST['userID'], 'getAllBeers' );
				break;
			case "getBeersRecent":
					getBeers( $db , $_REQUEST['userID'] , 'getRecentBeers' );
					//getBeersRecent( $db , $_REQUEST['user_id']);
				break;
			case "getBeersRating":
					getBeers( $db , $_REQUEST['userID'] , 'getRatingBeers' );
					//getBeersRating( $db , $_REQUEST['user_id']);
				break;
			case "getBeersPopular":
					getBeers( $db , $_REQUEST['userID'] , 'getPopularBeers' );
					//getBeersPopular( $db , $_REQUEST['user_id']);
				break;
			case "getBeersSearch":
					getBeers( $db , $_REQUEST['userID'] , 'getBeerSearchResults' , $_REQUEST['beerName'] );
				break;			
			case "getBeersSearchAgg":
					getBeers( $db , $_REQUEST['userID'] , 'getBeerSearchResultsAgg' , $_REQUEST['beerName'] );
				break;		
			case "getBeersSearchAggSingle":
					getBeers( $db , $_REQUEST['userID'] , 'getBeerSearchResultsAggSingle' , $_REQUEST['beerName'] );
				break;					
			case "getAllBeersSearch":
					getBeers( $db , $_REQUEST['userID'] , 'getAllBeerSearchResults' , $_REQUEST['beerName'] );
				break;								
			case "getAllBeersByUserID":
					// echo "yes";
					getBeers( $db , $_REQUEST['userID'] , 'getAllBeersByUserId' , $_REQUEST['uid'] );
				break;		

			case "dbBeer":
					//dbBeerEntry($db,$_REQUEST['beerName'],$_REQUEST['breweryID'],$_REQUEST['styleID'],$_REQUEST['serving'],1);
					dbBeerEntry($db,$_REQUEST['beerName'],$_REQUEST['breweryName'],$_REQUEST['styleName'],$_REQUEST['serving'],1);
				break;
			case "dbUser":
					dbUserEntry($db,$_REQUEST['userType'],$_REQUEST['userName'],$_REQUEST['extID']);
				break;
			case "dbPhoto":
					dbPhotoEntry($db,$_REQUEST['beerID'],$_REQUEST['userID'],$_REQUEST['photoURL']);
				break;
			case "dbBeerStat":
					dbBeerStatEntry($db,$_REQUEST['stat'],$_REQUEST['beerID'],$_REQUEST['userID'],$_REQUEST['statValue']);
				break;
			case "dbNote":
					dbNoteEntry($db,$_REQUEST['beerID'],$_REQUEST['userID'],$_REQUEST['noteText']);
				break;
			case "dbReview":
					dbReviewEntry($db,$_REQUEST['beerID'],$_REQUEST['userID'],$_REQUEST['reviewText']);
				break;
			case "dbStyle":
					dbStyleEntry($db,$_REQUEST['styleName']);
				break;
			case "dbRating":
					dbRatingEntry($db,$_REQUEST['beerID'],$_REQUEST['userID'],$_REQUEST['ratingScore']);
				break;
			case "dbWheel":
					dbWheelEntry($db,$_REQUEST['beerID'],$_REQUEST['userID'],$_REQUEST['bitter'],$_REQUEST['body'],$_REQUEST['linger'],$_REQUEST['aroma'],$_REQUEST['citrus'],$_REQUEST['hoppy'],$_REQUEST['floral'],$_REQUEST['malty'],$_REQUEST['toffee'],$_REQUEST['burnt'],$_REQUEST['sweet'],$_REQUEST['sour']);
				break;
			case "dbBrewery":
					dbBreweryEntry($db,$_REQUEST['breweryName']);
				break;
		}
	}

	
?>