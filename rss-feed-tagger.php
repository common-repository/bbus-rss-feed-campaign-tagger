<?php
/*
Plugin Name: BBU's RSS Feed Campaign Tagger
Version: 0.8
Plugin URI: http://blogbuildingu.com/software/rss-feed-campaign-tagger
Author: Hendry Lee
Author URI: http://blogbuildingu.com
Description: Marketers should know their numbers, but most bloggers and publishers are shooting in the dark when asked for conversions, i.e. sales or other goals, that come from RSS feed subscribers. Use this plugin to help you track Return on Investment (ROI) from your WordPress RSS feed. It readies your links for tracking with Google Analytics.
*/

/*  
Copyright 2009 Hendry Lee <hendry.lee@gmail.com> and Blog Building University.
Licensed under GPLv2, see file LICENSE in the package for details.
*/

if ( !class_exists( 'BBUFeedCampaignTagger' ) ) {
	
	class BBUFeedCampaignTagger {
		// The tagging code
		var $tag = 'utm_campaign=feed&utm_medium=feed&utm_source=blog';
		
		function BBUFeedCampaignTagger() {
			//blank
		}
		
		function bbu_permalink_feed_tagger( $content ) {
			// Permalink resides on own domain, so tag it
			return $content . ( strpos( $content, '?' ) ? '&' : '?' ) . $this->tag;
		}
		
		function bbu_content_feed_link ( $matches ) {
			// Only tag own links
			if ( strpos( $matches[0], get_option( 'home' ) ) )
				return "<a $matches[1]href=\"$matches[2]" . ( strpos( $matches[2], '?' ) ? '&' : '?' ) . $this->tag . "\"$matches[8]>$matches[9]</a>";
			else
				return $matches[0];
		}
				
		function bbu_content_feed_tagger( $content ) {
			if ( is_feed() )  {
				$urlpattern = '!<a (.*?)href=[\'"]?(https?://([-\w\.]+)(:\d+)?(/([\w/\.-]*(\?[^\'"]+)?)?)?)[\'"]?(.*)>(.*?)</a>!i';
				$content = preg_replace_callback( $urlpattern, array( &$this, 'bbu_content_feed_link' ), $content );
			}
			return $content;
		}
		
	} // class BBUFeedCampaignTracker
	
} // if !class_exists

if ( class_exists( 'BBUFeedCampaignTagger' ) )
	$bbu_fct = new BBUFeedCampaignTagger();
	
// Actions and filters
if ( isset( $bbu_fct ) ) {
	add_filter( 'the_permalink_rss', array( &$bbu_fct, 'bbu_permalink_feed_tagger' ) );
	add_filter( 'the_content', array( &$bbu_fct, 'bbu_content_feed_tagger' ) );
}

?> 
