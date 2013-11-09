<?php
////////////////////////////////////////////////////////
///// Digicution Simple Twitter Feed Settings Page /////
////////////////////////////////////////////////////////

function dt_admin() {

	///////////////////////////////////
	////// Define Main Variables //////
	///////////////////////////////////

	//Define Wordpress Conn As Global
	global $wpdb;
	
	//Define Tables
	$table_dt_twitter=$wpdb->prefix."dt_twitter";
		
	////////////////////////////////////////
	/////  Application Settings Code  //////
	////////////////////////////////////////
		
	//If General Settings Form Submitted
	if ($_POST['option']=='applicationupdate') {
	
		//Set Tab So We Load Correct Page
		$tab="application";
				
		//Grab Form Values
		$dt_twitter_oauth_access_token=dtCrypt('e',$_POST['dt_twitter_oauth_access_token']);
		$dt_twitter_oauth_access_token_secret=dtCrypt('e',$_POST['dt_twitter_oauth_access_token_secret']);
		$dt_twitter_consumer_key=dtCrypt('e',$_POST['dt_twitter_consumer_key']);
		$dt_twitter_consumer_secret=dtCrypt('e',$_POST['dt_twitter_consumer_secret']);
			
		//Clear General Message Session
		$_SESSION['application_message']='';
		
		//Set Update Flag Variable
		$dt_twitter_update=0;
		
		//Update Access Token Or Add Error Message
		if (!$dt_twitter_oauth_access_token) { $_SESSION['application_message'].="Please Enter The Twitter Application Access Token<br/>"; } else {	if(dtCrypt('d',get_option('dt_twitter_oauth_access_token'))!=$dt_twitter_oauth_access_token) { $dt_twitter_update=1; } update_option('dt_twitter_oauth_access_token',$dt_twitter_oauth_access_token); }
		
		//Update Access Token Secret Or Add Error Message
		if (!$dt_twitter_oauth_access_token_secret) { $_SESSION['application_message'].="Please Enter The Twitter Application Access Token Secret<br/>"; } else { if(dtCrypt('d',get_option('dt_twitter_oauth_access_token_secret'))!=$dt_twitter_oauth_access_token_secret) { $dt_twitter_update=1; } update_option('dt_twitter_oauth_access_token_secret',$dt_twitter_oauth_access_token_secret); }

		//Update Consumer Key Or Add Error Message
		if (!$dt_twitter_consumer_key) { $_SESSION['application_message'].="Please Enter The Twitter Application Consumer Key<br/>"; } else { if(dtCrypt('d',get_option('dt_twitter_consumer_key'))!=$dt_twitter_consumer_key) { $dt_twitter_update=1; } update_option('dt_twitter_consumer_key',$dt_twitter_consumer_key); }

		//Update Consumer Secret Or Add Error Message
		if (!$dt_twitter_consumer_secret) { $_SESSION['application_message'].="Please Enter The Twitter Application Consumer Secret<br/>"; } else {	if(dtCrypt('d',get_option('dt_twitter_consumer_secret'))!=$dt_twitter_consumer_secret) { $dt_twitter_update=1; } update_option('dt_twitter_consumer_secret',$dt_twitter_consumer_secret); }

		//If No Errors
		if(!$_SESSION['application_message']) { $_SESSION['application_success']="The Options Were Successfully Updated"; }
		
		//If Update Flag Set - Update Database
		if ($dt_twitter_update==1) { dt_twitter_update(); }
	
	//End General Settings Function	
	}

	///////////////////////////////////
	///// General Settings Code  //////
	///////////////////////////////////
		
	//If General Settings Form Submitted
	if ($_POST['option']=='generalupdate') {
	
		//Set Tab So We Load Correct Page
		$tab="general";
				
		//Grab Form Values
		$dt_twitter_screenname=$_POST['dt_twitter_screenname'];
		$dt_twitter_tweetsize=$_POST['dt_twitter_tweetsize'];
		$dt_twitter_twitterupdate=$_POST['dt_twitter_twitterupdate'];
		$dt_twitter_images=$_POST['dt_twitter_images'];
		$dt_twitter_retweet=$_POST['dt_twitter_retweet'];
		$dt_twitter_follow=$_POST['dt_twitter_follow'];
		$dt_twitter_hashtag_convert=$_POST['dt_twitter_hashtag_convert'];
		$dt_twitter_username_convert=$_POST['dt_twitter_username_convert'];
		$dt_twitter_post_expand=$_POST['dt_twitter_post_expand'];	
		$dt_twitter_post_reply=$_POST['dt_twitter_post_reply'];
		$dt_twitter_post_retweet=$_POST['dt_twitter_post_retweet'];
		$dt_twitter_post_favourite=$_POST['dt_twitter_post_favourite'];
		$dt_twitter_screenname_display=$_POST['dt_twitter_screenname_display'];
		$dt_twitter_fullname_display=$_POST['dt_twitter_fullname_display'];
		$dt_twitter_readdate_display=$_POST['dt_twitter_readdate_display'];
		$dt_twitter_header_display=$_POST['dt_twitter_header_display'];
		$dt_twitter_header_title=$_POST['dt_twitter_header_title'];
		$dt_twitter_header_follow=$_POST['dt_twitter_header_follow'];
		
		//Clear General Message Session
		$_SESSION['general_message']='';
		
		//Set Update Flag Variable
		$dt_twitter_update=0;
		
		//Update Screen Name Or Add Error Message
		if (!$dt_twitter_screenname) { $_SESSION['general_message'].="Please Enter The Twitter Username<br/>"; } else {	if(get_option('dt_twitter_screenname')!=$dt_twitter_screenname) { $dt_twitter_update=1; } update_option('dt_twitter_screenname',$dt_twitter_screenname); }
		
		//Update Tweet Size Or Add Error Message
		if (!$dt_twitter_tweetsize) { $_SESSION['general_message'].="Please Enter The Number Of Tweets To Display<br/>"; } elseif (!is_numeric($dt_twitter_tweetsize)) { $_SESSION['general_message'].="Please Enter A Numeric Value For Number Of Tweets To Display<br/>"; } else { if(get_option('dt_twitter_tweetsize')!=$dt_twitter_tweetsize) { $dt_twitter_update=1; } update_option('dt_twitter_tweetsize',$dt_twitter_tweetsize); }
				
		//Update Retweet Option
		if(get_option('dt_twitter_retweet')!=$dt_twitter_retweet) { $dt_twitter_update=1; } update_option('dt_twitter_retweet',$dt_twitter_retweet);
		 		
		//Update Select Options
		update_option('dt_twitter_twitterupdate',$dt_twitter_twitterupdate);
		update_option('dt_twitter_images',$dt_twitter_images);
		update_option('dt_twitter_follow',$dt_twitter_follow);
		update_option('dt_twitter_post_expand',$dt_twitter_post_expand);	
		update_option('dt_twitter_post_reply',$dt_twitter_post_reply);	
		update_option('dt_twitter_post_retweet',$dt_twitter_post_retweet);	
		update_option('dt_twitter_post_favourite',$dt_twitter_post_favourite);	
		update_option('dt_twitter_screenname_display',$dt_twitter_screenname_display);	
		update_option('dt_twitter_fullname_display',$dt_twitter_fullname_display);	
		update_option('dt_twitter_readdate_display',$dt_twitter_readdate_display);	
		update_option('dt_twitter_header_display',$dt_twitter_header_display);	
		update_option('dt_twitter_header_title',$dt_twitter_header_title);	
		update_option('dt_twitter_header_follow',$dt_twitter_header_follow);
		update_option('dt_twitter_hashtag_convert',$dt_twitter_hashtag_convert);
		update_option('dt_twitter_username_convert',$dt_twitter_username_convert);	
		
		//If No Errors
		if(!$_SESSION['general_message']) { $_SESSION['general_success']="The Options Were Successfully Updated"; }
		
		//If Update Flag Set - Update Database
		if ($dt_twitter_update==1) { dt_twitter_update(); }
	
	//End General Settings Function	
	}
	
	///////////////////////////////////
	///// Display Settings Code  //////
	///////////////////////////////////
		
	//If Display Settings Form Submitted
	if ($_POST['option']=='displayupdate') {
	
		//Set Tab So We Load Correct Page
		$tab="display";
				
		//Grab Form Values
		$dt_twitter_display_auto=$_POST['dt_twitter_display_auto'];
		$dt_twitter_display_mcwidth=$_POST['dt_twitter_display_mcwidth'];
		$dt_twitter_display_mcwidth_unit=$_POST['dt_twitter_display_mcwidth_unit'];
		$dt_twitter_display_mcbg=$_POST['dt_twitter_display_mcbg'];
		$dt_twitter_display_mcbg_enabled=$_POST['dt_twitter_display_mcbg_enabled'];
		$dt_twitter_display_mcpadding=$_POST['dt_twitter_display_mcpadding'];
		$dt_twitter_display_mcpadding_unit=$_POST['dt_twitter_display_mcpadding_unit'];
		$dt_twitter_display_mcmargintop=$_POST['dt_twitter_display_mcmargintop'];
		$dt_twitter_display_mcmargintop_unit=$_POST['dt_twitter_display_mcmargintop_unit'];
		$dt_twitter_display_mcmarginbottom=$_POST['dt_twitter_display_mcmarginbottom'];
		$dt_twitter_display_mcmarginbottom_unit=$_POST['dt_twitter_display_mcmarginbottom_unit'];
		$dt_twitter_display_mcmarginleft=$_POST['dt_twitter_display_mcmarginleft'];
		$dt_twitter_display_mcmarginleft_unit=$_POST['dt_twitter_display_mcmarginleft_unit'];
		$dt_twitter_display_mcmarginright=$_POST['dt_twitter_display_mcmarginright'];
		$dt_twitter_display_mcmarginright_unit=$_POST['dt_twitter_display_mcmarginright_unit'];
		$dt_twitter_display_fontsize=$_POST['dt_twitter_display_fontsize'];
		$dt_twitter_display_fontsize_unit=$_POST['dt_twitter_display_fontsize_unit'];
		$dt_twitter_display_fontcolor=$_POST['dt_twitter_display_fontcolor'];
		$dt_twitter_display_linkcolor=$_POST['dt_twitter_display_linkcolor'];
		$dt_twitter_display_tweetbg=$_POST['dt_twitter_display_tweetbg'];
		$dt_twitter_display_tweetbg_enabled=$_POST['dt_twitter_display_tweetbg_enabled'];
		$dt_twitter_display_tweetbgalt=$_POST['dt_twitter_display_tweetbgalt'];
		$dt_twitter_display_tweetbgalt_enabled=$_POST['dt_twitter_display_tweetbgalt_enabled'];
		$dt_twitter_display_tweetmargintop=$_POST['dt_twitter_display_tweetmargintop'];
		$dt_twitter_display_tweetmargintop_unit=$_POST['dt_twitter_display_tweetmargintop_unit'];
		$dt_twitter_display_tweetmarginbottom=$_POST['dt_twitter_display_tweetmarginbottom'];
		$dt_twitter_display_tweetmarginbottom_unit=$_POST['dt_twitter_display_tweetmarginbottom_unit'];
		$dt_twitter_display_tweetmarginleft=$_POST['dt_twitter_display_tweetmarginleft'];
		$dt_twitter_display_tweetmarginleft_unit=$_POST['dt_twitter_display_tweetmarginleft_unit'];
		$dt_twitter_display_tweetmarginright=$_POST['dt_twitter_display_tweetmarginright'];
		$dt_twitter_display_tweetmarginright_unit=$_POST['dt_twitter_display_tweetmarginright_unit'];
		$dt_twitter_display_tweetpaddingtop=$_POST['dt_twitter_display_tweetpaddingtop'];
		$dt_twitter_display_tweetpaddingtop_unit=$_POST['dt_twitter_display_tweetpaddingtop_unit'];
		$dt_twitter_display_tweetpaddingbottom=$_POST['dt_twitter_display_tweetpaddingbottom'];
		$dt_twitter_display_tweetpaddingbottom_unit=$_POST['dt_twitter_display_tweetpaddingbottom_unit'];
		$dt_twitter_display_tweetpaddingleft=$_POST['dt_twitter_display_tweetpaddingleft'];
		$dt_twitter_display_tweetpaddingleft_unit=$_POST['dt_twitter_display_tweetpaddingleft_unit'];
		$dt_twitter_display_tweetpaddingright=$_POST['dt_twitter_display_tweetpaddingright'];
		$dt_twitter_display_tweetpaddingright_unit=$_POST['dt_twitter_display_tweetpaddingright_unit'];
		$dt_twitter_image_size=$_POST['dt_twitter_image_size'];
		$dt_twitter_image_marginright=$_POST['dt_twitter_image_marginright'];
		$dt_twitter_image_marginright_unit=$_POST['dt_twitter_image_marginright_unit'];
		$dt_twitter_image_marginbottom=$_POST['dt_twitter_image_marginbottom'];
		$dt_twitter_image_marginbottom_unit=$_POST['dt_twitter_image_marginbottom_unit'];
				
		//Clear General Message Session
		$_SESSION['display_message']='';
		
		//Update Main Container Width Or Add Error Message
		if(!$dt_twitter_display_mcwidth) { $_SESSION['display_message'].="Please Enter The Width Of The Main Container<br/>"; } elseif (!is_numeric($dt_twitter_display_mcwidth)) { $_SESSION['display_message'].="Please Enter A Numeric Value For Main Container Width<br/>"; } else { update_option('dt_twitter_display_mcwidth',$dt_twitter_display_mcwidth); }
		
		//Update Main Container Width Or Add Error Message
		if(!$dt_twitter_display_fontsize) { $_SESSION['display_message'].="Please Enter The Tweet Font Size<br/>"; } elseif (!is_numeric($dt_twitter_display_fontsize)) { $_SESSION['display_message'].="Please Enter A Numeric Value For The Tweet Font Size<br/>"; } else { update_option('dt_twitter_display_fontsize',$dt_twitter_display_fontsize); }
		
		//If Margin & Padding Values Are Empty - Set Them To Zero
		if(!$dt_twitter_display_mcpadding) { $dt_twitter_display_mcpadding=0; }
		if(!$dt_twitter_display_mcmargintop) { $dt_twitter_display_mcmargintop=0; }
		if(!$dt_twitter_display_mcmarginbottom) { $dt_twitter_display_mcmarginbottom=0; }
		if(!$dt_twitter_display_mcmarginleft) { $dt_twitter_display_mcmarginleft=0; }
		if(!$dt_twitter_display_mcmarginright) { $dt_twitter_display_mcmarginright=0; }
		if(!$dt_twitter_display_tweetmargintop) { $dt_twitter_display_tweetmargintop=0; }
		if(!$dt_twitter_display_tweetmarginbottom) { $dt_twitter_display_tweetmarginbottom=0; }
		if(!$dt_twitter_display_tweetmarginleft) { $dt_twitter_display_tweetmarginleft=0; }
		if(!$dt_twitter_display_tweetmarginright) { $dt_twitter_display_tweetmarginright=0; }
		if(!$dt_twitter_display_tweetpaddingtop) { $dt_twitter_display_tweetpaddingtop=0; }
		if(!$dt_twitter_display_tweetpaddingbottom) { $dt_twitter_display_tweetpaddingbottom=0; }
		if(!$dt_twitter_display_tweetpaddingleft) { $dt_twitter_display_tweetpaddingleft=0; }
		if(!$dt_twitter_display_tweetpaddingright) { $dt_twitter_display_tweetpaddingright=0; }
		if(!$dt_twitter_image_marginright) { $dt_twitter_image_marginright=0; }
		if(!$dt_twitter_image_marginbottom) { $dt_twitter_image_marginbottom=0; }

		//Check Padding / Margin Values Are Numeric - If So, Update Option Or Add Error Message
		if (!is_numeric($dt_twitter_display_mcpadding)) { $_SESSION['display_message'].="Please Enter A Numeric Value For The Main Container Padding<br/>"; } else { update_option('dt_twitter_display_mcpadding',$dt_twitter_display_mcpadding); }
		if (!is_numeric($dt_twitter_display_mcmargintop)) { $_SESSION['display_message'].="Please Enter A Numeric Value For The Top Main Container Margin<br/>"; } else { update_option('dt_twitter_display_mcmargintop',$dt_twitter_display_mcmargintop); }
		if (!is_numeric($dt_twitter_display_mcmarginbottom)) { $_SESSION['display_message'].="Please Enter A Numeric Value For The Bottom Main Container Margin<br/>"; } else { update_option('dt_twitter_display_mcmarginbottom',$dt_twitter_display_mcmarginbottom); }
		if (!is_numeric($dt_twitter_display_mcmarginleft)) { $_SESSION['display_message'].="Please Enter A Numeric Value For The Left Main Container Margin<br/>"; } else { update_option('dt_twitter_display_mcmarginleft',$dt_twitter_display_mcmarginleft); }
		if (!is_numeric($dt_twitter_display_mcmarginright)) { $_SESSION['display_message'].="Please Enter A Numeric Value For The Right Main Container Margin<br/>"; } else { update_option('dt_twitter_display_mcmarginright',$dt_twitter_display_mcmarginright); }
		if (!is_numeric($dt_twitter_display_tweetmargintop)) { $_SESSION['display_message'].="Please Enter A Numeric Value For The Top Tweet Margin<br/>"; } else { update_option('dt_twitter_display_tweetmargintop',$dt_twitter_display_tweetmargintop); }
		if (!is_numeric($dt_twitter_display_tweetmarginbottom)) { $_SESSION['display_message'].="Please Enter A Numeric Value For The Bottom Tweet Margin<br/>"; } else { update_option('dt_twitter_display_tweetmarginbottom',$dt_twitter_display_tweetmarginbottom); }
		if (!is_numeric($dt_twitter_display_tweetmarginleft)) { $_SESSION['display_message'].="Please Enter A Numeric Value For The Left Tweet Margin<br/>"; } else { update_option('dt_twitter_display_tweetmarginleft',$dt_twitter_display_tweetmarginleft); }
		if (!is_numeric($dt_twitter_display_tweetmarginright)) { $_SESSION['display_message'].="Please Enter A Numeric Value For The Right Tweet Margin<br/>"; } else { update_option('dt_twitter_display_tweetmarginright',$dt_twitter_display_tweetmarginright); }
		if (!is_numeric($dt_twitter_display_tweetpaddingtop)) { $_SESSION['display_message'].="Please Enter A Numeric Value For The Top Tweet Padding<br/>"; } else { update_option('dt_twitter_display_tweetpaddingtop',$dt_twitter_display_tweetpaddingtop); }
		if (!is_numeric($dt_twitter_display_tweetpaddingbottom)) { $_SESSION['display_message'].="Please Enter A Numeric Value For The Bottom Tweet Padding<br/>"; } else { update_option('dt_twitter_display_tweetpaddingbottom',$dt_twitter_display_tweetpaddingbottom); }
		if (!is_numeric($dt_twitter_display_tweetpaddingleft)) { $_SESSION['display_message'].="Please Enter A Numeric Value For The Left Tweet Padding<br/>"; } else { update_option('dt_twitter_display_tweetpaddingleft',$dt_twitter_display_tweetpaddingleft); }
		if (!is_numeric($dt_twitter_display_tweetpaddingright)) { $_SESSION['display_message'].="Please Enter A Numeric Value For The Right Tweet Padding<br/>"; } else { update_option('dt_twitter_display_tweetpaddingright',$dt_twitter_display_tweetpaddingright); }
		if (!is_numeric($dt_twitter_image_marginright)) { $_SESSION['display_message'].="Please Enter A Numeric Value For The Right Image Margin<br/>"; } else { update_option('dt_twitter_image_marginright',$dt_twitter_image_marginright); }
		if (!is_numeric($dt_twitter_image_marginbottom)) { $_SESSION['display_message'].="Please Enter A Numeric Value For The Bottom Image Margin<br/>"; } else { update_option('dt_twitter_image_marginbottom',$dt_twitter_image_marginbottom); }
		
		//Update Other Select Options
		update_option('dt_twitter_display_auto',$dt_twitter_display_auto);
		update_option('dt_twitter_display_mcwidth_unit',$dt_twitter_display_mcwidth_unit);
		update_option('dt_twitter_display_mcbg',$dt_twitter_display_mcbg);
		update_option('dt_twitter_display_mcbg_enabled',$dt_twitter_display_mcbg_enabled);
		update_option('dt_twitter_display_mcpadding_unit',$dt_twitter_display_mcpadding_unit);
		update_option('dt_twitter_display_mcmargintop_unit',$dt_twitter_display_mcmargintop_unit);
		update_option('dt_twitter_display_mcmarginbottom_unit',$dt_twitter_display_mcmarginbottom_unit);
		update_option('dt_twitter_display_mcmarginleft_unit',$dt_twitter_display_mcmarginleft_unit);
		update_option('dt_twitter_display_mcmarginright_unit',$dt_twitter_display_mcmarginright_unit);
		update_option('dt_twitter_display_fontsize_unit',$dt_twitter_display_fontsize_unit);
		update_option('dt_twitter_display_fontcolor',$dt_twitter_display_fontcolor);
		update_option('dt_twitter_display_linkcolor',$dt_twitter_display_linkcolor);
		update_option('dt_twitter_display_tweetbg',$dt_twitter_display_tweetbg);
		update_option('dt_twitter_display_tweetbg_enabled',$dt_twitter_display_tweetbg_enabled);
		update_option('dt_twitter_display_tweetbgalt',$dt_twitter_display_tweetbgalt);
		update_option('dt_twitter_display_tweetbgalt_enabled',$dt_twitter_display_tweetbgalt_enabled);
		update_option('dt_twitter_image_size',$dt_twitter_image_size);
		update_option('dt_twitter_image_marginright_unit',$dt_twitter_image_marginright_unit);
		update_option('dt_twitter_image_marginbottom_unit',$dt_twitter_image_marginbottom_unit);
		
		//If No Errors
		if(!$_SESSION['display_message']) { $_SESSION['display_success']="The Display Options Were Successfully Updated"; }
		
	//End Display Settings Function		
	}
	?>
		
	<div class="wrap dt">
	
		<h2 class="dt_header">Digicution Simple Twitter Feed<div class="request">Found a bug or have a feature request?&nbsp;&nbsp;<a class="button-primary dtbutton" href="http://www.digicution.com/contact/" target="_blank" name="featurebug"/>Click Here</a></div></h2>
		
		<div id="dt" class="main">
				
			<div id="dt-admin-form-container">		
						
				<div id="dt-tabbed-area" class="rounded">
							
					<div class="pane-content">
		
		
						<div class="left-area">
							<ul>
								<?php
								//Only Run Update If CURL Exists
								if (function_exists('curl_init')) { 
								
									//Get OAuth Authentication Details (Twitter API V1.1)
									$dt_twitter_oauth_access_token=get_option('dt_twitter_oauth_access_token');
									$dt_twitter_oauth_access_token_secret=get_option('dt_twitter_oauth_access_token_secret');
									$dt_twitter_consumer_key=get_option('dt_twitter_consumer_key');
									$dt_twitter_consumer_secret=get_option('dt_twitter_consumer_secret');
									
									//Set Flag For Body
									$oauthdetails=0;
									?>
									<li><a id="tab-section-application" rel="application" href="#" <?php if (!$tab || $tab=="application") {?>class="active"<?php } ?>>Twitter Application Settings</a></li>
									<?php
									//If We Have All Required Tokens & Keys
									if($dt_twitter_oauth_access_token && $dt_twitter_oauth_access_token_secret && $dt_twitter_consumer_key && $dt_twitter_consumer_secret) {
									
										//Set Flag For Body
										$oauthdetails=1;
										?>
										<li><a id="tab-section-general" rel="general" href="#" <?php if ($tab=="general") {?>class="active"<?php } ?>>General Settings</a></li>
										<li><a id="tab-section-display" rel="display" href="#" <?php if ($tab=="display") {?>class="active"<?php } ?>>Automatic Styling</a></li>
										<li><a id="tab-section-manual" rel="manual" href="#" <?php if ($tab=="manual") {?>class="active"<?php } ?>>Manual Styling</a></li>
										<li><a id="tab-section-integrate" rel="integrate" href="#" <?php if ($tab=="integrate") {?>class="active"<?php } ?>>How To Integrate</a></li>
									<?php
									//Otherwise - Disable These Until Details Completed
									} else {
									?>
										<li><span class="inactive">General Settings</span></li>
										<li><span class="inactive">Automatic Styling</span></li>
										<li><span class="inactive">Manual Styling</span></li>
										<li><span class="inactive">How To Integrate</span></li>
									<?php
									//End If We Have OAuth Access Details
									}
										
								//Otherwise - CURL Not Available
								} else {
									?>
									<li><a id="tab-section-application" rel="application" href="#" <?php if (!$tab || $tab=="application") {?>class="active"<?php } ?>>Error: cURL Not Available</a></li>
								<?php
								//End CURL Check
								}
								?>
							</ul>
						</div>
				
						<div class="right-area">									
									
									
							<?php
							//Only Run Update If CURL Exists
							if (function_exists('curl_init')) { 
							?>
		
							<div <?php if (!$tab || $tab=="application") {?>style="display: block;"<?php } else { ?>style="display: none;"<?php } ?> class="setting-right-section" id="setting-application">
														
								<div class="dt-setting">
								
									<?php if (($_SESSION['application_message']!="") || ($_SESSION['application_success']!="")) {?>
									
									<div class="dt-setting">
										<div>
										<fieldset class="rounded">
										<?php if ($_SESSION['application_message']!="") { ?><legend class="validation">Validation Error</legend><?php } else {?><legend class="validation">Success</legend><?php } ?>
										<div class="dt-ad-section-settings"></div>
										<div class="dt-setting type-text validation" id="setting_site_title">
											<?php echo $_SESSION['application_message']; ?><?php echo $_SESSION['application_success']; ?>
										</div>
										<div class="dt-setting type-section-end"></div>
										</fieldset>
										</div>
									</div>								
									
									<?php $_SESSION['application_message']=""; $_SESSION['application_success']=""; } ?>
										
									<form action="?page=dt_setting" method="post" id="dt_twitter-plugin_options_form_application" name="dt_twitter-plugin_options_form_application">
										
									<div class="dt-setting">	
										<fieldset class="rounded">
										<legend>Twitter Application Settings</legend>
	
											<div class="dt-setting type-text" id="setting_site_title">
														
												<?php
												//If We Have All Required Tokens & Keys
												if($oauthdetails==0) {
												?>
												
												<div class="bottomgap">
												<p>In order to use this plugin, you must first create a Twitter application so that you can use the OAuth authentication techniques required for this to function.<br/><br/>Head to <a href="https://dev.twitter.com/" target="_blank" />https://dev.twitter.com/</a> to create a Twitter Application and then simply enter the Application details in the fields below.<br/><br/>Once you've done this, the plugin will become fully active.</p>
												</div>
												
												<br/><br/><hr/><br/><br/>
												
												<?php 
												//End If We Don't Have Details
												}
												?>
												
												<div class="inputholder bottomgap">
												<label for="dt_twitter_oauth_access_token">Access Token:</label>
												<p class="labeldesc">Please Enter Your Twitter Application Access Token</p>
												<input id="dt_twitter_oauth_access_token" type="text" size="36" name="dt_twitter_oauth_access_token" value="<?php echo dtCrypt('d',get_option('dt_twitter_oauth_access_token')); ?>" />
												</div>
												
												<div class="inputholder bottomgap">
												<label for="dt_twitter_oauth_access_token_secret">Access Token Secret:</label>
												<p class="labeldesc">Please Enter Your Twitter Application Access Token Secret</p>
												<input id="dt_twitter_oauth_access_token_secret" type="text" size="36" name="dt_twitter_oauth_access_token_secret" value="<?php echo dtCrypt('d',get_option('dt_twitter_oauth_access_token_secret')); ?>" />
												</div>
												
												<div class="inputholder bottomgap">
												<label for="dt_twitter_consumer_key">Consumer Key:</label>
												<p class="labeldesc">Please Enter Your Twitter Application Consumer Key</p>
												<input id="dt_twitter_consumer_key" type="text" size="36" name="dt_twitter_consumer_key" value="<?php echo dtCrypt('d',get_option('dt_twitter_consumer_key')); ?>" />
												</div>
												
												<div class="inputholder bottomgap">
												<label for="dt_twitter_consumer_secret">Consumer Secret:</label>
												<p class="labeldesc">Please Enter Your Twitter Application Consumer Secret</p>
												<input id="dt_twitter_consumer_secret" type="text" size="36" name="dt_twitter_consumer_secret" value="<?php echo dtCrypt('d',get_option('dt_twitter_consumer_secret')); ?>" />
												</div>
												
											</div>
											
										</fieldset>
									</div>
									
									<div class="dt-setting"></div>
									<input type="hidden" name="option" value="applicationupdate" />
									<input class="button-primary dtbutton" type="submit" name="submit" tabindex="1" value="Update Application Options" />
										
									</form>
																			
								</div>	
								
							</div>	
									
							<?php
							//Otherwise - CURL Not Available
							} else {
							?>
									
							<div <?php if (!$tab || $tab=="application") {?>style="display: block;"<?php } else { ?>style="display: none;"<?php } ?> class="setting-right-section" id="setting-application">
														
								<div class="dt-setting">
										
									<div class="dt-setting">	
										<fieldset class="rounded">
										<legend>Error: cURL Is Not Available</legend>
	
											<div class="dt-setting type-text" id="setting_site_title">
												
												<div class="bottomgap">
												<p>Unfortunately, due to the changes made to the Twitter API on June 11th 2013 when it was upgraded to Version 1.1, all Twitter requests require OAuth authorisation.<br/><br/>This application requires PHP's built in cURL functions in order to retrieve your Tweets from Twitter and unfortunately, the server that your Wordpress blog is hosted on does not seem to have this functionality enabled.<br/><br/>Please speak to your server administrator and get them to enable cURL on your installation in order to use this plugin.</p>
												</div>
												
											</div>
											
										</fieldset>
									</div>
																			
								</div>	
								
							</div>	
									
							<?php
							//End CURL Check
							}
							?>
										
							<div <?php if ($tab=="general") {?>style="display: block;"<?php } else { ?>style="display: none;"<?php } ?> class="setting-right-section" id="setting-general">
														
								<div class="dt-setting">
								
									<?php if (($_SESSION['general_message']!="") || ($_SESSION['general_success']!="")) {?>
									
									<div class="dt-setting">
										<div>
										<fieldset class="rounded">
										<?php if ($_SESSION['general_message']!="") { ?><legend class="validation">Validation Error</legend><?php } else {?><legend class="validation">Success</legend><?php } ?>
										<div class="dt-ad-section-settings"></div>
										<div class="dt-setting type-text validation" id="setting_site_title">
											<?php echo $_SESSION['general_message']; ?><?php echo $_SESSION['general_success']; ?>
										</div>
										<div class="dt-setting type-section-end"></div>
										</fieldset>
										</div>
									</div>								
									
									<?php $_SESSION['general_message']=""; $_SESSION['general_success']=""; } ?>
										
									<form action="?page=dt_setting" method="post" id="dt_twitter-plugin_options_form_general" name="dt_twitter-plugin_options_form_general">
										
									<div class="dt-setting">	
										<fieldset class="rounded">
										<legend>General Settings</legend>
	
											<div class="dt-setting type-text" id="setting_site_title">
												
												<div class="inputholder bottomgap">
												<label for="dt_twitter_screenname">Twitter Username:</label>
												<p class="labeldesc">Please Enter Your Twitter Username / Screen Name</p>
												<input id="dt_twitter_screenname" type="text" size="36" name="dt_twitter_screenname" value="<?php echo get_option('dt_twitter_screenname'); ?>" />
												</div>
												
												<div class="inputholder bottomgap">
												<label for="dt_twitter_tweetsize">Number Of Tweets To Display:</label>
												<p class="labeldesc">Please Indicate How Many Tweets You Would Like To Display In Your Twitter Feed</p>
												<input id="dt_twitter_tweetsize" type="text" size="36" name="dt_twitter_tweetsize" class="numberinput" value="<?php echo get_option('dt_twitter_tweetsize'); ?>" />
												</div>
												
												<div class="inputholder bottomgap">
												<label for="dt_twitter_twitterupdate">Twitter Update Frequency:</label>
												<p class="labeldesc">Please Select How Often You Would Like Your Twitter Feed To Update (Please Check The <a href="https://dev.twitter.com/docs/rate-limiting/1.1" target="_blank">Rate Limiting Documentation</a> Before Changing This Value)</p>
							                    <select name="dt_twitter_twitterupdate">
							                    <option value="3600"<?php if((get_option('dt_twitter_twitterupdate')==3600) || (!get_option('dt_twitter_twitterupdate'))) { echo ' selected="selected"'; } ?>>1 Hour</option>
							                    <option value="2700"<?php if(get_option('dt_twitter_twitterupdate')==2700) { echo ' selected="selected"'; } ?>>45 Minutes</option>
							                    <option value="1800"<?php if(get_option('dt_twitter_twitterupdate')==1800) { echo ' selected="selected"'; } ?>>30 Minutes</option>
							                    <option value="1500"<?php if(get_option('dt_twitter_twitterupdate')==1500) { echo ' selected="selected"'; } ?>>25 Minutes</option>
							                    <option value="1200"<?php if(get_option('dt_twitter_twitterupdate')==1200) { echo ' selected="selected"'; } ?>>20 Minutes</option>
							                    <option value="900"<?php if(get_option('dt_twitter_twitterupdate')==900) { echo ' selected="selected"'; } ?>>15 Minutes</option>
							                    <option value="600"<?php if(get_option('dt_twitter_twitterupdate')==600) { echo ' selected="selected"'; } ?>>10 Minutes</option>
							                    <option value="300"<?php if(get_option('dt_twitter_twitterupdate')==300) { echo ' selected="selected"'; } ?>>5 Minutes</option>
							                    </select>
												</div>
												
												<div class="inputholder bottomgap">
												<label for="dt_twitter_images">Display Profile Images:</label>
												<p class="labeldesc">Please Indicate Whether You Would Like To Display Profile Images Next To Each Tweet</p>
							                    <select name="dt_twitter_images">
							                    <option value="1"<?php if((get_option('dt_twitter_images')==1) || (!get_option('dt_twitter_images'))) { echo ' selected="selected"'; } ?>>Yes</option>
							                    <option value="0"<?php if(get_option('dt_twitter_images')==0) { echo ' selected="selected"'; } ?>>No</option>
							                    </select>
												</div>
												
												<div class="inputholder bottomgap">
												<label for="dt_twitter_retweet">Display Re-Tweets:</label>
												<p class="labeldesc">Please Indicate Whether You Would Like To Display Native Re-Tweets From Your Twitter Feed</p>
							                    <select name="dt_twitter_retweet">
							                    <option value="1"<?php if((get_option('dt_twitter_retweet')==1) || (!get_option('dt_twitter_retweet'))) { echo ' selected="selected"'; } ?>>Yes</option>
							                    <option value="0"<?php if(get_option('dt_twitter_retweet')==0) { echo ' selected="selected"'; } ?>>No</option>
							                    </select>
												</div>
												
												<div class="inputholder bottomgap">
												<label for="dt_twitter_follow">Display Follow Link After Tweets:</label>
												<p class="labeldesc">Please Indicate Whether You Would Like A Link To Your Twitter Profile At The Bottom Of The Feed</p>
							                    <select name="dt_twitter_follow">
							                    <option value="2"<?php if(get_option('dt_twitter_follow')==2) { echo ' selected="selected"'; } ?>>Follow Button</option>
							                    <option value="1"<?php if(get_option('dt_twitter_follow')==1) { echo ' selected="selected"'; } ?>>Text Link</option>
							                    <option value="0"<?php if((get_option('dt_twitter_follow')==0) || (!get_option('dt_twitter_follow'))) { echo ' selected="selected"'; } ?>>No</option>
							                    </select>
												</div>
												
												<div class="inputholder bottomgap">
												<label for="dt_twitter_hashtag_convert">Link Hashtags:</label>
												<p class="labeldesc">Please Indicate Whether You Would Like To Convert Twitter Hash Tags To Links</p>
							                    <select name="dt_twitter_hashtag_convert">
							                    <option value="1"<?php if((get_option('dt_twitter_hashtag_convert')==1) || (!get_option('dt_twitter_hashtag_convert'))) { echo ' selected="selected"'; } ?>>Yes</option>
							                    <option value="0"<?php if(get_option('dt_twitter_hashtag_convert')==0) { echo ' selected="selected"'; } ?>>No</option>
							                    </select>
												</div>
												
												<div class="inputholder">
												<label for="dt_twitter_username_convert">Link @usernames:</label>
												<p class="labeldesc">Please Indicate Whether You Would Like To Convert Twitter Usernames To Links</p>
							                    <select name="dt_twitter_username_convert">
							                    <option value="1"<?php if((get_option('dt_twitter_username_convert')==1) || (!get_option('dt_twitter_username_convert'))) { echo ' selected="selected"'; } ?>>Yes</option>
							                    <option value="0"<?php if(get_option('dt_twitter_username_convert')==0) { echo ' selected="selected"'; } ?>>No</option>
							                    </select>
												</div>
												
											</div>
											
										</fieldset>
									</div>
									
									<div class="dt-setting">	
										<fieldset class="rounded">
										<legend>Header Settings</legend>
	
											<div class="dt-setting type-text" id="setting_site_title">
												
												<div class="inputholder bottomgap">
												<label for="dt_twitter_header_display">Display Header:</label>
												<p class="labeldesc">Please Indicate Whether You Would Like To Display A Title Header For Your Tweets</p>
							                    <select name="dt_twitter_header_display">
							                    <option value="1"<?php if(get_option('dt_twitter_header_display')==1) { echo ' selected="selected"'; } ?>>Yes</option>
							                    <option value="0"<?php if((get_option('dt_twitter_header_display')==0) || (!get_option('dt_twitter_header_display'))) { echo ' selected="selected"'; } ?>>No</option>
							                    </select>
												</div>
												
												<div class="inputholder bottomgap">
												<label for="dt_twitter_header_title">Header Title:</label>
												<p class="labeldesc">Please Enter A Title For Your Header</p>
												<input id="dt_twitter_header_title" type="text" size="36" name="dt_twitter_header_title" value="<?php echo get_option('dt_twitter_header_title'); ?>" />
												</div>
												
												<div class="inputholder">
												<label for="dt_twitter_header_follow">Display Follow Link In Header:</label>
												<p class="labeldesc">Please Indicate Whether You Would Like A Link To Your Twitter Profile In Your Header</p>
							                    <select name="dt_twitter_header_follow">
							                    <option value="2"<?php if(get_option('dt_twitter_header_follow')==2) { echo ' selected="selected"'; } ?>>Follow Button</option>
							                    <option value="1"<?php if(get_option('dt_twitter_header_follow')==1) { echo ' selected="selected"'; } ?>>Text Link</option>
							                    <option value="0"<?php if((get_option('dt_twitter_header_follow')==0) || (!get_option('dt_twitter_header_follow'))) { echo ' selected="selected"'; } ?>>No</option>
							                    </select>
												</div>
												
											</div>
											
										</fieldset>
									</div>
									
									<div class="dt-setting">
										<fieldset class="rounded">
										<legend>Single Tweet Settings (What To Display For Each Tweet)</legend>
	
											<div class="dt-setting type-text" id="setting_site_title">
											
												<div class="inputholder bottomgap">
												<label for="dt_twitter_fullname_display">Display Full Name:</label>
												<p class="labeldesc">Please Indicate Whether You Would Like To Display Full Name At The Top Of Each Tweet</p>
							                    <select name="dt_twitter_fullname_display">
							                    <option value="1"<?php if(get_option('dt_twitter_fullname_display')==1) { echo ' selected="selected"'; } ?>>Yes</option>
							                    <option value="0"<?php if((get_option('dt_twitter_fullname_display')==0) || (!get_option('dt_twitter_fullname_display'))) { echo ' selected="selected"'; } ?>>No</option>
							                    </select>
												</div>
												
												<div class="inputholder bottomgap">
												<label for="dt_twitter_screenname_display">Display Screen Name:</label>
												<p class="labeldesc">Please Indicate Whether You Would Like To Display Screen Name At The Top Of Each Tweet</p>
							                    <select name="dt_twitter_screenname_display">
							                    <option value="1"<?php if(get_option('dt_twitter_screenname_display')==1) { echo ' selected="selected"'; } ?>>Yes</option>
							                    <option value="0"<?php if((get_option('dt_twitter_screenname_display')==0) || (!get_option('dt_twitter_screenname_display'))) { echo ' selected="selected"'; } ?>>No</option>
							                    </select>
												</div>
											
												<div class="inputholder bottomgap">
												<label for="dt_twitter_readdate_display">Display Tweet Date:</label>
												<p class="labeldesc">Please Indicate Whether You Would Like To Display The Approximate Time / Date Of Tweet</p>
							                    <select name="dt_twitter_readdate_display">
							                    <option value="2"<?php if(get_option('dt_twitter_readdate_display')==2) { echo ' selected="selected"'; } ?>>After Tweet</option>
							                    <option value="1"<?php if(get_option('dt_twitter_readdate_display')==1) { echo ' selected="selected"'; } ?>>Before Tweet</option>
							                    <option value="0"<?php if((get_option('dt_twitter_readdate_display')==0) || (!get_option('dt_twitter_readdate_display'))) { echo ' selected="selected"'; } ?>>No</option>
							                    </select>
												</div>

												<div class="inputholder bottomgap">
												<label for="dt_twitter_post_expand">Display Expand Tweet Option:</label>
												<p class="labeldesc">Please Indicate Whether You Would Like An Expand Tweet Option Link After Each Tweet</p>
							                    <select name="dt_twitter_post_expand">
							                    <?php /*<option value="2"<?php if(get_option('dt_twitter_post_expand')==2) { echo ' selected="selected"'; } ?>>Icon</option>*/ ?>
							                    <option value="1"<?php if(get_option('dt_twitter_post_expand')==1) { echo ' selected="selected"'; } ?>>Yes</option>
							                    <option value="0"<?php if((get_option('dt_twitter_post_expand')==0) || (!get_option('dt_twitter_post_expand'))) { echo ' selected="selected"'; } ?>>No</option>
							                    </select>
												</div>
												
												<div class="inputholder bottomgap">
												<label for="dt_twitter_post_reply">Display Reply To Tweet Option:</label>
												<p class="labeldesc">Please Indicate Whether You Would Like A Reply To Tweet Option Link After Each Tweet</p>
							                    <select name="dt_twitter_post_reply">
							                    <?php /*<option value="2"<?php if(get_option('dt_twitter_post_reply')==2) { echo ' selected="selected"'; } ?>>Icon</option>*/ ?>
							                    <option value="1"<?php if(get_option('dt_twitter_post_reply')==1) { echo ' selected="selected"'; } ?>>Yes</option>
							                    <option value="0"<?php if((get_option('dt_twitter_post_reply')==0) || (!get_option('dt_twitter_post_reply'))) { echo ' selected="selected"'; } ?>>No</option>
							                    </select>
												</div>
												
												<div class="inputholder bottomgap">
												<label for="dt_twitter_post_retweet">Display Re-Tweet Option:</label>
												<p class="labeldesc">Please Indicate Whether You Would Like A Re-Tweet Option Link After Each Tweet</p>
							                    <select name="dt_twitter_post_retweet">
							                    <?php /*<option value="2"<?php if(get_option('dt_twitter_post_retweet')==2) { echo ' selected="selected"'; } ?>>Icon</option>*/?>
							                    <option value="1"<?php if(get_option('dt_twitter_post_retweet')==1) { echo ' selected="selected"'; } ?>>Yes</option>
							                    <option value="0"<?php if((get_option('dt_twitter_post_retweet')==0) || (!get_option('dt_twitter_post_retweet'))) { echo ' selected="selected"'; } ?>>No</option>
							                    </select>
												</div>
												
												<div class="inputholder">
												<label for="dt_twitter_post_favourite">Display Favourite Option:</label>
												<p class="labeldesc">Please Indicate Whether You Would Like An Add To Favourites Option Link After Each Tweet</p>
							                    <select name="dt_twitter_post_favourite">
							                    <?php /*<option value="2"<?php if(get_option('dt_twitter_post_favourite')==2) { echo ' selected="selected"'; } ?>>Icon</option>*/?>
							                    <option value="1"<?php if(get_option('dt_twitter_post_favourite')==1) { echo ' selected="selected"'; } ?>>Yes</option>
							                    <option value="0"<?php if((get_option('dt_twitter_post_favourite')==0) || (!get_option('dt_twitter_post_favourite'))) { echo ' selected="selected"'; } ?>>No</option>
							                    </select>
												</div>
												
											</div>
											
										</fieldset>
									</div>
									
									<div class="dt-setting"></div>
									<input type="hidden" name="option" value="generalupdate" />
									<input class="button-primary dtbutton" type="submit" name="submit" tabindex="1" value="Update General Options" />
										
									</form>
																			
								</div>	
								
							</div>	
										
										
										
										
																							
							<div <?php if ($tab=="display") {?>style="display: block;"<?php } else { ?>style="display: none;"<?php } ?> class="setting-right-section" id="setting-display">
										
								<div class="dt-setting">
								
									<?php if (($_SESSION['display_message']!="") || ($_SESSION['display_success']!="")) {?>
									
									<div class="dt-setting">
										<div>
										<fieldset class="rounded">
										<?php if ($_SESSION['display_message']!="") { ?><legend class="validation">Validation Error</legend><?php } else {?><legend class="validation">Success</legend><?php } ?>
										<div class="dt-ad-section-settings"></div>
										<div class="dt-setting type-text validation" id="setting_site_title">
											<?php echo $_SESSION['display_message']; ?><?php echo $_SESSION['display_success']; ?>
										</div>
										<div class="dt-setting type-section-end"></div>
										</fieldset>
										</div>
									</div>								
									
									<?php $_SESSION['display_message']=""; $_SESSION['display_success']=""; } ?>
										
									<form action="?page=dt_setting" method="post" id="dt_twitter-plugin_options_form_display" name="dt_twitter-plugin_options_form_display">
										
									<div class="dt-setting">	
										<fieldset class="rounded">
										<legend>Automatic Styling Settings</legend>
	
											<div class="dt-setting type-text">
												
												<div class="inputholder">
												<label for="dt_twitter_display_auto">Use Automatic Styling:</label>
												<p class="labeldesc">Select Yes To Use The Settings On This Page For Your Twitter Feed</p>
												<p class="labeldesc">Select No To Disable All Styling So You Can Manually Style Your Twitter Feed Using CSS</p>
							                    <select name="dt_twitter_display_auto">
							                    <option value="1"<?php if((get_option('dt_twitter_display_auto')==1) || (!get_option('dt_twitter_display_auto'))) { echo ' selected="selected"'; } ?>>Yes - Use Automatic Styling</option>
							                    <option value="0"<?php if(get_option('dt_twitter_display_auto')==0) { echo ' selected="selected"'; } ?>>No - Use Manual Styling</option>
							                    </select>
												</div>
												
											</div>
											
										</fieldset>
									</div>
									
									<div class="dt-setting">
										<fieldset class="rounded">
										<legend>Main Container Settings</legend>
	
											<div class="dt-setting type-text">
											
												<div class="inputholder bottomgap">
												<label for="dt_twitter_display_mcwidth">Main Container Width:</label>
												<p class="labeldesc">Choose The Main Container Width For The Twitter Feed In Pixels / Percent</p>
												<input id="dt_twitter_display_mcwidth" type="text" size="36" name="dt_twitter_display_mcwidth" class="numberinput left" value="<?php echo get_option('dt_twitter_display_mcwidth'); ?>" />
							                    <select name="dt_twitter_display_mcwidth_unit" class="left">
							                    <option value="1"<?php if((get_option('dt_twitter_display_mcwidth_unit')==1) || (!get_option('dt_twitter_display_mcwidth_unit'))) { echo ' selected="selected"'; } ?>>Pixels</option>
							                    <option value="0"<?php if(get_option('dt_twitter_display_mcwidth_unit')==0) { echo ' selected="selected"'; } ?>>Percent</option>
							                    </select>
							                    <br class="clearer" />
												</div>
												
												<div class="inputholder bottomgap">
												<label for="dt_twitter_display_mcbg">Main Container BG Colour:</label>
												<p class="labeldesc">Choose The Main Container Background Colour Or Select Disabled To Make It Transparent</p>
												<input id="dt_twitter_display_mcbg" type="minicolors" size="36" name="dt_twitter_display_mcbg" class="left" <?php if (get_option('dt_twitter_display_mcbg_enabled')==0) { ?>disabled="disabled" value="No Background Colour"<?php } else { ?>value="<?php echo get_option('dt_twitter_display_mcbg'); ?>"<?php } ?> />
							                    <select name="dt_twitter_display_mcbg_enabled" rel="dt_twitter_display_mcbg" class="colorselector left">
							                    <option value="1"<?php if((get_option('dt_twitter_display_mcbg_enabled')==1) || (!get_option('dt_twitter_display_mcbg_enabled'))) { echo ' selected="selected"'; } ?>>Enabled</option>
							                    <option value="0"<?php if(get_option('dt_twitter_display_mcbg_enabled')==0) { echo ' selected="selected"'; } ?>>Disabled</option>
							                    </select>
							                    <br class="clearer" />
												</div>
												
												<div class="inputholder bottomgap">
												<label for="dt_twitter_display_mcpadding">Main Container Padding:</label>
												<p class="labeldesc">Choose The Padding For The Main Container (Spacing Between Main Container &amp; Tweets) In Pixels / Percent</p>
												<input id="dt_twitter_display_mcpadding" type="text" size="36" name="dt_twitter_display_mcpadding" class="numberinput left" value="<?php echo get_option('dt_twitter_display_mcpadding'); ?>" />
							                    <select name="dt_twitter_display_mcpadding_unit" class="left">
							                    <option value="1"<?php if((get_option('dt_twitter_display_mcpadding_unit')==1) || (!get_option('dt_twitter_display_mcpadding_unit'))) { echo ' selected="selected"'; } ?>>Pixels</option>
							                    <option value="0"<?php if(get_option('dt_twitter_display_mcpadding_unit')==0) { echo ' selected="selected"'; } ?>>Percent</option>
							                    </select>
							                    <br class="clearer" />
												</div>
												
												<div class="inputholder bottomgap">
												<label for="dt_twitter_display_mcmargintop">Main Container Top Margin:</label>
												<p class="labeldesc">Choose The Top Margin For The Main Container (Spacing Around The Top Of The Main Container) In Pixels / Percent</p>
												<input id="dt_twitter_display_mcmargintop" type="text" size="36" name="dt_twitter_display_mcmargintop" class="numberinput left" value="<?php echo get_option('dt_twitter_display_mcmargintop'); ?>" />
							                    <select name="dt_twitter_display_mcmargintop_unit" class="left">
							                    <option value="1"<?php if((get_option('dt_twitter_display_mcmargintop_unit')==1) || (!get_option('dt_twitter_display_mcmargintop_unit'))) { echo ' selected="selected"'; } ?>>Pixels</option>
							                    <option value="0"<?php if(get_option('dt_twitter_display_mcmargintop_unit')==0) { echo ' selected="selected"'; } ?>>Percent</option>
							                    </select>
							                    <br class="clearer" />
												</div>
												
												<div class="inputholder bottomgap">
												<label for="dt_twitter_display_mcmarginbottom">Main Container Bottom Margin:</label>
												<p class="labeldesc">Choose The Bottom Margin For The Main Container (Spacing Around The Bottom Of The Main Container) In Pixels / Percent</p>
												<input id="dt_twitter_display_mcmarginbottom" type="text" size="36" name="dt_twitter_display_mcmarginbottom" class="numberinput left" value="<?php echo get_option('dt_twitter_display_mcmarginbottom'); ?>" />
							                    <select name="dt_twitter_display_mcmarginbottom_unit" class="left">
							                    <option value="1"<?php if((get_option('dt_twitter_display_mcmarginbottom_unit')==1) || (!get_option('dt_twitter_display_mcmarginbottom_unit'))) { echo ' selected="selected"'; } ?>>Pixels</option>
							                    <option value="0"<?php if(get_option('dt_twitter_display_mcmarginbottom_unit')==0) { echo ' selected="selected"'; } ?>>Percent</option>
							                    </select>
							                    <br class="clearer" />
												</div>
												
												<div class="inputholder bottomgap">
												<label for="dt_twitter_display_mcmarginleft">Main Container Left Margin:</label>
												<p class="labeldesc">Choose The Left Margin For The Main Container (Spacing Around The Left Of The Main Container) In Pixels / Percent</p>
												<input id="dt_twitter_display_mcmarginleft" type="text" size="36" name="dt_twitter_display_mcmarginleft" class="numberinput left" value="<?php echo get_option('dt_twitter_display_mcmarginleft'); ?>" />
							                    <select name="dt_twitter_display_mcmarginleft_unit" class="left">
							                    <option value="1"<?php if((get_option('dt_twitter_display_mcmarginleft_unit')==1) || (!get_option('dt_twitter_display_mcmarginleft_unit'))) { echo ' selected="selected"'; } ?>>Pixels</option>
							                    <option value="0"<?php if(get_option('dt_twitter_display_mcmarginleft_unit')==0) { echo ' selected="selected"'; } ?>>Percent</option>
							                    </select>
							                    <br class="clearer" />
												</div>
												
												<div class="inputholder">
												<label for="dt_twitter_display_mcmarginright">Main Container Right Margin:</label>
												<p class="labeldesc">Choose The Right Margin For The Main Container (Spacing Around The Right Of The Main Container) In Pixels / Percent</p>
												<input id="dt_twitter_display_mcmarginright" type="text" size="36" name="dt_twitter_display_mcmarginright" class="numberinput left" value="<?php echo get_option('dt_twitter_display_mcmarginright'); ?>" />
							                    <select name="dt_twitter_display_mcmarginright_unit" class="left">
							                    <option value="1"<?php if((get_option('dt_twitter_display_mcmarginright_unit')==1) || (!get_option('dt_twitter_display_mcmarginright_unit'))) { echo ' selected="selected"'; } ?>>Pixels</option>
							                    <option value="0"<?php if(get_option('dt_twitter_display_mcmarginright_unit')==0) { echo ' selected="selected"'; } ?>>Percent</option>
							                    </select>
							                    <br class="clearer" />
												</div>
												
											</div>
											
										</fieldset>
									</div>
									
									<div class="dt-setting">
										<fieldset class="rounded">
										<legend>Tweet Settings</legend>
	
											<div class="dt-setting type-text">
														
												<div class="inputholder bottomgap">
												<label for="dt_twitter_display_fontsize">Tweet Font Size:</label>
												<p class="labeldesc">Choose The Size Of Your Tweet Text In Pixels / Ems</p>
												<input id="dt_twitter_display_fontsize" type="text" size="36" name="dt_twitter_display_fontsize" class="numberinput left" value="<?php echo get_option('dt_twitter_display_fontsize'); ?>" />
							                    <select name="dt_twitter_display_fontsize_unit" class="left">
							                    <option value="1"<?php if((get_option('dt_twitter_display_fontsize_unit')==1) || (!get_option('dt_twitter_display_fontsize_unit'))) { echo ' selected="selected"'; } ?>>Pixels</option>
							                    <option value="0"<?php if(get_option('dt_twitter_display_fontsize_unit')==0) { echo ' selected="selected"'; } ?>>Ems</option>
							                    </select>
							                    <br class="clearer" />
												</div>
														
												<div class="inputholder bottomgap">
												<label for="dt_twitter_display_fontcolor">Tweet Text Colour:</label>
												<p class="labeldesc">Choose The Colour For Your Tweets Text</p>
												<input id="dt_twitter_display_fontcolor" type="minicolors" size="36" name="dt_twitter_display_fontcolor" class="color left" value="<?php echo get_option('dt_twitter_display_fontcolor'); ?>" />
							                    <br class="clearer" />
												</div>

												<div class="inputholder bottomgap">
												<label for="dt_twitter_display_linkcolor">Tweet Link Colour:</label>
												<p class="labeldesc">Choose The Colour For Your Tweets Links</p>
												<input id="dt_twitter_display_linkcolor" type="minicolors" size="36" name="dt_twitter_display_linkcolor" class="color left" value="<?php echo get_option('dt_twitter_display_linkcolor'); ?>" />
							                    <br class="clearer" />
												</div>
																							
												<div class="inputholder bottomgap">
												<label for="dt_twitter_display_tweetbg">Tweet Main BG Colour:</label>
												<p class="labeldesc">Choose The Background Colour For Your Tweets Or Select Disabled To Make Them Transparent</p>
												<input id="dt_twitter_display_tweetbg" type="minicolors" size="36" name="dt_twitter_display_tweetbg" class="left" <?php if (get_option('dt_twitter_display_tweetbg_enabled')==0) { ?>disabled="disabled" value="No Background Colour"<?php } else { ?>value="<?php echo get_option('dt_twitter_display_tweetbg'); ?>"<?php } ?> />
							                    <select name="dt_twitter_display_tweetbg_enabled" rel="dt_twitter_display_tweetbg" class="colorselector left">
							                    <option value="1"<?php if((get_option('dt_twitter_display_tweetbg_enabled')==1) || (!get_option('dt_twitter_display_tweetbg_enabled'))) { echo ' selected="selected"'; } ?>>Enabled</option>
							                    <option value="0"<?php if(get_option('dt_twitter_display_tweetbg_enabled')==0) { echo ' selected="selected"'; } ?>>Disabled</option>
							                    </select>
							                    <br class="clearer" />
												</div>
												
												<div class="inputholder bottomgap">
												<label for="dt_twitter_display_tweetbgalt">Tweet Alternate BG Colour:</label>
												<p class="labeldesc">Choose The Background Colour For Each Alternate Tweet Or Select Disabled To Use The Same Value As The Main Tweet BG Colour</p>
												<input id="dt_twitter_display_tweetbgalt" type="minicolors" size="36" name="dt_twitter_display_tweetbgalt" class="left" <?php if (get_option('dt_twitter_display_tweetbgalt_enabled')==0) { ?>disabled="disabled" value="No Background Colour"<?php } else { ?>value="<?php echo get_option('dt_twitter_display_tweetbgalt'); ?>"<?php } ?> />
							                    <select name="dt_twitter_display_tweetbgalt_enabled" rel="dt_twitter_display_tweetbgalt" class="colorselector left">
							                    <option value="1"<?php if((get_option('dt_twitter_display_tweetbgalt_enabled')==1) || (!get_option('dt_twitter_display_tweetbgalt_enabled'))) { echo ' selected="selected"'; } ?>>Enabled</option>
							                    <option value="0"<?php if(get_option('dt_twitter_display_tweetbgalt_enabled')==0) { echo ' selected="selected"'; } ?>>Disabled</option>
							                    </select>
							                    <br class="clearer" />
												</div>
												
												<div class="inputholder bottomgap">
												<label for="dt_twitter_display_mcmargintop">Tweet Top Margin:</label>
												<p class="labeldesc">Choose The Top Margin For The Tweet (Spacing Around The Top Of The Tweet After Padding &amp; Borders) In Pixels / Percent</p>
												<input id="dt_twitter_display_tweetmargintop" type="text" size="36" name="dt_twitter_display_tweetmargintop" class="numberinput left" value="<?php echo get_option('dt_twitter_display_tweetmargintop'); ?>" />
							                    <select name="dt_twitter_display_tweetmargintop_unit" class="left">
							                    <option value="1"<?php if((get_option('dt_twitter_display_tweetmargintop_unit')==1) || (!get_option('dt_twitter_display_tweetmargintop_unit'))) { echo ' selected="selected"'; } ?>>Pixels</option>
							                    <option value="0"<?php if(get_option('dt_twitter_display_tweetmargintop_unit')==0) { echo ' selected="selected"'; } ?>>Percent</option>
							                    </select>
							                    <br class="clearer" />
												</div>
												
												<div class="inputholder bottomgap">
												<label for="dt_twitter_display_tweetmarginbottom">Tweet Bottom Margin:</label>
												<p class="labeldesc">Choose The Bottom Margin For The Tweet (Spacing Around The Bottom Of The Tweet After Padding &amp; Borders) In Pixels / Percent</p>
												<input id="dt_twitter_display_tweetmarginbottom" type="text" size="36" name="dt_twitter_display_tweetmarginbottom" class="numberinput left" value="<?php echo get_option('dt_twitter_display_tweetmarginbottom'); ?>" />
							                    <select name="dt_twitter_display_tweetmarginbottom_unit" class="left">
							                    <option value="1"<?php if((get_option('dt_twitter_display_tweetmarginbottom_unit')==1) || (!get_option('dt_twitter_display_tweetmarginbottom_unit'))) { echo ' selected="selected"'; } ?>>Pixels</option>
							                    <option value="0"<?php if(get_option('dt_twitter_display_tweetmarginbottom_unit')==0) { echo ' selected="selected"'; } ?>>Percent</option>
							                    </select>
							                    <br class="clearer" />
												</div>
												
												<div class="inputholder bottomgap">
												<label for="dt_twitter_display_tweetmarginleft">Tweet Left Margin:</label>
												<p class="labeldesc">Choose The Left Margin For The Tweet (Spacing Around The Left Of The Tweet After Padding &amp; Borders) In Pixels / Percent</p>
												<input id="dt_twitter_display_tweetmarginleft" type="text" size="36" name="dt_twitter_display_tweetmarginleft" class="numberinput left" value="<?php echo get_option('dt_twitter_display_tweetmarginleft'); ?>" />
							                    <select name="dt_twitter_display_tweetmarginleft_unit" class="left">
							                    <option value="1"<?php if((get_option('dt_twitter_display_tweetmarginleft_unit')==1) || (!get_option('dt_twitter_display_mcmarginleft_unit'))) { echo ' selected="selected"'; } ?>>Pixels</option>
							                    <option value="0"<?php if(get_option('dt_twitter_display_tweetmarginleft_unit')==0) { echo ' selected="selected"'; } ?>>Percent</option>
							                    </select>
							                    <br class="clearer" />
												</div>
												
												<div class="inputholder bottomgap">
												<label for="dt_twitter_display_tweetmarginright">Tweet Right Margin:</label>
												<p class="labeldesc">Choose The Right Margin For The Tweet (Spacing Around The Right Of The Tweet After Padding &amp; Borders) In Pixels / Percent</p>
												<input id="dt_twitter_display_tweetmarginright" type="text" size="36" name="dt_twitter_display_tweetmarginright" class="numberinput left" value="<?php echo get_option('dt_twitter_display_tweetmarginright'); ?>" />
							                    <select name="dt_twitter_display_tweetmarginright_unit" class="left">
							                    <option value="1"<?php if((get_option('dt_twitter_display_tweetmarginright_unit')==1) || (!get_option('dt_twitter_display_tweetmarginright_unit'))) { echo ' selected="selected"'; } ?>>Pixels</option>
							                    <option value="0"<?php if(get_option('dt_twitter_display_tweetmarginright_unit')==0) { echo ' selected="selected"'; } ?>>Percent</option>
							                    </select>
							                    <br class="clearer" />
												</div>

												<div class="inputholder bottomgap">
												<label for="dt_twitter_display_mcmargintop">Tweet Top Padding:</label>
												<p class="labeldesc">Choose The Top Padding For The Tweet (Spacing Around The Top Of The Tweet Before Margin &amp; Borders) In Pixels / Percent</p>
												<input id="dt_twitter_display_tweetpaddingtop" type="text" size="36" name="dt_twitter_display_tweetpaddingtop" class="numberinput left" value="<?php echo get_option('dt_twitter_display_tweetpaddingtop'); ?>" />
							                    <select name="dt_twitter_display_tweetpaddingtop_unit" class="left">
							                    <option value="1"<?php if((get_option('dt_twitter_display_tweetpaddingtop_unit')==1) || (!get_option('dt_twitter_display_tweetpaddingtop_unit'))) { echo ' selected="selected"'; } ?>>Pixels</option>
							                    <option value="0"<?php if(get_option('dt_twitter_display_tweetpaddingtop_unit')==0) { echo ' selected="selected"'; } ?>>Percent</option>
							                    </select>
							                    <br class="clearer" />
												</div>
												
												<div class="inputholder bottomgap">
												<label for="dt_twitter_display_tweetpaddingbottom">Tweet Bottom Padding:</label>
												<p class="labeldesc">Choose The Bottom Padding For The Tweet (Spacing Around The Bottom Of The Tweet Before Margin &amp; Borders) In Pixels / Percent</p>
												<input id="dt_twitter_display_tweetpaddingbottom" type="text" size="36" name="dt_twitter_display_tweetpaddingbottom" class="numberinput left" value="<?php echo get_option('dt_twitter_display_tweetpaddingbottom'); ?>" />
							                    <select name="dt_twitter_display_tweetpaddingbottom_unit" class="left">
							                    <option value="1"<?php if((get_option('dt_twitter_display_tweetpaddingbottom_unit')==1) || (!get_option('dt_twitter_display_tweetpaddingbottom_unit'))) { echo ' selected="selected"'; } ?>>Pixels</option>
							                    <option value="0"<?php if(get_option('dt_twitter_display_tweetpaddingbottom_unit')==0) { echo ' selected="selected"'; } ?>>Percent</option>
							                    </select>
							                    <br class="clearer" />
												</div>
												
												<div class="inputholder bottomgap">
												<label for="dt_twitter_display_tweetpaddingleft">Tweet Left Padding:</label>
												<p class="labeldesc">Choose The Left Padding For The Tweet (Spacing Around The Left Of The Tweet Before Margin &amp; Borders) In Pixels / Percent</p>
												<input id="dt_twitter_display_tweetpaddingleft" type="text" size="36" name="dt_twitter_display_tweetpaddingleft" class="numberinput left" value="<?php echo get_option('dt_twitter_display_tweetpaddingleft'); ?>" />
							                    <select name="dt_twitter_display_tweetpaddingleft_unit" class="left">
							                    <option value="1"<?php if((get_option('dt_twitter_display_tweetpaddingleft_unit')==1) || (!get_option('dt_twitter_display_tweetpaddingleft_unit'))) { echo ' selected="selected"'; } ?>>Pixels</option>
							                    <option value="0"<?php if(get_option('dt_twitter_display_tweetpaddingleft_unit')==0) { echo ' selected="selected"'; } ?>>Percent</option>
							                    </select>
							                    <br class="clearer" />
												</div>
												
												<div class="inputholder">
												<label for="dt_twitter_display_tweetpaddingright">Tweet Right Padding:</label>
												<p class="labeldesc">Choose The Right Padding For The Tweet (Spacing Around The Right Of The Tweet Before Margin &amp; Borders) In Pixels / Percent</p>
												<input id="dt_twitter_display_tweetpaddingright" type="text" size="36" name="dt_twitter_display_tweetpaddingright" class="numberinput left" value="<?php echo get_option('dt_twitter_display_tweetpaddingright'); ?>" />
							                    <select name="dt_twitter_display_tweetpaddingright_unit" class="left">
							                    <option value="1"<?php if((get_option('dt_twitter_display_tweetpaddingright_unit')==1) || (!get_option('dt_twitter_display_tweetpaddingright_unit'))) { echo ' selected="selected"'; } ?>>Pixels</option>
							                    <option value="0"<?php if(get_option('dt_twitter_display_tweetpaddingright_unit')==0) { echo ' selected="selected"'; } ?>>Percent</option>
							                    </select>
							                    <br class="clearer" />
												</div>
																								
											</div>
											
										</fieldset>
									</div>
									
									<div class="dt-setting"<?php if(get_option('dt_twitter_images')==0) { echo ' style="display:none;"'; } ?>>
										<fieldset class="rounded">
										<legend>Image Settings</legend>
	
											<div class="dt-setting type-text">

												<div class="inputholder bottomgap">
												<label for="dt_twitter_image_size">Tweet Image Size:</label>
												<p class="labeldesc">Choose The Size Of Your Tweet Image In Pixels</p>
							                    <select name="dt_twitter_image_size">
							                    <option value="10"<?php if(get_option('dt_twitter_image_size')==10) { echo ' selected="selected"'; } ?>>10px Width x 10px Height</option>
							                    <option value="15"<?php if(get_option('dt_twitter_image_size')==15) { echo ' selected="selected"'; } ?>>15px Width x 15px Height</option>
							                    <option value="20"<?php if(get_option('dt_twitter_image_size')==20) { echo ' selected="selected"'; } ?>>20px Width x 20px Height</option>
							                    <option value="25"<?php if(get_option('dt_twitter_image_size')==25) { echo ' selected="selected"'; } ?>>25px Width x 25px Height</option>
							                    <option value="30"<?php if(get_option('dt_twitter_image_size')==30) { echo ' selected="selected"'; } ?>>30px Width x 30px Height</option>
							                    <option value="35"<?php if(get_option('dt_twitter_image_size')==35) { echo ' selected="selected"'; } ?>>35px Width x 35px Height</option>
							                    <option value="40"<?php if(get_option('dt_twitter_image_size')==40) { echo ' selected="selected"'; } ?>>40px Width x 40px Height</option>
							                    <option value="45"<?php if(get_option('dt_twitter_image_size')==45) { echo ' selected="selected"'; } ?>>45px Width x 45px Height</option>
							                    <option value="50"<?php if((get_option('dt_twitter_image_size')==50) || (!get_option('dt_twitter_image_size'))) { echo ' selected="selected"'; } ?>>50px Width x 50px Height</option>
							                    <?php /*<option value="55"<?php if(get_option('dt_twitter_image_size')==55) { echo ' selected="selected"'; } ?>>55px Width x 55px Height</option>
							                    <option value="60"<?php if(get_option('dt_twitter_image_size')==60) { echo ' selected="selected"'; } ?>>60px Width x 60px Height</option>
							                    <option value="65"<?php if(get_option('dt_twitter_image_size')==65) { echo ' selected="selected"'; } ?>>65px Width x 65px Height</option>
							                    <option value="70"<?php if(get_option('dt_twitter_image_size')==70) { echo ' selected="selected"'; } ?>>70px Width x 70px Height</option>
							                    <option value="75"<?php if(get_option('dt_twitter_image_size')==75) { echo ' selected="selected"'; } ?>>75px Width x 75px Height</option>
							                    <option value="80"<?php if(get_option('dt_twitter_image_size')==80) { echo ' selected="selected"'; } ?>>80px Width x 80px Height</option>
							                    <option value="85"<?php if(get_option('dt_twitter_image_size')==85) { echo ' selected="selected"'; } ?>>85px Width x 85px Height</option>
							                    <option value="90"<?php if(get_option('dt_twitter_image_size')==90) { echo ' selected="selected"'; } ?>>90px Width x 90px Height</option>
							                    <option value="95"<?php if(get_option('dt_twitter_image_size')==95) { echo ' selected="selected"'; } ?>>95px Width x 95px Height</option>
							                    <option value="100"<?php if((get_option('dt_twitter_image_size')==100) || (!get_option('dt_twitter_image_size'))) { echo ' selected="selected"'; } ?>>100px Width x 100px Height</option>*/?>
							                    </select>
							                    <br class="clearer" />
												</div>
														
												<div class="inputholder bottomgap">
												<label for="dt_twitter_image_marginright">Tweet Image Margin Right:</label>
												<p class="labeldesc">Choose The Margin To The Right Of The Image (To Space Out The Text) In Pixels / Percent</p>
												<input id="dt_twitter_image_marginright" type="text" size="36" name="dt_twitter_image_marginright" class="numberinput left" value="<?php echo get_option('dt_twitter_image_marginright'); ?>" />
							                    <select name="dt_twitter_image_marginright_unit" class="left">
							                    <option value="1"<?php if((get_option('dt_twitter_image_marginright_unit')==1) || (!get_option('dt_twitter_image_marginright_unit'))) { echo ' selected="selected"'; } ?>>Pixels</option>
							                    <option value="0"<?php if(get_option('dt_twitter_image_marginright_unit')==0) { echo ' selected="selected"'; } ?>>Percent</option>
							                    </select>
							                    <br class="clearer" />
												</div>

												<div class="inputholder">
												<label for="dt_twitter_image_marginbottom">Tweet Image Margin Bottom:</label>
												<p class="labeldesc">Choose The Margin At The Bottom Of The Image (To Space Out Overflow Text) In Pixels / Percent</p>
												<input id="dt_twitter_image_marginbottom" type="text" size="36" name="dt_twitter_image_marginbottom" class="numberinput left" value="<?php echo get_option('dt_twitter_image_marginbottom'); ?>" />
							                    <select name="dt_twitter_image_marginbottom_unit" class="left">
							                    <option value="1"<?php if((get_option('dt_twitter_image_marginbottom_unit')==1) || (!get_option('dt_twitter_image_marginbottom_unit'))) { echo ' selected="selected"'; } ?>>Pixels</option>
							                    <option value="0"<?php if(get_option('dt_twitter_image_marginbottom_unit')==0) { echo ' selected="selected"'; } ?>>Percent</option>
							                    </select>
							                    <br class="clearer" />
												</div>												
																								
											</div>
											
										</fieldset>
									</div>									
									
									<div class="dt-setting"></div>
									<input type="hidden" name="option" value="displayupdate" />
									<input class="button-primary dtbutton" type="submit" name="submit" tabindex="1" value="Update Styling Options" />
										
									</form>
																			
								</div>	
									
							</div>	
							
							
							
							<div <?php if ($tab=="manual") {?>style="display: block;"<?php } else { ?>style="display: none;"<?php } ?> class="setting-right-section" id="setting-manual">
														
								<div class="dt-setting">
																		
									<div class="dt-setting">	
										<fieldset class="rounded">
										<legend>Manual Styling</legend>
	
											<div class="dt-setting type-text" id="setting_site_title">
												
												<div class="bottomgap">
												<p>OK, so the automatic styling not good enough for ya eh?  Fair play, I generally style up the tweets manually anyway so I've included a CSS Template for you to copy and paste into your theme's stylesheet (usually style.css in the main theme directory) and you can then amend the Twitter Feed to your liking with Custom CSS... Enjoy :)</p>
												<p>Simply click the button below to download or copy and paste the template from below the line.</p>
												<p><a class="button-primary dtbutton" href="<?php echo plugins_url().'/digicution-twitter/css/dt-twitter-template.css'; ?>" target="_blank">&nbsp;&nbsp;&nbsp;&nbsp;Download CSS Template&nbsp;&nbsp;&nbsp;&nbsp;</a></p>
												</div>
												
												<br/><hr/><br/>
												
