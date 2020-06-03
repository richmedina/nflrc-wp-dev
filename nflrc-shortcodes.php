<?php 
/**
 * Plugin Name: xNFLRC Shortcode Lib
 * Plugin URI: 
 * Description: Specialized shortcodes for use with NFLRC Site
 * Author: RM
 * Version: 1.0
 */

function read_nflrc_fields($post) {
	global $post;
	$post_type = $post->post_type;
	$fields = array(
		    'title' 	=> $post->post_title,
		    'excerpt' 	=> $post->post_excerpt,
		    'post_type' => $post_type,
		    'icon' 		=> get_the_post_thumbnail(),
		    'link'		=> get_the_permalink(),
		    'content'   => $post->post_content,
		);

	if($post_type === 'project') {
		$fields['cycle'] = $post->grant_cycle;
		$fields['language'] = $post->language;
		$fields['director'] = $post->director;
		$fields['project_number'] = $post->project_number;

	} else if($post_type === 'publication') {
		$fields['language'] = $post->language;
		$fields['author'] = $post->author;

	} else if($post_type === 'prodev') {
		$fields['language'] = $post->language;
		$fields['event_date'] = $post->event_date;

	} else if($post_type === 'contact') {
		$fields['nflrc_role'] = $post->nflrc_role;
		$fields['nflrc_staff'] = $post->nflrc_staff;
		$fields['nflrc_role_type'] = $post->nflrc_role_type;

	} else if($post_type === 'story') {

	}
	return $fields;	
}

// [nflrc_feature_list]
/* Displays all post types flagged as featured ordered by featured rank.
*/
add_shortcode( 'nflrc_feature_list', 'nflrc_feature_list_func' );
function nflrc_feature_list_func($atts, $content = null) {
	$output = "";
	$args = array(
	    // 'numberposts'   	=> -1,
	    'post_type'      	=> array( 'project', 'prodev', 'publication', 'story' ),
	    'meta_query'     	=> array('key'=>'featured','compare'=>'=','value'=>'t'),
	    'meta_key'       	=> 'featured_rank',
	    'orderby'			=> 'meta_value_num',
	    'order'   			=> 'ASC',
	    'posts_per_page' 	=> 70,

	);
	$posts = new WP_Query($args);

	if ( $posts->have_posts() ) {
	    $output = "";
	    while ( $posts->have_posts() ) {
	        $posts->the_post();
	        $title = get_the_title();
	        $ptype = $post->post_type;
	        $featured = get_field('featured', $post->ID);
	        $featured_rnk = get_field('featured_rank', $post->ID);
	        $cycle = get_field('grant_cycle', $post->ID);
	        $output .= "<div>{$title}</div>";
	    }	    
	} else {
	    $output .= "<div>No matching posts.</div>";
	}
	/* Restore original Post Data */
	// wp_reset_postdata();
	return $output;
}

//[nflrc_project_block]
/* Displays all post types flagged as featured ordered by featured rank.

Attributes: 
post_slug - slug of item to display in the block
cls_str - horizontal (default) or vertical layout
Example: [nflrc_post_block post_slug="issues-in-placement" cls_str="vertical"]
*/
add_shortcode( 'nflrc_post_block', 'nflrc_post_block_func' );
function nflrc_post_block_func($atts, $content = null) {
	$a = shortcode_atts( array(
		'post_slug' => '',
		'cls_str' => 'horizontal',
	), $atts );
	
	$slug = sanitize_text_field($a['post_slug']);
	$args = array(
		'numberposts' 		=> 1,
		'name'				=> $slug,
	    'post_type'      	=> array('project', 'prodev', 'publication', 'contact', 'story'),
	);
	$posts = new WP_Query($args);
	$output = '';
	if ( $posts->have_posts() ) {
	    global $post;
	    while ( $posts->have_posts() ) {
	    	$posts->the_post();
	    	$data = read_nflrc_fields($post);
	    	$output .= "<article class='grid_block {$a['cls_str']}'>";
	    	$output .= "<div><a href='{$data['link']}'>{$data['icon']}</a></div>";
	    	$output .= "<div class='card'>";
	    	$output .= "<div class='block_title'><a href='{$data['link']}'>{$data['title']}</a></div>";
	    	$output .= "<div class='block_body'>{$data['excerpt']}</div>";
	    	$output .= "<div class='block_footer'>{$data['post_type']}</div>";
	    	$output .= "</div>";
	    	$output .= "</article>";
	    }
	    // wp_reset_postdata();  
	} else {
	    $output .= "<div>Content not found.</div>";	}

	wp_reset_postdata();
	
	return $output;	
}

//[nflrc_contact_grid]
/* Displays all contact post types filtered by shortcode param.

Attributes:
role_type - STAFF (default), ADVBOARD, COLLAB
cls_str - horizontal or vertical (vertical) layout for each card

Example: [nflrc_contact_grid role_type="STAFF"]
*/
add_shortcode('nflrc_contact_grid', 'nflrc_contact_grid_func');
function nflrc_contact_grid_func($atts, $content = null) {
	$a = shortcode_atts( array(
		'role_type' => 'STAFF',
		'cls_str' => 'vertical',
	), $atts );
	$role_type = sanitize_text_field($a['role_type']);
	$args = array(
		// 'numberposts' 		=> 1000,
		// 'order'   			=> 'DESC',
		'meta_query'     	=> array('key'=>'nflrc_role_type','compare'=>'=','value'=>'1'),
	    'post_type'      	=> 'contact',
	    'posts_per_page' 	=> -1,

	);
	$posts = new WP_Query($args);
	$output = '';
	
	if ( $posts->have_posts() ) {
		$output .= "<div class='grid_wrap'>";
	    global $post;
	    while ( $posts->have_posts() ) {
	    	$posts->the_post();
	    	$data = read_nflrc_fields($post);
	    	
	    	$output .= "<article class='grid_block {$a['cls_str']}'>";
	    	$output .= "<div><a href='{$data['link']}'>{$data['icon']}</a></div>";
	    	$output .= "<div class='card'>";
	    	$output .= "<div class='block_title'><a href='{$data['link']}'>{$data['title']}</a></div>";
	    	$output .= "<div class='block_body'>{$data['nflrc_staff']} | {$data['nflrc_role_type']} | {$data['excerpt']} </div>";
	    	$output .= "<div class='block_footer'>{$data['nflrc_role']}</div>";
	    	$output .= "</div>";
	    	$output .= "</article>";
	    	// var_dump($data);
	    }
	    $output .= "</div>";
	    // wp_reset_postdata();  
	} else {
	    $output .= "<div>Content not found.</div>";	}

	wp_reset_postdata();
	
	return $output;	
}

