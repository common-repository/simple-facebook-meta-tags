<?php

/**
 * @package Element Engage - Simple Social Meta Tags
 * @version 1.5
 * GPLv2 or later
 */
/*
Plugin Name: Simple Social Meta Tags
Plugin URI: https://elementengage.com
Description: Adds Open Graph meta tags to your pages to make social media shares more meaningful and attractive. | No Config Required | <a href="https://elementengage.com/donate/">Donate</a> | <a href="https://elementengage.com/contact-me/">Feedback</a>
Author: Mitchell Bennis - Element Engage, LLC
Version: 1.5
Author URI: https://elementengage.com
*/
	
defined( 'ABSPATH' ) or die( 'No direct access is allowed' );

// Make pages Facebook friendly
function eeSimpleFacebookMetaTags() { 
	
	global $post; // Connect to Wordpress
	
	// Key Variables
	$eeImage = '';
	
	// Go if we have a Post ID, else nothing.
	if($post->ID) {
		
		// Setup
		$eePostObject = get_post($post->ID); // Get the Post object
		$eePostContent = $eePostObject->post_content; // Get the Content from the Post
		
		$eeOutput = "\n\n<!-- Simple Social Meta Tags -->\n"; // We echo this at the end of the script.
		
		// The Site Name
		$eeSiteName = strip_tags(get_bloginfo('name'));
		$eeOutput .= '<meta property="og:site_name" content="' . $eeSiteName . '" />' . "\n";
		
		// The URL
		$eeOutput .= '<meta property="og:url" content="' . get_permalink() . '" />' . "\n";
		
		// The Type
		if( is_page() ) {
			$eeType = 'website';
		} else {
			$eeType = 'blog';
		}
		
		$eeOutput .= '<meta property="og:type" content="' . $eeType . '" />' . "\n";
	
		
		// The Title
		$eeOutput .= '<meta property="og:title" content="' . get_the_title() . '" />' . "\n";
		
		
		// The Description - - - NEEDS WORK (TO DO)
		$eePostText = $eePostContent;
		$eePostText = strip_tags($eePostText); // Remove all HTML
		$eePostText = str_replace("\r\n", ' ', $eePostText);
		// $eePostText = str_replace("..", '.', $eePostText);
		
		// Get the sentances in the text
		if($eePostText) {
			
			$eePos1 = strpos($eePostText, '[');
			if($eePos1 === 0) { // Content starts with a shortcode
				
				$eePos2 = strpos($eePostText, ']');
				
				if($eePos2) {
					$eePostText = substr($eePostText, $eePos2+1); // Start after shortcode
				}
			}
			
			$eePos3 = strpos($eePostText, '[');
				
			if($eePos3) {
				$eePostText = substr($eePostText, 0, $eePos3); // End at the shortcode
			}
			
			$eeArray = explode('. ', $eePostText); // Look for sentance endings.
			
			if($eeArray[0]) {
				
				// Get just the first three sentances, make code-output-ready.
				$eeExcerpt = htmlentities($eeArray[0] . '. ' . @$eeArray[1] . '. ' . @$eeArray[2]) . '.';
			
			} else {
				$eeExcerpt = htmlentities($eePostText);
			}
			 
		}
		
		$eeExcerpt = eeTrimAllWhite($eeExcerpt); // Use function below to clean up the text.
		
		if($eeExcerpt) {
			
			$eeOutput .= '<meta property="og:description" content="' . $eeExcerpt . '" />' . "\n";
		}
		
		
		// The Image - The Hard Part
		if(has_post_thumbnail($post->ID)) { // Try the official Post thumbnail first
			
			// Get the featured image tag
			$eePostContent = get_the_post_thumbnail($post->ID);
			$eeImage = eeGetContentImage($eePostContent); // Get the image URL only
		
		} elseif(strpos($eePostContent, 'src=')) { // Look in the Post content
			
			$eeImage = eeGetContentImage($eePostContent);
			
		}
		
		if(!$eeImage) { // Look elsewhere
			
			if(is_readable($_SERVER['DOCUMENT_ROOT'] . '/social_default_image.png')) {
				
				$eeImage = get_site_url() . '/social_default_image.png';
				
			} elseif(is_readable($_SERVER['DOCUMENT_ROOT'] . '/social_default_image.jpg')) {
				
				$eeImage = get_site_url() . '/social_default_image.jpg';
				
			} elseif( has_custom_logo() ) {
				
				$eeImage = get_custom_logo();
				$eeImage = eeGetContentImage($eeImage);
			
			} elseif( get_header_image() ) {
				
				$eeImage = get_header_image();
			
			} else {
				// We're struggling :-(  - - - - - (TO DO)
					
				// Could we can the final full page HTML and do a javascript find/replace to ensure just our tags are used?
				// Like, an over-ride?
			}	
		}
		
		if($eeImage) { // Do we have an actual image we can view ?
			
			$eeImageHeaders = @get_headers($eeImage); // Let's see...
			
			if($eeImageHeaders AND !strpos($eeImageHeaders[0], '404')) { // 404 is always bad
			    
			    $eeImageCheck = TRUE;
				
			} elseif(eeCheckImageURL($eeImage)) { // Try harder
				
				$eeOutput .= '<!-- Image Header Failed -->' . "\n";
				$eeOutput .= '<!-- Image Headers: ' . @implode(', ', $eeImageHeaders) . " -->\n";
				
				$eeImageCheck = eeCheckImageURL($eeImage); // Just see if the URL is reachable
				
			}
			
			if($eeImageCheck) {
				
				// Image checks out...
			    $eeOutput .= '<meta property="og:image" content="' . $eeImage . '"/>'; // Output the image meta tag
				
			} else {
				
				// Not readable	
				$eeOutput .= '<!-- Image Cannot be Found: ' . $eeImage . " -->\n";
				$eeOutput .= '<!-- Image Headers: ' . @implode(', ', $eeImageHeaders) . " -->\n";
			}
		}
		
		// All Done
		echo $eeOutput . "\n\n\n"; // Print the output to the page ----- THE END.
		
		// But, what about duplicate tags? (TO DO)
	}
}


function eeGetContentImage($eePostContent) {
	
	$eeDom = new DOMDocument(); // Create an object from the content
	$eeDom->loadHTML($eePostContent); // Get the page HTML
	$eeDom->preserveWhiteSpace = false; // Get rid of whitespace
    
    $imageTags = $eeDom->getElementsByTagName('img'); // Get just the images
    
    $eeImages = array(); // Need an array
    
    // Loop through the images and add to our array.
    foreach ($imageTags as $eeImage) {
		$eeImages[] = $eeImage->getAttribute('src');
	}
    
    // Use the first image only
    if($eeImages[0]) { $eeImage = $eeImages[0]; }
    
    if($eeImage) { return $eeImage; }
}



function eeTrimAllWhite($eeString) {
    
    $eeWith = ' ';
    $eeWhat   = "\\x00-\\x20";    // All white-spaces and control chars
   
    return trim( preg_replace( "/[".$eeWhat."]+/" , $eeWith , $eeString ) , $eeWhat );
}


function eeCheckImageURL($eeImage) {
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$eeImage);
    // don't download content
    curl_setopt($ch, CURLOPT_NOBODY, 1);
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$result = curl_exec($ch);
    curl_close($ch);
    
    if($result !== FALSE) {
        return TRUE;
    } else {
        return FALSE;
    }
}


	
// Hook into the page output, place in <head> section.

add_action('wp_head','eeSimpleFacebookMetaTags');




	
?>