<pre>												
/* ------------------------------------------------------------ */
/*            Digicution Simple Twitter CSS Template            */
/* ------------------------------------------------------------ */

/* Twitter Header Container */
div.dt-twitter-header										{   }

/* Twitter Follow Button */
a.twitter-follow-button										{   }

/* Header Follow Link (Not Button) */
a.dt-twitter-header-follow									{   }

/* Twitter UL Container */
ul.dt-twitter												{   }

/* Twitter LI Items (Single Tweets) */	
ul.dt-twitter li											{   }
ul.dt-twitter li.first										{   }
ul.dt-twitter li.post_even									{   }
ul.dt-twitter li.last										{   }
ul.dt-twitter li.last_even									{   }

/* Tweet Avatar Link & Img Styling */
a.dt-twitter-avatar-link 									{   }
img.dt-twitter-avatar										{   }

/* Tweet Wrapper */
span.dt-twitter-tweet										{   }

/* Tweet Styling */
div.dt-twitter-fullname a									{   }
div.dt-twitter-screenname a									{   }
div.dt-twitter-readdate a									{   }
div.dt-twitter-tweetbody									{   }
div.dt-twitter-tweetbody a									{   }

/* Tweet End Container & Action Buttons */
div.dt-twitter-end-container								{   }
div.dt-twitter-end-container a.dt-twitter-button-expand		{   }
div.dt-twitter-end-container a.dt-twitter-button-favourite	{   }
div.dt-twitter-end-container a.dt-twitter-button-retweet	{   }
div.dt-twitter-end-container a.dt-twitter-button-reply		{   }

