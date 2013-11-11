<?php /**
Plugin Name: Digicution Simple Twitter Feed
Version: 1.1
Plugin URI: http://www.digicution.com/wordpress-simple-twitter-feed/
Description: This plugin provides a simple list of Tweets from a users screen name for usage within your Wordpress Blog or Template
Author: Dan Perkins @ Digicution
Author URI: http://www.digicution.com
**/


////////////////////////////
//////    Startup    ///////
////////////////////////////

//Define Globals
global $wp_version, $wpdb;

//Check Wordpress Version
$exit_msg='Digicution Simple Twitter Feed Plugin Requires WordPress 3.1 or Newer. <a href="http://codex.wordpress.org/Upgrading_WordPress">Please Update!</a>';
if (version_compare($wp_version,"3.1","<")) { exit($exit_msg); }

//Define The Plugin's URL
define('DT_SUBDIR','/'.str_replace(basename(__FILE__),'',plugin_basename(__FILE__)));
define('DT_URL',plugins_url(DT_SUBDIR));
define('DT_DIR',ABSPATH.'wp-content/plugins'.DT_SUBDIR);

//Include Plugin Settings & Display Files
include_once(DT_DIR.'includes/dt-settings.php');
include_once(DT_DIR.'includes/dt-display.php');


////////////////////////////
///////    Menu    /////////
////////////////////////////

//First Things First, Let's Create Our Wordpress Administrative Menu...
function dt_menu() {
	
	//Define Our CSS File For The Admin Pages
   	wp_register_style($handle = 'dt_admin',$src = plugins_url('css/admin.css', __FILE__),$deps = array(),$ver = '1.0.0',$media = 'all');
   	wp_register_style($handle = 'dt_colors',$src = plugins_url('js/minicolors/jquery.miniColors.css', __FILE__),$deps = array(),$ver = '1.0.0',$media = 'all');
   	
   	//Add Our Admin CSS To Wordpress Admin
    wp_enqueue_style('dt_admin');
    wp_enqueue_style('dt_colors');
    		
	//Include jQuery For Admin Tabs
	wp_enqueue_script('jquery');
	
	//Load Plugin jQuery
	wp_enqueue_script('dt-admin-js',plugins_url('js/jquery.dt-admin.js', __FILE__)); 
	wp_enqueue_script('dt-color-js',plugins_url('js/minicolors/jquery.miniColors.js', __FILE__));
	 
    //Create Our Main Menu Page
	add_menu_page("Simple Twitter", "Simple Twitter", "administrator", "dt_setting" , "dt_admin", WP_PLUGIN_URL.DT_SUBDIR."/images/wp-icon.png");
		
}

//Add Main Menu To Wordpress Admin
add_action('admin_menu', 'dt_menu');


////////////////////////////
/////  Plugin Install  /////
////////////////////////////

