<?php
///////////////////////////////////////////////////////////
///// Digicution Simple Twitter Feed Update Function  /////
///////////////////////////////////////////////////////////

function dt_twitter_update($tweetNoOverride=NULL) {

	////////////////////////////////////////////////////////////////////////////////
	//////  New CURL Only Method As We Need To Send OAuth Headers For 1.1 API //////
	////////////////////////////////////////////////////////////////////////////////

	//Only Run Update If CURL Exists
	if (function_exists('curl_init')) { 

		///////////////////////////////////
		////// Define Main Variables //////
		///////////////////////////////////
	
		//Define Wordpress Conn As Global
		global $wpdb;
		
		//Define Tables
		$table_dt_twitter=$wpdb->prefix."dt_twitter";
		
		///////////////////////////////////
		////// Construct Twitter URL //////
		///////////////////////////////////
		
		//Get OAuth Authentication Details (Twitter API V1.1)
		$dt_twitter_oauth_access_token=dtCrypt('d',get_option('dt_twitter_oauth_access_token'));
		$dt_twitter_oauth_access_token_secret=dtCrypt('d',get_option('dt_twitter_oauth_access_token_secret'));
		$dt_twitter_consumer_key=dtCrypt('d',get_option('dt_twitter_consumer_key'));
		$dt_twitter_consumer_secret=dtCrypt('d',get_option('dt_twitter_consumer_secret'));
	
		//Get General Options
		$screenname=get_option('dt_twitter_screenname');
		$size=get_option('dt_twitter_tweetsize');
		//If Override Size Is Bigger Than Option Size - Increase Limit So We Get Enough Tweets
		if($tweetNoOverride && ($tweetNoOverride>$size)) { $size=$tweetNoOverride; }
		$getretweets=get_option('dt_twitter_retweet');
				
		//Check Our Retweets Option
		if($getretweets==1) {
			
			//Get All Tweets URL
			$url="https://api.twitter.com/1.1/statuses/user_timeline.json?count=$size&include_entities=true&include_rts=true&screen_name=$screenname";
	
		//Otherwise
		} else {
			
			//Multiple Size By 20 To Do Our Best To Ensure We Get Enough Native Tweets To Fill Our Size Value
			$size=$size*20;
			
			//No ReTweets URL
			$url="https://api.twitter.com/1.1/statuses/user_timeline.json?count=$size&exclude_replies=true&include_entities=true&include_rts=false&screen_name=$screenname";
		
		//End Retweet Option Check	
		}	
	
		////////////////////////////////////////////////////////////////////////////////////////////////////////
		////// Lookup Twitter Details - New CURL Only Method As We Need To Send OAuth Headers For 1.1 API //////
		////////////////////////////////////////////////////////////////////////////////////////////////////////
	
		//Create Base URL String Function
		function buildBaseString($baseURI, $method, $params) { $r = array(); ksort($params); foreach($params as $key=>$value){ $r[] = "$key=" . rawurlencode($value); } return $method."&" . rawurlencode($baseURI) . '&' . rawurlencode(implode('&', $r)); }
		
		//Create OAuth CURL Header Function
		function buildAuthorizationHeader($oauth) { $r = 'Authorization: OAuth '; $values = array(); foreach($oauth as $key=>$value) $values[] = "$key=\"" . rawurlencode($value) . "\""; $r .= implode(', ', $values); return $r; }
	
		//If We Have All Required Tokens & Keys
		if($dt_twitter_oauth_access_token && $dt_twitter_oauth_access_token_secret && $dt_twitter_consumer_key && $dt_twitter_consumer_secret) {
	
			///////////////////////////////////////
			////// Construct Twitter Details //////
			///////////////////////////////////////
			
			$oauth_hash = '';
			$oauth_hash .= 'count='.$size.'&';
			if($getretweets!=1) { $oauth_hash .= 'exclude_replies=true&'; }
			$oauth_hash .= 'include_entities=true&';
			if($getretweets==1) { $oauth_hash .= 'include_rts=true&'; } else { $oauth_hash .= 'include_rts=false&'; }
			$oauth_hash .= 'oauth_consumer_key='.$dt_twitter_consumer_key.'&';
			$oauth_hash .= 'oauth_nonce=' . time() . '&';
			$oauth_hash .= 'oauth_signature_method=HMAC-SHA1&';
			$oauth_hash .= 'oauth_timestamp=' . time() . '&';
			$oauth_hash .= 'oauth_token='.$dt_twitter_oauth_access_token.'&';
			$oauth_hash .= 'oauth_version=1.0&';	
			$oauth_hash .= 'screen_name='.$screenname;
	
			$base = '';
			$base .= 'GET';
			$base .= '&';
			$base .= rawurlencode('https://api.twitter.com/1.1/statuses/user_timeline.json');
			$base .= '&';
			$base .= rawurlencode($oauth_hash);	
			
			$key = '';
			$key .= rawurlencode($dt_twitter_consumer_secret);
			$key .= '&';
			$key .= rawurlencode($dt_twitter_oauth_access_token_secret);
			
			$signature = base64_encode(hash_hmac('sha1', $base, $key, true));
			$signature = rawurlencode($signature);	
	
			$oauth_header = '';
			$oauth_header .= 'count="'.$size.'", ';
			if($getretweets!=1) { $oauth_header .= 'exclude_replies="true", '; }
			$oauth_header .= 'include_entities="true", ';
			if($getretweets==1) { $oauth_header .= 'include_rts="true", '; } else { $oauth_header .= 'include_rts="false", '; }
			$oauth_header .= 'oauth_consumer_key="'.$dt_twitter_consumer_key.'", ';
			$oauth_header .= 'oauth_nonce="' . time() . '", ';
			$oauth_header .= 'oauth_signature="' . $signature . '", ';
			$oauth_header .= 'oauth_signature_method="HMAC-SHA1", ';
			$oauth_header .= 'oauth_timestamp="' . time() . '", ';
			$oauth_header .= 'oauth_token="'.$dt_twitter_oauth_access_token.'", ';
			$oauth_header .= 'oauth_version="1.0", ';
			$oauth_header .= 'screen_name="'.$screenname.'"';
			
			$curl_header = array("Authorization: Oauth {$oauth_header}", 'Expect:');	
	
			////////////////////////////////////
			////// Lookup Twitter Details //////
			////////////////////////////////////
	
			$curl_request = curl_init();
			curl_setopt($curl_request, CURLOPT_HTTPHEADER, $curl_header);
			curl_setopt($curl_request, CURLOPT_HEADER, false);
			curl_setopt($curl_request, CURLOPT_URL, $url);
			curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, false);
			$json = curl_exec($curl_request);
			curl_close($curl_request);	
	
			//Decode Data Ready For Processing
			$data=json_decode($json);
		
			//For Debugging Purposes - Display All Info Returned By Twitter API
			//print_r($json);
			//echo '<br/><br/>';
			//print_r($data);
			//echo '<br/><br/>';
		
			////////////////////////////////////
			//////  Decode Twitter JSON  ///////
			////////////////////////////////////
			
			//If We Have Data
			if ($data) {
			
				//Clear Database Table
				$wpdb->query("DELETE FROM $table_dt_twitter"); 
			
				//Set Counter
				$tweetcount=0;
						
				//Loop Through Tweets
				foreach($data as $t) {
			
					//Add 1 To Tweetcounter
					$tweetcount++;
					
					//If Tweetcounter Is Not More Than Our Tweetsize
					if ($tweetcount<=$size) {
			
						//Set Standard Variables
						$tweet="";
						$retweet=1;
				
						//Get Tweet ID For Date Link
						$tweetid=$t->id;
						
						//Grab User Vars
						$user=$t->user;
						
						//Grab User Full Name
						$userfullname=$t->user->name;
						
						//Grab Tweeter Location
						$userlocation=$t->user->location;
						
						//Grab Tweet Profile Image (In Case It Is ReTweet)
						$image=$t->retweeted_status->user->profile_image_url;
						
						//Grab Tweeter Screen Name (In Case It Is ReTweet)
						$user_screen_name=$t->retweeted_status->user->screen_name;
						
						//Grab Tweeter Full Name
						$user_full_name=$t->retweeted_status->user->name;
						
						//Grab Tweeter Location
						$user_location=$t->retweeted_status->user->location;
						
						//If No Image Use Our Profile Image
						if (!$image) { $image = $user->profile_image_url; }
						
						//If Not Re-Tweet - Must Be Ours So Use Our Tweet Vars & Set Re-Tweet Variable
						if (!$user_screen_name) { $user_screen_name = $screenname; $user_full_name=$userfullname; $user_location=$userlocation; $retweet=0; }
						
						//Add The Tweet Text
						$tweet = $t->text;
									
						//Grab Tweet Entities
						$entities = $t->entities;
									
						//Set Counter To 0
						$i = 0;
						
						//Convert URL Strings To Actual URLs
						$tweet=dt_convert_urls($tweet);
												
						//Loop Through Our Replacement Array & Make The Changes For URL's & Mention Links
						for($i = 0; $i < count($string); $i++) {
							$pattern = $replace[$i];
							$tweet = str_replace($pattern, $string[$i], $tweet);
						}
						
						//Sort Out Date & Tweet Link
						$date=strtotime($t->created_at);
						$date=human_time_diff($date,current_time("timestamp"));
									
						//Clean Twitter ID
						$tweetid=mysql_real_escape_string($tweetid);
						
						//Check If Tweet Exists In DB
						$tweetCheck=$wpdb->prepare("SELECT id FROM $table_dt_twitter WHERE tweetid=%d",$tweetid);
						$tweetChecker=$wpdb->get_row($tweetCheck,OBJECT);
											
						//If We Have A Tweet With This ID - Delete The Record So We Can Insert New One, Updating Is So Last Year :)
						if (!empty($tweetChecker)) { $wpdb->query("DELETE FROM $table_dt_twitter WHERE tweetid=".$tweetid); }
						
						//Insert Tweet Into DB
						$wpdb->insert($table_dt_twitter, array('tweetid' => $tweetid, 'tweet' => $tweet, 'screenname' => $user_screen_name, 'profileimage' => $image, 'retweet' => $retweet, 'fullname' => $user_full_name, 'location' => $user_location, 'tweetreaddate' => $date));	
				
					//End If Tweetcount Is Not More Than Size
					}
				
				//End For Each Tweet Loop		
				}
				
				//Optimize Wordpress Twitter Table
				$wpdb->query("OPTIMIZE TABLE $table_dt_twitter"); 
			
			//End If We Have Data Statement
			}
		
		//End If We Have ALl Required Keys & Tokens	
		}

	//End Only Run If CURL Exists
	}

