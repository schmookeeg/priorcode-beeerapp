<?php

/*
	Main functionality
*/			



//function getBeerReview($db,$uid,$beerName,$breweryName)
function getBeer($db,$uid,$beerName)
	{
		$styleID = $_REQUEST['styleID'];
		$serving = $_REQUEST['serving'];
		$breweryName = $_REQUEST['breweryName'];
		$approved = 1;
		$uType = $_REQUEST['userType'];
		$uName = $_REQUEST['userName'];
		$uExtID = $_REQUEST['extID'];
		
		//if( isset($uid)==TRUE)
		//{
		//	$user_id = getUserID($db,$uid);																		// Uses external ID to look up uid in database
			//echo'<pre>';var_dump($user_id);echo'</pre>';
		//	if( isset($user_id) && $user_id != 0)																	// User not detected
		//	{
				// echo "GETTING BEER BY NAME: $beerName";
				$beer = getBeerByName($db,$beerName);	
				//exit;															// Gets full Beer array
				//echo'<pre>';var_dump($beer->id);echo $beerName;echo'</pre>';
				//if( isset($beer->id) == FALSE || $beer->id == '0' || empty($beer->error)==FALSE )							// Beer ID not detected (and no error)
				if($beer->id == '0')
				{
					//$breweryID = dbBreweryEntry($db,$breweryName);													// Creates Brewery
					//echo "NEW BREWERY: ".$breweryID;
					//if( isset($timeStamp) )	{	$createDate = date('Y-m-d', $timeStamp);	}							// ref: microtime for UNIX timestamp in microseconds
					//else					{	$createDate = date('Y-m-d');	}
					//else				{	$createDate = date('m-d-Y  H:m:s:ms');	}
					
					$newBeerID = dbBeerEntry($db,$beerName,$breweryID=0,$styleID=0,$serving='',$approved);					// Creates Beer if needed
					//echo "NEW BEER: ".$newBeerID;
					$beer = getBeerByID($db,$newBeerID);															// Gets full Beer array of new beer
				}
		//	}
		//	else
		//	{
		//		dbUserEntry($db,$uType,$uName,$uExtID);																// Make user as no user account was detected
		//	}
			
		//	$userData = getUserData($db,$user_id);
		//}
		//else
		//{
		//	$beer->error = 'User ID required';	
		//}

		$json->beerArray = $beer;
		//$json->userInfo = $userData;
		
		echo json_encode($json);
		//echo'<pre>';print_r($json);echo'</pre>';		
		//echo'<pre>';var_dump($json);echo'</pre>';		
	}

function getBeers($db,$uid,$mode,$search='')
	{		
		if( isset($uid)==TRUE && $uid != "0")
		{
			$user_id = getUserID($db,$uid);

			if( isset($user_id) && $user_id != 0)
			{
				//$beers = getAllBeers($db,$uid);
				if($search!='')
				{
					$beers = call_user_func_array($mode, array($db,$uid,$search) );
				}
				else
				{
					$beers = call_user_func_array($mode, array($db,$uid) );
				}
			}
			else
			{
				
			}
			
			$userData = getUserData($db,$user_id);
		}
		else
		{
			switch($mode)
			{
				case "getRecentBeers":
					$beers = getRecentBeers($db);
					break;
				case "getPopularBeers":
					$beers = getPopularBeers($db);
					break;
				case "getRatingBeers":
					$beers = getRatingBeers($db);
					break;
				case "getBeerSearchResults":
					$beers = getBeerByNameSearch($db,$search);
					break;
				case "getBeerSearchResultsAgg":
					$beers = getBeerByNameSearchAgg($db,$search);
					break;
				case "getBeerSearchResultsAggSingle":
					$beers = getBeerByNameSearchAggSingle($db,$search);
					break;
				case "getAllBeerSearchResults":
					$beers = getAllBeerByNameSearch($db,$search);
					break;
				case "getAllBeersByUserId":
					$beers = getAllBeersByUserId($db,$search);
					break;

			}
			
			//$beers->error = 'User ID required';	
		}

		$json->beerArray = $beers;
		$json->userInfo = $userData;
		
		echo json_encode($json);
		//echo'<pre>';print_r($json);echo'</pre>';		
		//echo'<pre>';var_dump($json);echo'</pre>';		
	}
?>