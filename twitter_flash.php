<?php
/*
Plugin Name: Twitter Flash Widget
Description: Adds a sidebar widget to display twitter messages using Twitter's flash script
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
function widget_twitter_flash_init() {
	
	// Check for the required API functions
	if ( !function_exists('register_sidebar_widget') || !function_exists('register_widget_control') )
		return;

	// Save options and print the widget's configuration interface.
	function widget_twitter_flash_control() {
		$options = get_option('widget_twitter_flash');

		if ( $_POST['twitter-flash-submit'] ) {
			$options['title'] = strip_tags(stripslashes($_POST['twitter-flash-title']));
			$options['username'] = strip_tags(stripslashes($_POST['twitter-flash-username']));
			$options['userid'] =  (int) $_POST['twitter-flash-id'];
			update_option('widget_twitter_flash', $options);
		}
?>
		<div style="text-align:right">
		<label for="twitter-flash-title" style="line-height:35px;display:block;">
			<?php _e('Widget title:', 'widgets'); ?> 
			<input type="text" id="twitter-flash-title" name="twitter-flash-title" 
				value="<?php echo wp_specialchars($options['title'], true); ?>" />
		</label>

		<label for="twitter-flash-username" style="line-height:35px;display:block;">
			<?php _e('Twitter login:', 'widgets'); ?> 
			<input type="text" id="twitter-flash-username" name="twitter-flash-username" 
				value="<?php echo wp_specialchars($options['username'], true);?>"/>
		</label>

		<label for="twitter-flash-id" style="line-height:35px;display:block;">
			<?php _e('Twitter id:', 'widgets'); ?> 
			<input type="text" id="twitter-flash-id" name="twitter-flash-id" value="<?php echo $options['userid']; ?>" />
		</label>
		<input type="hidden" name="twitter-flash-submit" id="twitter-flash-submit" value="1" />
		</div>
<?php
	}

	// Show the widget
	function widget_twitter_flash($args) {
		extract($args);
		$options = (array) get_option('widget_twitter_flash');
		$twitterid = (int)$options['userid'];
		$title = $options['title'];
		if ( empty($title) )
			$title = '&nbsp;';
		echo $before_widget . $before_title . $title . $after_title;
?>
		<!-- This is the Twitter Flash Plugin Code, modified to accept the Twitter Id from the user options saved -->
		<div style="width:176px;text-align:center">
		<embed src="http://twitter.com/flash/twitter_badge.swf"
			flashvars="color1=52479&type=user&id=<?php echo $twitterid;?>"
			quality="high" 
			width="176" 
			height="176" 
			name="twitter_badge" 
			align="middle" 
			allowScriptAccess="always" 
			wmode="transparent" 
			type="application/x-shockwave-flash" 
			pluginspage="http://www.macromedia.com/go/getflashplayer" 
		/>
		<br>
		<a style="font-size: 10px; color: #00CCFF; text-decoration: none" 
			href="http://twitter.com//<?php echo $options['username'];?>">follow <?php echo $options['username'];?> at http://twitter.com</a>
		</div>

<?php

		echo $after_widget;

	}

	// Tell Dynamic Sidebar about our new widget and its control
	register_sidebar_widget(array('Twitter Flash', 'widgets'), 'widget_twitter_flash');
	register_widget_control(array('Twitter Flash', 'widgets'), 'widget_twitter_flash_control');
	
}

// Delay plugin execution to ensure Dynamic Sidebar has a chance to load first
add_action('widgets_init', 'widget_twitter_flash_init');

?>
