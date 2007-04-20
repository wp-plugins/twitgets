<?php
/*
Plugin Name: Twitter Friends Widget
Description: Adds a sidebar widget to display twitter messages using Twitter's flash friends scripts
Author: dhtvllc
Version: 1.0
Author URI: http://www.dhtechventures.com
*/
?>
<?php
/*  
    This program is free software; you can redistribute it and/or modify
    it under the terms of the current GNU General Public License as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
*/
?>
<?php
// Called at the plugins_loaded action
function widget_twitter_friends_init() {
	
	// Check for the required API functions
	if ( !function_exists('register_sidebar_widget') || !function_exists('register_widget_control') )
		return;

	// Save options and print the widget's configuration interface.
	function widget_twitter_friends_control() {
		$options = get_option('widget_twitter_friends');

		if ( $_POST['twitter-friends-submit'] ) {
			$options['title'] = strip_tags(stripslashes($_POST['twitter-friends-title']));
			$options['username'] = strip_tags(stripslashes($_POST['twitter-friends-username']));
			update_option('widget_twitter_friends', $options);
		}
?>
		<div style="text-align:right">
		<label for="twitter-friends-title" style="line-height:35px;display:block;">
			<?php _e('Widget title:', 'widgets'); ?> 
			<input type="text" id="twitter-friends-title" name="twitter-friends-title" 
				value="<?php echo wp_specialchars($options['title'], true); ?>" />
		</label>

		<label for="twitter-friends-username" style="line-height:35px;display:block;">
			<?php _e('Twitter login:', 'widgets'); ?> 
			<input type="text" id="twitter-friends-username" name="twitter-friends-username" 
				value="<?php echo wp_specialchars($options['username'], true);?>"/>
		</label>

		<input type="hidden" name="twitter-friends-submit" id="twitter-friends-submit" value="1" />
		</div>
<?php
	}

	// Show the widget
	function widget_twitter_friends($args) {
		extract($args);
		$options = (array) get_option('widget_twitter_friends');
		$twittername = $options['username'];
		$title = $options['title'];
		if ( empty($title) )
			$title = '&nbsp;';
		echo $before_widget . $before_title . $title . $after_title;
?>
		<!-- This is the Twitter Friends Flash Plugin Code, modified to accept the Twitter Id from the user options saved -->
		<script type="text/javascript">
			function twitterCallback(obj) {
				var friendsHTML="";
				for (var i=0; i<obj.length; i++){
					//alert(i);
					var profile_image_url = obj[i].profile_image_url;
					var screen_name = obj[i].screen_name;
					var user_name = obj[i].name;
					friendsHTML+= ('<a href="http://twitter.com/'+obj[i].screen_name+'" rel="contact" title="'+obj[i].name+'"><img src="'+profile_image_url+'" border="0" width="24" height="24" style="padding: 1px;" /></a>')
				}
				document.getElementById('my_twitter_friends').innerHTML = friendsHTML;
			}
		</script>
		<div id="my_twitter_friends" style="width: 162px;"></div>
		<script type="text/javascript" src="http://twitter.com/statuses/friends/<?php echo $twittername;?>.json?callback=twitterCallback"></script>

<?php

		echo $after_widget;

	}

	// Tell Dynamic Sidebar about our new widget and its control
	register_sidebar_widget(array('Twitter Friends', 'widgets'), 'widget_twitter_friends');
	register_widget_control(array('Twitter Friends', 'widgets'), 'widget_twitter_friends_control');
	
}

// Delay plugin execution to ensure Dynamic Sidebar has a chance to load first
add_action('widgets_init', 'widget_twitter_friends_init');

?>