//End Update Function	
}


///////////////////////////////////////////////////////////
/////  Digicution Simple Twitter Feed Main Function   /////
///////////////////////////////////////////////////////////	
	
function dt_twitter($tweetNoOverride=NULL) {

	////////////////////////////////////////////////////////////////////////////////
	//////  New CURL Only Method As We Need To Send OAuth Headers For 1.1 API //////
	////////////////////////////////////////////////////////////////////////////////

	//Only Run Update If CURL Exists
	if (function_exists('curl_init')) { 
	
		//Initiate Wordpress DB As Global Please...
		global $wpdb;
		
		//Define Tables
		$table_dt_twitter=$wpdb->prefix."dt_twitter";
	
		//Get Our Options
		$twitterupdate=get_option('dt_twitter_twitterupdate');
		
		//Pull Last Tweet From DB 
		$queryTweet="SELECT tweetid, tweetdate FROM $table_dt_twitter ORDER BY tweetid DESC LIMIT 0,1";
		$lastTweet=$wpdb->get_row($queryTweet, OBJECT);
	
	    //If We Have A Last Tweet...
	    if (!empty($lastTweet)) {
		 	 
			//Grab Variables We Need
			$tweetID=$lastTweet->tweetid;
			$tweetDate=$lastTweet->tweetdate;
			
			//Add An Hour To The Timestamp For Checking
			$tweetDateCheck=strtotime($tweetDate)+$twitterupdate;
					
			//If Twitter Was Updated Less Than Our Update Frequency Timeout		
			if ($tweetDateCheck >= time()) {
				
				//Display The Tweets
				dt_twitter_display($tweetNoOverride);
			
			//Otherwise - We Need To Update	
			} else {
			
				//Udate The Tweets
				dt_twitter_update($tweetNoOverride);
				
				//Display The Tweets
				dt_twitter_display($tweetNoOverride);
				
			//End Timeout Check	
			}
		
		//Otherwise - We Have No Tweets In The DB So...	
		} else {
		
			//Attempt To Update The Tweets
			dt_twitter_update($tweetNoOverride);
			
			//Atttempt To Display The Tweets
			dt_twitter_display($tweetNoOverride);
		
		//End If We Have A Last Tweet
		}
	
	//End If No CURL Function Exists
	}

//End Function	
}