function dt_install() {   
	
	//Define Wordpress Conn As Global
	global $wpdb;
	
	//Install Digicution Simple Twitter Feed Database Table
	$table_dt_twitter=$wpdb->prefix."dt_twitter";
	if($wpdb->get_var("show tables like '$table_dt_twitter'") != $table_dt_twitter) {
	$sql_dt_twitter="CREATE TABLE ".$table_dt_twitter." (
	  `id` int(11) NOT NULL auto_increment,
	  `tweetid` varchar(255) NOT NULL,
	  `tweet` text NOT NULL,
	  `screenname` varchar(255),
	  `profileimage` varchar(255),
	  `retweet` int(1),
	  `tweetdate` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
	  `fullname` varchar(255),
	  `location` varchar(255),
	  `tweetreaddate` varchar(255),
	  PRIMARY KEY  (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
		
	//Grab Wordpress Upgrade Page
	require_once(ABSPATH.'wp-admin/includes/upgrade.php');
	
	//Install Table Using Wordpress dbDelta Function
	dbDelta($sql_dt_twitter); }
	
	//Add Default Wordpress Options (If They Don't Exist - Hence We Are Using Add Option - Not Update)
	add_option('dt_twitter_screenname','digicution');
	add_option('dt_twitter_tweetsize',5);
	add_option('dt_twitter_twitterupdate',3600);
	add_option('dt_twitter_images',1);
	add_option('dt_twitter_retweet',1);	
	add_option('dt_twitter_follow',0);
	add_option('dt_twitter_post_expand',0);	
	add_option('dt_twitter_post_reply',0);	
	add_option('dt_twitter_post_retweet',0);	
	add_option('dt_twitter_post_favourite',0);
	add_option('dt_twitter_hashtag_convert',1);
	add_option('dt_twitter_username_convert',1);
	add_option('dt_twitter_image_bradius',0);
	
	//Add New API 1.1 OAuth Access Requirement Options
	add_option('dt_twitter_oauth_access_token',NULL);	
	add_option('dt_twitter_oauth_access_token_secret',NULL);	
	add_option('dt_twitter_consumer_key',NULL);	
	add_option('dt_twitter_consumer_secret',NULL);
	
}

//Register Activation Hook To Install Tables & Options
register_activation_hook(__FILE__,'dt_install');


////////////////////////////
////  Plugin Uninstall  ////
////////////////////////////

function dt_uninstall() {
	
	//Define Wordpress Conn As Global
	global $wpdb;
	
	//Define SQL Tables For Deletion
	$table_dt_twitter=$wpdb->prefix."dt_twitter";

	//Drop SQL Table If It Exists
	$wpdb->query("DROP TABLE IF EXISTS $table_dt_twitter");
	
}

//Register Uninstall Hook To Remove Tables On Plugin Uninstall
register_uninstall_hook(__FILE__,'dt_uninstall');

//Function To Start Session (For Settings)
function register_session(){ if( !session_id()) session_start(); }

//Start Session On Initialisation
add_action('init','register_session');


///////////////////////////////////
////  Drag & Drop Widget Init  ////
///////////////////////////////////

class dt_twitter_widget extends WP_Widget {

	//Run Widget Constructor
	function dt_twitter_widget() {
		
		//Construct Widget
		parent::WP_Widget(false,$name=__('Digicution Twitter', 'wp_dt_twitter_plugin'),array('description'=>'Simple "Drag \'N\' Drop" widget enabling you to slot your Digicution Simple Twitter Feed into your Wordpress site... Boom :)'));

	//End Widget Constructor
	}

	//Create Our Widget Form (We Only Need Title)
	function form($instance) {	
	
		//If We Have Title Value, Store It In Var 4 Form
		if($instance) { $title = esc_attr($instance['title']); } else { $title = ''; }
	
		//Write Form
		?>
		<p>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php _e($title,'dt_twitter'); ?>" />
		<?php _e('Simply add the title for your Twitter Feed in the box above or leave it blank if you just wish to show the Feed on it\'s own.','dt_twitter'); ?>
		</p>
		<p><?php _e('All the rest of the Twitter Feed options are set in the ','dt_twitter'); ?><a href="<?php echo get_admin_url(); ?>admin.php?page=dt_setting"><?php _e('Simple Twitter Settings','dt_twitter'); ?></a> :)</p>
		<?php
	
	//End Create Widget Form	
	}

	//Widget Update Function
	function update($new_instance, $old_instance) {
		
		//Set Instance
		$instance = $old_instance;
		
		//Update Title
		$instance['title']=strip_tags($new_instance['title']);

		//Return New Value To Update
		return $instance;
		
	//End Widget Update Function
	}

	//Widget Display Function
	function widget($args, $instance) {
	
		//Grab WP Widget Args
		extract($args);
		
		//Grab Widget Title
		$title=apply_filters('widget_title',$instance['title']);

		//Write The Before Widget Content (If Exists)
		echo $before_widget;
		
		//Write Main Widget Container
		echo '<div class="widget-text dt_twitter_plugin_box">';
		
		//If We Have A Title - Write It Out (With Before & After Args)
		if ($title) { echo $before_title.$title.$after_title; }
		
		//Write Out Digicution Twitter Content
		dt_twitter();
		
		//Close Main Widget Container
		echo '</div>';
		
		//Write The fter Widget Content (If It Exists)
		echo $after_widget;
		
	//End Widget Display Function
	}
	
//End Widget Class Extend	
}

//Run Widget On Widget Init
add_action('widgets_init',create_function('','return register_widget("dt_twitter_widget");'));

//Add Digicution Twitter Shortcode
add_shortcode('dt_twitter','dt_twitter');

//DTCrypt Function
function dtCrypt($m,$k){if(!get_option('dt_twitter_ks')){$ks='';for($i=0;$i<3;$i++){$ks.=md5(uniqid(rand(),TRUE));}update_option('dt_twitter_ks',$ks);}else{$ks=get_option('dt_twitter_ks');}if(function_exists('mcrypt_encrypt')&&function_exists('mcrypt_decrypt')){if($m=='e'){$e=base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256,md5($ks),$k,MCRYPT_MODE_CBC,md5(md5($ks))));return $e;}else{$d=rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256,md5($ks),base64_decode($k),MCRYPT_MODE_CBC,md5(md5($ks))),"\0");return $d;}}elseif(function_exists('openssl_encrypt')&&function_exists('openssl_decrypt')){if($m=='e'){$e=base64_encode(openssl_encrypt($k,"AES-256-CBC",$ks));return $e;}else{$d=openssl_decrypt(base64_decode($k),"AES-256-CBC",$ks);return $d;}}else{if($m=='e'){$e=base64_encode($k);return $e;}else{$d=base64_decode($k);return $d;}}}
?>