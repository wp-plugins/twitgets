<?php
/*
Plugin Name: Twitter Party Widget
Description: Adds a sidebar widget to display your Twitter Friends Timeline using Twitter's flash scripts
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
function widget_twitter_party_init() {
	
	// Check for the required API functions
	if ( !function_exists('register_sidebar_widget') || !function_exists('register_widget_control') )
		return;

	// Save options and print the widget's configuration interface.
	function widget_twitter_party_control() {
		$options = get_option('widget_twitter_party');

		if ( $_POST['twitter-party-submit'] ) {
			$options['title'] = strip_tags(stripslashes($_POST['twitter-party-title']));
			$options['username'] = strip_tags(stripslashes($_POST['twitter-party-username']));
			$options['fullname'] = strip_tags(stripslashes($_POST['twitter-party-fullname']));
			$options['userid'] =  (int) $_POST['twitter-party-id'];
			update_option('widget_twitter_party', $options);
		}
?>
		<div style="text-align:right">
		<label for="twitter-party-title" style="line-height:35px;display:block;">
			<?php _e('Widget title:', 'widgets'); ?> 
			<input type="text" id="twitter-party-title" name="twitter-party-title" 
				value="<?php echo wp_specialchars($options['title'], true); ?>" />
		</label>

		<label for="twitter-party-username" style="line-height:35px;display:block;">
		<?php _e('Twitter login:', 'widgets'); ?> 
		<input type="text" id="twitter-party-username" name="twitter-party-username" 
			value="<?php echo wp_specialchars($options['username'], true);?>"/>
		</label>

		<label for="twitter-party-fullname" style="line-height:35px;display:block;">
			<?php _e('Fullname:', 'widgets'); ?> 
			<input type="text" id="twitter-party-fullname" name="twitter-party-fullname" 
				value="<?php echo $options['fullname']; ?>" />
		</label>

                <label for="twitter-party-id" style="line-height:35px;display:block;">
                        <?php _e('Twitter id:', 'widgets'); ?>
                        <input type="text" id="twitter-party-id" name="twitter-party-id" 
				value="<?php echo $options['userid']; ?>" />
                </label>

		<input type="hidden" name="twitter-party-submit" id="twitter-party-submit" value="1" />
		</div>
<?php
	}

	// Show the widget
	function widget_twitter_party($args) {
		extract($args);
		$options = (array) get_option('widget_twitter_party');
		$twittername = $options['username'];
		$twitterfullname = $options['fullname'];
		$twitterid = (int)$options['userid'];
		$title = $options['title'];
		if ( empty($title) )
			$title = '&nbsp;';
		echo $before_widget . $before_title . $title . $after_title;
?>
		<!-- This is the Twitter Party Flash Plugin Code, modified to accept the Twitter IDs from the user options saved -->
<script type="text/javascript" src="http://static.twitter.com/javascripts/swfobject.js"></script>
<script>
function writeTwitterPartyBadge (badgeWidth,badgeHeight,userID,userName,displayName,color1,color2,textColor1,textColor2,backgroundColor,textSize){
document.write ('<div id="flashcontent" style="width: '+badgeWidth+'px;height: '+badgeHeight+'px;text-align: center;"><strong>Flash Player 9 is required.<br><a href="http://www.adobe.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash">Get it here.</a></strong>')
var so = new SWFObject("http://static.twitter.com/flash/twitter_timeline_badge.swf", "twitter_timeline_badge", badgeWidth, badgeHeight, "9.0.0", backgroundColor, false);
so.addVariable("user_id",userID);
so.addVariable("color1",color1);
so.addVariable("color2",color2);
so.addVariable("textColor1",textColor1);
so.addVariable("textColor2",textColor2);
so.addVariable("backgroundColor",backgroundColor);
so.addVariable("textSize",textSize);
so.write("flashcontent");
document.write('<br><a style="font-size: 10px; color: #00CCFF; text-decoration: none" href="http://twitter.com/<?php echo $options['username'];?>">follow <?php echo $options['username'];?> at http://twitter.com</a></div>');
}
</script>
<div style="width:176px;text-align:center">
<script>writeTwitterPartyBadge(176,400,<?php echo $twitterid;?>,"<?php echo $twittername;?>","<?php echo $twitterfullname;?>","0xFFFFCE","0xFCE7CC","0x4A396D","0xBA0909","0x92E2E5","10")</script>
</div>
<br>
<?php

		echo $after_widget;

	}

	// Tell Dynamic Sidebar about our new widget and its control
	register_sidebar_widget(array('Twitter Party', 'widgets'), 'widget_twitter_party');
	register_widget_control(array('Twitter Party', 'widgets'), 'widget_twitter_party_control');
	
}

// Delay plugin execution to ensure Dynamic Sidebar has a chance to load first
add_action('widgets_init', 'widget_twitter_party_init');

?>