/* Ending Text Container (After Tweet List & Only 4 Txt) */
div.dt-twitter-p-container									{   }

/* Bottom Text Follow Link */
a.dt-twitter-button											{   }	
</pre>
												
											</div>
											
										</fieldset>
									</div>
																																						
								</div>	
								
							</div>	
							
							
							<div <?php if ($tab=="integrate") {?>style="display: block;"<?php } else { ?>style="display: none;"<?php } ?> class="setting-right-section" id="setting-integrate">
														
								<div class="dt-setting">
																		
									<div class="dt-setting">	
										<fieldset class="rounded">
										<legend>How To Integrate</legend>
	
											<div class="dt-setting type-text" id="setting_site_title">
												
												<div class="bottomgap">
												<p>So, you've configured your Twitter App, sorted your settings and styled up your tweets...  So, how do you actually go about displaying them?  Well, there are three options :</p>
												<br/><br/><h3>1. Drag & Drop Widget</h3>
												<p>If your current theme has widget areas available, you can head to <strong>Appearance -> Widgets</strong> and simply Drag the <strong>"Digicution Twitter"</strong> widget into the widget area where you want your tweets to appear.</p>
												<br/><br/><h3>2. Use The Shortcode</h3>
												<p>You can drop the Twitter Widget into any standard Wordpress Post or Page simply by pasting the shortcode below into the content section of the post/page:<br/><br/><strong>[dt_twitter]</strong></p>
												<br/><br/><h3>3. Drop The Function In Manually</h3>
												<p>Or, for the more versed in theme customisation, you can simply drop the PHP function directly into your theme files where you want the Twitter Feed to appear.  To do this, simply copy and paste the code below into your theme where you want the Feed to appear:<br/><br/><strong>&lt;?php dt_twitter(); ?&gt;</strong></p>												
												</div>
												
											</div>
											
										</fieldset>
									</div>
																																						
								</div>	
								
							</div>	
							
							<?php //End Tabbed Content ?>
														
							
							
							
									
							<br class="clearer" />	
								
						</div>
							
					<br class="clearer" />
					
				</div>
			
			</div>
						
			<br class="clearer" />
						
		</div>
								
		<p id="dt-trademark"><em>Created By Dan Perkins @ Digicution</em></p>
					
	</div><!-- End Admin Area -->

</div><!-- End Wrapper -->

<div class="clearer"></div>

<?php 
//End Digicution Twitter Admin Function
} 
?>