///////////////////////////////////////////////////////////
///// Digicution Simple Twitter Feed Display Function /////
///////////////////////////////////////////////////////////

function dt_twitter_display($tweetNoOverride=NULL) {
	
	//Initiate Wordpress DB As Global Please...
	global $wpdb;
	
	//Define Tables
	$table_dt_twitter=$wpdb->prefix."dt_twitter";

	//Get Our General Options
	$screenname=get_option('dt_twitter_screenname');
	$size=get_option('dt_twitter_tweetsize');	
	$twitterimages=get_option('dt_twitter_images');
	$twitterfollow=get_option('dt_twitter_follow');
	$twitterpexpand=get_option('dt_twitter_post_expand');	
	$twitterpreply=get_option('dt_twitter_post_reply');	
	$twitterpretweet=get_option('dt_twitter_post_retweet');	
	$twitterpfavourite=get_option('dt_twitter_post_favourite');
	$twitter_screenname_display=get_option('dt_twitter_screenname_display');
	$twitter_fullname_display=get_option('dt_twitter_fullname_display');
	$twitter_date_display=get_option('dt_twitter_readdate_display');
	$twitter_header_display=get_option('dt_twitter_header_display');
	$twitter_header_title=get_option('dt_twitter_header_title');
	$twitter_header_follow=get_option('dt_twitter_header_follow');
	$twitter_hashtag_convert=get_option('dt_twitter_hashtag_convert');
	$twitter_username_convert=get_option('dt_twitter_username_convert');
	
	//If Override Size Option Submitted - Override Size
	if($tweetNoOverride) { $size=$tweetNoOverride; }

	//Grab Tweets From DB
	$queryTweets="SELECT tweetid, tweet, screenname, profileimage, fullname, tweetreaddate FROM $table_dt_twitter ORDER BY tweetid DESC LIMIT 0,".$size;
	$tweety=$wpdb->get_results($queryTweets, OBJECT);
	
	//Get Automatic Styling Option
	$dtauto=get_option('dt_twitter_display_auto');
	
	//Define Style Variable
	$style='';
	
	//If Automatic Styling Is Set To Yes
	if($dtauto==1) {
	
		//Get Main Container Options
		$dt_twitter_display_mcwidth=get_option('dt_twitter_display_mcwidth');
		$dt_twitter_display_mcwidth_unit=get_option('dt_twitter_display_mcwidth_unit');
		$dt_twitter_display_mcpadding=get_option('dt_twitter_display_mcpadding');
		$dt_twitter_display_mcpadding_unit=get_option('dt_twitter_display_mcpadding_unit');
		$dt_twitter_display_mcmargintop=get_option('dt_twitter_display_mcmargintop');
		$dt_twitter_display_mcmargintop_unit=get_option('dt_twitter_display_mcmargintop_unit');
		$dt_twitter_display_mcmarginbottom=get_option('dt_twitter_display_mcmarginbottom');
		$dt_twitter_display_mcmarginbottom_unit=get_option('dt_twitter_display_mcmarginbottom_unit');
		$dt_twitter_display_mcmarginleft=get_option('dt_twitter_display_mcmarginleft');
		$dt_twitter_display_mcmarginleft_unit=get_option('dt_twitter_display_mcmarginleft_unit');
		$dt_twitter_display_mcmarginright=get_option('dt_twitter_display_mcmarginright');
		$dt_twitter_display_mcmarginright_unit=get_option('dt_twitter_display_mcmarginright_unit');
		$dt_twitter_display_mcbg=get_option('dt_twitter_display_mcbg');
		$dt_twitter_display_mcbg_enabled=get_option('dt_twitter_display_mcbg_enabled');
		
		//Create Our CSS Container Style
		$style=' style="width:'.$dt_twitter_display_mcwidth;
		if ($dt_twitter_display_mcwidth_unit==1) { $style.='px;'; } else { $style.='%;'; }
		if ($dt_twitter_display_mcpadding) { $style.='padding:'.$dt_twitter_display_mcpadding; if($dt_twitter_display_mcpadding_unit==1) { $style.='px;'; } else { $style.='%;'; } }
		if ($dt_twitter_display_mcmargintop) { $style.='margin-top:'.$dt_twitter_display_mcmargintop; if($dt_twitter_display_mcmargintop_unit==1) { $style.='px;'; } else { $style.='%;'; } }
		if ($dt_twitter_display_mcmarginbottom) { $style.='margin-bottom:'.$dt_twitter_display_mcmarginbottom; if($dt_twitter_display_mcmarginbottom_unit==1) { $style.='px;'; } else { $style.='%;'; } }
		if ($dt_twitter_display_mcmarginleft) { $style.='margin-left:'.$dt_twitter_display_mcmarginleft; if($dt_twitter_display_mcmarginleft_unit==1) { $style.='px;'; } else { $style.='%;'; } }
		if ($dt_twitter_display_mcmarginright) { $style.='margin-right:'.$dt_twitter_display_mcmarginright; if($dt_twitter_display_mcmarginright_unit==1) { $style.='px;'; } else { $style.='%;'; } }
		if ($dt_twitter_display_mcbg_enabled==1) { $style.='background-color:'.$dt_twitter_display_mcbg.';'; }
		$style.='"';

	//End If Automatic Styling Set To Yes
	}
	
	//If Header Option Is Selected
	if($twitter_header_display==1) {
	
		//Start Header
		echo '<div class="dt-twitter-header">'.$twitter_header_title;
		
		//If We Have A Header Follow Text Option
		if ($twitter_header_follow==1) { echo '<a href="http://twitter.com/'.$screenname.'" class="dt-twitter-header-follow" rel="nofollow">Follow @'.$screenname.'</a></div>'; } 
		
		//If We Have A Header Follow Button Option
		if ($twitter_header_follow==2) { echo '<a href="https://twitter.com/'.$screenname.'" class="twitter-follow-button" data-show-count="false" data-show-screen-name="false" data-dnt="true">Follow</a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?\'http\':\'https\';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+\'://platform.twitter.com/widgets.js\';fjs.parentNode.insertBefore(js,fjs);}}(document, \'script\', \'twitter-wjs\');</script>'; }

		//End Header
		echo '</div>';

	//End If Header Option Selected	
	}
	
	//Start The Unordered List
	echo '<ul class="dt-twitter"'.$style.'>';
	
	//Zero Odd Even Counter
	$oddity=0;
	
	//Main Count
	$count=0;
	
	//Define Tweet Style Variables
	$listyle='';
	$lialtstyle='';
	$astyle='';
	$imgstyle='';
	
	//If Automatic Styling Is Set To Yes
	if($dtauto==1) {
	
		//Get Tweet Styling Options
		$dt_twitter_display_fontsize=get_option('dt_twitter_display_fontsize');
		$dt_twitter_display_fontsize_unit=get_option('dt_twitter_display_fontsize_unit');
		$dt_twitter_display_fontcolor=get_option('dt_twitter_display_fontcolor');
		$dt_twitter_display_linkcolor=get_option('dt_twitter_display_linkcolor');
		$dt_twitter_display_tweetbg=get_option('dt_twitter_display_tweetbg');
		$dt_twitter_display_tweetbg_enabled=get_option('dt_twitter_display_tweetbg_enabled');
		$dt_twitter_display_tweetbgalt=get_option('dt_twitter_display_tweetbgalt');
		$dt_twitter_display_tweetbgalt_enabled=get_option('dt_twitter_display_tweetbgalt_enabled');
		$dt_twitter_display_tweetpadding=get_option('dt_twitter_display_tweetpadding');
		$dt_twitter_display_tweetpadding_unit=get_option('dt_twitter_display_tweetpadding_unit');
		$dt_twitter_display_tweetmargintop=get_option('dt_twitter_display_tweetmargintop');
		$dt_twitter_display_tweetmargintop_unit=get_option('dt_twitter_display_tweetmargintop_unit');
		$dt_twitter_display_tweetmarginbottom=get_option('dt_twitter_display_tweetmarginbottom');
		$dt_twitter_display_tweetmarginbottom_unit=get_option('dt_twitter_display_tweetmarginbottom_unit');
		$dt_twitter_display_tweetmarginleft=get_option('dt_twitter_display_tweetmarginleft');
		$dt_twitter_display_tweetmarginleft_unit=get_option('dt_twitter_display_tweetmarginleft_unit');
		$dt_twitter_display_tweetmarginright=get_option('dt_twitter_display_tweetmarginright');
		$dt_twitter_display_tweetmarginright_unit=get_option('dt_twitter_display_tweetmarginright_unit');
		$dt_twitter_display_tweetpaddingtop=get_option('dt_twitter_display_tweetpaddingtop');
		$dt_twitter_display_tweetpaddingtop_unit=get_option('dt_twitter_display_tweetpaddingtop_unit');
		$dt_twitter_display_tweetpaddingbottom=get_option('dt_twitter_display_tweetpaddingbottom');
		$dt_twitter_display_tweetpaddingbottom_unit=get_option('dt_twitter_display_tweetpaddingbottom_unit');
		$dt_twitter_display_tweetpaddingleft=get_option('dt_twitter_display_tweetpaddingleft');
		$dt_twitter_display_tweetpaddingleft_unit=get_option('dt_twitter_display_tweetpaddingleft_unit');
		$dt_twitter_display_tweetpaddingright=get_option('dt_twitter_display_tweetpaddingright');
		$dt_twitter_display_tweetpaddingright_unit=get_option('dt_twitter_display_tweetpaddingright_unit');

		//Create Our Tweet CSS Style
		if ($dt_twitter_display_tweetbg_enabled==1) { $listyle='background:'.$dt_twitter_display_tweetbg.';'; } 
		if ($dt_twitter_display_tweetbgalt_enabled==1) { $lialtstyle='background:'.$dt_twitter_display_tweetbgalt.';'; } else { $lialtstyle=$listyle; }
		if ($dt_twitter_display_tweetmargintop) { if ($dt_twitter_display_tweetmargintop_unit==1) { $dtpu.='px'; } else { $dtpu.='%'; } $listyle.='margin-top:'.$dt_twitter_display_tweetmargintop.$dtpu.';'; $lialtstyle.='margin-top:'.$dt_twitter_display_tweetmargintop.$dtpu.';'; } 
		if ($dt_twitter_display_tweetmarginbottom) { if ($dt_twitter_display_tweetmarginbottom_unit==1) { $dtpu.='px'; } else { $dtpu.='%'; } $listyle.='margin-bottom:'.$dt_twitter_display_tweetmarginbottom.$dtpu.';'; $lialtstyle.='margin-bottom:'.$dt_twitter_display_tweetmarginbottom.$dtpu.';'; } 
		if ($dt_twitter_display_tweetmarginleft) { if ($dt_twitter_display_tweetmarginleft_unit==1) { $dtpu.='px'; } else { $dtpu.='%'; } $listyle.='margin-left:'.$dt_twitter_display_tweetmarginleft.$dtpu.';'; $lialtstyle.='margin-left:'.$dt_twitter_display_tweetmarginleft.$dtpu.';'; } 
		if ($dt_twitter_display_tweetmarginright) { if ($dt_twitter_display_tweetmarginright_unit==1) { $dtpu.='px'; } else { $dtpu.='%'; } $listyle.='margin-right:'.$dt_twitter_display_tweetmarginright.$dtpu.';'; $lialtstyle.='margin-right:'.$dt_twitter_display_tweetmarginright.$dtpu.';'; } 		
		if ($dt_twitter_display_tweetpaddingtop) { if ($dt_twitter_display_tweetpaddingtop_unit==1) { $dtpu.='px'; } else { $dtpu.='%'; } $listyle.='padding-top:'.$dt_twitter_display_tweetpaddingtop.$dtpu.';'; $lialtstyle.='padding-top:'.$dt_twitter_display_tweetpaddingtop.$dtpu.';'; } 
		if ($dt_twitter_display_tweetpaddingbottom) { if ($dt_twitter_display_tweetpaddingbottom_unit==1) { $dtpu.='px'; } else { $dtpu.='%'; } $listyle.='padding-bottom:'.$dt_twitter_display_tweetpaddingbottom.$dtpu.';'; $lialtstyle.='padding-bottom:'.$dt_twitter_display_tweetpaddingbottom.$dtpu.';'; } 
		if ($dt_twitter_display_tweetpaddingleft) { if ($dt_twitter_display_tweetpaddingleft_unit==1) { $dtpu.='px'; } else { $dtpu.='%'; } $listyle.='padding-left:'.$dt_twitter_display_tweetpaddingleft.$dtpu.';'; $lialtstyle.='padding-left:'.$dt_twitter_display_tweetpaddingleft.$dtpu.';'; } 
		if ($dt_twitter_display_tweetpaddingright) { if ($dt_twitter_display_tweetpaddingright_unit==1) { $dtpu.='px'; } else { $dtpu.='%'; } $listyle.='padding-right:'.$dt_twitter_display_tweetpaddingright.$dtpu.';'; $lialtstyle.='padding-right:'.$dt_twitter_display_tweetpaddingright.$dtpu.';'; } 

	//End If Automatic Styling Set To Yes
	}

	//Loop Through Tweets If We Have Them
	foreach($tweety as $i => $item) {
	
		//Get Vars
		$req_tweetid=$item->tweetid;
		$req_tweet=$item->tweet;
		$req_screenname=$item->screenname;
		$req_profileimage=$item->profileimage;
		$req_fullname=$item->fullname;
		$req_readdate=$item->tweetreaddate;
				
		//Add 1 To Oddity Counter
		$oddity++;
		
		//Add 1 To Main Counter
		$count++;
		
		//Define List Styles
		$list_style='';
		$list_style_alt='';
		
		//Create List Styles If Necessary
		if($listyle) { $list_style=' style="'.$listyle.'"'; }
		if($lialtstyle) { $list_style_alt=' style="'.$lialtstyle.'"'; }
						
		//Echo List Starter
		if ($count==1) {
			echo '<li class="first"'.$list_style.'>';
		} elseif ($count==$size && $oddity==2) {
			echo '<li class="last_even"'.$list_style_alt.'>';
		} elseif ($count==$size) {
			echo '<li'.$list_style.'>';
		} elseif ($oddity==2) {
			echo '<li class="post_even"'.$list_style_alt.'>';
		} else {
			echo '<li'.$list_style.'>';
		}
		
		//If User Images Option Is Selected & We Have A User Image
		if (($twitterimages==1) && ($req_profileimage)) {
		
			//Grab Image Details
			$dt_twitter_image_size=get_option('dt_twitter_image_size');
			$dt_twitter_image_marginright=get_option('dt_twitter_image_marginright');
			$dt_twitter_image_marginright_unit=get_option('dt_twitter_image_marginright_unit');
			$dt_twitter_image_marginbottom=get_option('dt_twitter_image_marginbottom');
			$dt_twitter_image_marginbottom_unit=get_option('dt_twitter_image_marginbottom_unit');

			//If Automatic Styling Is Set To Yes
			if($dtauto==1) {
			
				//Get Our Image Margins
				$imgmarginright=$dt_twitter_image_marginright; if($dt_twitter_image_marginright_unit==1) { $imgmarginright.='px'; } else { $imgmarginright.='%'; }
				$imgmarginbottom=$dt_twitter_image_marginbottom; if($dt_twitter_image_marginbottom_unit==1) { $imgmarginbottom.='px'; } else { $imgmarginbottom.='%'; }
				
				//Get Our Image Size
				$imgsize=$dt_twitter_image_size.'px';
				
				//Display User Image
				echo '<a target="_blank" class="dt-twitter-avatar-link" style="float:left;" href="http://twitter.com/'.$req_screenname.'"><img src="'.$req_profileimage.'" class="dt-twitter-avatar" alt="'.$req_profileimage.' avatar" title="'.$req_profileimage.' avatar" style="float:left;margin-right:'.$imgmarginright.';margin-bottom:'.$imgmarginbottom.';width:'.$imgsize.';height:'.$imgsize.';" /></a>';
				
			//Otherwise - Write Out Standard For Manual Styling
			} else {
			
				//Display User Image
				echo '<a target="_blank" class="dt-twitter-avatar-link" href="http://twitter.com/'.$req_screenname.'"><img src="'.$req_profileimage.'" class="dt-twitter-avatar" alt="'.$req_profileimage.' avatar" title="'.$req_profileimage.' avatar" /></a>';
				
			//End Automatic / Manual Styling (Images)
			}
			
		//End If We Have Image / Image Set	
		} 
		
		//Add Main Tweet Container For Manual Styling
		$req_tweet='<div class="dt-twitter-tweetbody">'.$req_tweet.'</div>';
			
		//Add Our Date If Turned On (Before)
		if($twitter_date_display==1) { $req_tweet='<div class="dt-twitter-readdate"><a target="_blank" href="http://twitter.com/'.$req_screenname.'/status/'.$req_tweetid.'">about '.$req_readdate.' ago</a></div>'.$req_tweet; }

		//Add Our Date If Turned On (After)
		if($twitter_date_display==2) { $req_tweet.='<div class="dt-twitter-readdate"><a target="_blank" href="http://twitter.com/'.$req_screenname.'/status/'.$req_tweetid.'">about '.$req_readdate.' ago</a></div>'; }
		
		//Add Our Screen Name If Turned On
		if($twitter_screenname_display==1) { $req_tweet='<div class="dt-twitter-screenname"><a target="_blank" href="http://twitter.com/'.$req_screenname.'">@'.$req_screenname.'</a></div>'.$req_tweet; }
		
		//Add Our Full Name If Turned On
		if($twitter_fullname_display==1) { $req_tweet='<div class="dt-twitter-fullname"><a target="_blank" href="http://twitter.com/'.$req_screenname.'">'.$req_fullname.'</a></div>'.$req_tweet; }

		//Convert Hashtags To Links If Turned On
		if($twitter_hashtag_convert==1) { $req_tweet=preg_replace('/\#([a-z0-9]+)/i','<a href="https://twitter.com/search?q=%23$1&src=hash" target="_blank">#$1</a>',$req_tweet); }

		//Convert Usernames To Links If Turned On
		if($twitter_username_convert==1) { $req_tweet=preg_replace('/\@([A-Za-z0-9_]+)/i','<a href="https://twitter.com/$1" target="_blank">@$1</a>',$req_tweet); }
		
		//If Automatic Styling Is Set To Yes
		if($dtauto==1) {
	
			//Get Our Font Styling
			$fontstyle=''; $fontsize=$dt_twitter_display_fontsize; if($dt_twitter_display_fontsize_unit==1) { $fontstyle=' style="font-size:'.$fontsize.'px;line-height:'.$fontsize.'px;'; if($dt_twitter_display_fontcolor) { $fontstyle.='color:'.$dt_twitter_display_fontcolor.';'; } $fontstyle.='"'; } else { $fontstyle=' style="font-size:'.$fontsize.'%;line-height:'.$fontsize.'%;'; if($dt_twitter_display_fontcolor) { $fontstyle.='color:'.$dt_twitter_display_fontcolor.';'; } $fontstyle.='"'; }
			
			//Get Our Link Styling
			$linkstyle=''; $fontsize=$dt_twitter_display_fontsize; if($dt_twitter_display_fontsize_unit==1) { $linkstyle='style="font-size:'.$fontsize.'px;line-height:'.$fontsize.'px;'; if($dt_twitter_display_linkcolor) { $linkstyle.='color:'.$dt_twitter_display_linkcolor.';'; } $linkstyle.='"'; } else { $linkstyle='style="font-size:'.$fontsize.'%;line-height:'.$fontsize.'%;'; if($dt_twitter_display_linkcolor) { $linkstyle.='color:'.$dt_twitter_display_linkcolor.';'; } $linkstyle.='"'; }
			
			//If We Have Tweet Link Color Set
			if ($dt_twitter_display_linkcolor) { $req_tweet=str_replace('<a','<a style="color:'.$dt_twitter_display_linkcolor.';"',$req_tweet); }

			//If We Have A Global Padding Value - Add It To Bottom Of Tweet
			if ($dt_twitter_display_mcpadding) { $globalpadding=$dt_twitter_display_mcpadding.'px'; } else { $globalpadding='0px'; }
			
			//Write Out Tweet
			echo '<span'.$fontstyle.'>'.$req_tweet.'</span><div style="clear:both;margin-bottom:'.$globalpadding.';"></div>';

			//If We Have Any Post Options
			if (($twitterpexpand==1) || ($twitterpreply==1) || ($twitterpretweet==1) || ($twitterpfavourite==1)) {
				
				//Start Container
				echo '<div class="dt-twitter-end-container">';
				
				if ($twitterpexpand==1) { echo '<a '.$linkstyle.' href="http://twitter.com/'.$screenname.'/status/'.$req_tweetid.'" class="dt-twitter-button-expand" rel="external" target="_blank">Expand</a>&nbsp;'; }
				if ($twitterpreply==1) { echo '<a '.$linkstyle.' href="http://twitter.com/intent/tweet?related='.$screenname.'&in_reply_to='.$req_tweetid.'" class="dt-twitter-button-reply" rel="external" target="_blank">Reply</a>&nbsp;'; }
				if ($twitterpretweet==1) { echo '<a '.$linkstyle.' href="http://twitter.com/intent/retweet?related='.$screenname.'&tweet_id='.$req_tweetid.'" class="dt-twitter-button-retweet" rel="external" target="_blank">Retweet</a>&nbsp;'; }
				if ($twitterpfavourite==1) { echo '<a '.$linkstyle.' href="http://twitter.com/intent/favorite?related='.$screenname.'&tweet_id='.$req_tweetid.'" class="dt-twitter-button-favourite" rel="external" target="_blank">Favourite</a>&nbsp;'; }
	
				//Close Container
				echo '</div>';
			}

		//Otherwise - Write Standard Tweet For Manual Styling
		} else {
			
			//Write Out Tweet
			echo '<span class="dt-twitter-tweet">'.$req_tweet.'</span>';

			//If We Have Any Post Options
			if (($twitterpexpand==1) || ($twitterpreply==1) || ($twitterpretweet==1) || ($twitterpfavourite==1)) {
				
				//Start Container
				echo '<div class="dt-twitter-end-container">';
				
				if ($twitterpexpand==1) { echo '<a href="http://twitter.com/'.$screenname.'/status/'.$req_tweetid.'" class="dt-twitter-button-expand" rel="external" target="_blank">Expand</a>'; }
				if ($twitterpfavourite==1) { echo '<a href="http://twitter.com/intent/favorite?related='.$screenname.'&tweet_id='.$req_tweetid.'" class="dt-twitter-button-favourite" rel="external" target="_blank">Favourite</a>'; }
				if ($twitterpretweet==1) { echo '<a href="http://twitter.com/intent/retweet?related='.$screenname.'&tweet_id='.$req_tweetid.'" class="dt-twitter-button-retweet" rel="external" target="_blank">Retweet</a>'; }
				if ($twitterpreply==1) { echo '<a href="http://twitter.com/intent/tweet?related='.$screenname.'&in_reply_to='.$req_tweetid.'" class="dt-twitter-button-reply" rel="external" target="_blank">Reply</a>'; }
	
				//If Icons Selected
				///if ($twitterpfavourite==2) { echo '<a href="http://twitter.com/intent/favorite?related='.$screenname.'&tweet_id='.$req_tweetid.'" class="dt-twitter-button-favourite" rel="external" target="_blank" style="float:right;width:26px;height:26px;text-indent:-9999px;background:url(\''.plugins_url('images/icons.png', __FILE__).'\') top left no-repeat;">Favourite</a>'; }
				//if ($twitterpretweet==2) { echo '<a href="http://twitter.com/intent/retweet?related='.$screenname.'&tweet_id='.$req_tweetid.'" class="dt-twitter-button-retweet" rel="external" target="_blank" style="float:right;">Retweet</a>'; }
				//if ($twitterpreply==2) { echo '<a href="http://twitter.com/intent/tweet?related='.$screenname.'&in_reply_to='.$req_tweetid.'" class="dt-twitter-button-reply" rel="external" target="_blank" style="float:right;" >Reply</a>'; }

				//If Icons Selected - Add Clear Floats
				//if ($twitterpfavourite==2 || $twitterpretweet==2 || $twitterpreply==2) { echo '<div style="clear:both;"></div>'; }
				
				//Close Container
				echo '</div>';
			}

		//End If Automatic Styling Is Set To Yes
		}
		
		//Close List Element
		 echo "</li>";
		
		//Reset Oddity Counter If We Are On 1
		if ($oddity==2) { $oddity=0; }
		
	}
	
	//Close Unordered List
	echo "</ul>";
	
	//If We Have A Follow Text Option
	if ($twitterfollow==1) { echo '<div class="dt-twitter-p-container"><a href="http://twitter.com/'.$screenname.'" class="dt-twitter-button" rel="nofollow">Follow @'.$screenname.'</a></div>'; } 
	
	//If We Have A Follow Button Option
	if ($twitterfollow==2) { echo '<a href="https://twitter.com/'.$screenname.'" class="twitter-follow-button" data-show-count="false" data-show-screen-name="false" data-dnt="true">Follow</a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?\'http\':\'https\';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+\'://platform.twitter.com/widgets.js\';fjs.parentNode.insertBefore(js,fjs);}}(document, \'script\', \'twitter-wjs\');</script>'; }
	
//End Display Function	
}


/////////////////////////////////////////////////////////////
///// Digicution Simple Twitter Feed Convert URLs (New) /////
/////////////////////////////////////////////////////////////

function dt_convert_urls($text) {
    $text= preg_replace("/(^|[\n ])([\w]*?)((ht|f)tp(s)?:\/\/[\w]+[^ \,\"\n\r\t<]*)/is", "$1$2<a href=\"$3\" target=\"_blank\">$3</a>", $text);  
    $text= preg_replace("/(^|[\n ])([\w]*?)((www|ftp)\.[^ \,\"\t\n\r<]*)/is", "$1$2<a href=\"http://$3\" target=\"_blank\">$3</a>", $text);  
    $text= preg_replace("/(^|[\n ])([a-z0-9&\-_\.]+?)@([\w\-]+\.([\w\-\.]+)+)/i", "$1<a href=\"mailto:$2@$3\" target=\"_blank\">$2@$3</a>", $text);  
    return($text);  
}  							
?>