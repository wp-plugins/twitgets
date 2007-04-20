<?php
/*
Plugin Name: Twitter Javascript Widget
Description: Adds a sidebar widget to display twitter messages using Twitter's javascript method
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
function widget_twitter_js_init() {
	
	// Check for the required API functions
	if ( !function_exists('register_sidebar_widget') || !function_exists('register_widget_control') )
		return;

	// Save options and print the widget's configuration interface.
	function widget_twitter_js_control() {
		$options = get_option('widget_twitter_js');

		if ( $_POST['twitter-js-submit'] ) {
			$options['title'] = strip_tags(stripslashes($_POST['twitter-js-title']));
			$options['username'] = strip_tags(stripslashes($_POST['twitter-js-username']));
			$options['userid'] =  (int) $_POST['twitter-js-id'];
			update_option('widget_twitter_js', $options);
		}
?>
		<div style="text-align:right">
		<label for="twitter-js-title" style="line-height:35px;display:block;">
			<?php _e('Widget title:', 'widgets'); ?> 
			<input type="text" id="twitter-js-title" name="twitter-js-title" 
				value="<?php echo wp_specialchars($options['title'], true); ?>" />
		</label>

		<label for="twitter-js-username" style="line-height:35px;display:block;">
			<?php _e('Twitter login:', 'widgets'); ?> 
			<input type="text" id="twitter-js-username" name="twitter-js-username" 
				value="<?php echo wp_specialchars($options['username'], true);?>"/>
		</label>

		<label for="twitter-js-id" style="line-height:35px;display:block;">
			<?php _e('Twitter id:', 'widgets'); ?> 
			<input type="text" id="twitter-js-id" name="twitter-js-id" value="<?php echo $options['userid']; ?>" />
		</label>
		<input type="hidden" name="twitter-js-submit" id="twitter-js-submit" value="1" />
		</div>
<?php
	}

	// Show the widget
	function widget_twitter_js($args) {
		extract($args);
		$options = (array) get_option('widget_twitter_js');
		$twitterid = (int)$options['userid'];
		$title = $options['title'];
		if ( empty($title) )
			$title = '&nbsp;';
		echo $before_widget . $before_title . $title . $after_title;
?>
		<!-- This is the Twitter JavaScript Code, modified to accept the Twitter Id from the user options saved -->
		<div style="width:176px;text-align:center">

<script type="text/javascript">
  function relative_time(time_value) {
     var parsed_date = Date.parse(time_value);

     var relative_to = (arguments.length > 1) ? arguments[1] : new Date();
     var delta = parseInt((relative_to.getTime() - parsed_date) / 1000);

     if(delta < 60) {
         return 'less than a minute ago';
     } else if(delta < 120) {
         return 'about a minute ago';
     } else if(delta < (45*60)) {
         return (parseInt(delta / 60)).toString() + ' minutes ago';
     } else if(delta < (90*60)) {
         return 'about an hour ago';
     } else if(delta < (24*60*60)) {
         return 'about ' + (parseInt(delta / 3600)).toString() + ' hours ago';
     } else if(delta < (48*60*60)) {
         return '1 day ago';
     } else {
         return (parseInt(delta / 86400)).toString() + ' days ago';
     }
  }
  
	function twitterCallback(obj) {
		var id = obj[0].user.id;
		document.getElementById('my_twitter_status').innerHTML = obj[0].text;
		document.getElementById('my_twitter_status_time').innerHTML = relative_time(obj[0].created_at);
	}
</script>
<span id="my_twitter_status"></span> <span id="my_twitter_status_time"></span>

<script type="text/javascript" src="http://www.twitter.com/statuses/user_timeline/<?php echo $twitterid ?>.json?callback=twitterCallback&count=1"></script>
<br>
		<a style="font-size: 10px; color: #00CCFF; text-decoration: none" 
			href="http://twitter.com/<?php echo $options['username'];?>">follow <?php echo $options['username'];?> at http://twitter.com</a>
		</div>

<?php
		echo $after_widget;
	}

	// Tell Dynamic Sidebar about our new widget and its control
	register_sidebar_widget(array('Twitter JS', 'widgets'), 'widget_twitter_js');
	register_widget_control(array('Twitter JS', 'widgets'), 'widget_twitter_js_control');
	
}

// Delay plugin execution to ensure Dynamic Sidebar has a chance to load first
add_action('widgets_init', 'widget_twitter_js_init');

?>
