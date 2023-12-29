<?php
/**
 * Plugin Name:       WP Column
 * Plugin URI:        https://classysystem.com/plugin/wp-column/
 * Description:       WordPress Column management
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Gazi Akter
 * Author URI:        https://gaziakter.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://classysystem.com/
 * Text Domain:       wp-column
 * Domain Path:       /languages
 */

 /**
  * Load textdomain
  */
 function wpcol_textdomain(){
    load_textdomain( "wp-column", false, dirname(__FILE__)."/languages" );
 }
 add_action( "plugins_loaded", "wpcol_textdomain" );

 /**
  * Show new column
  */
 
function wpcol_post_columns( $columns ) {
	print_r( $columns );
	unset( $columns['tags'] );
	unset( $columns['comments'] );
	/*unset($columns['author']);
	unset($columns['date']);
	$columns['author']="Author";
	$columns['date']="Date";*/
	$columns['id']        = __( 'Post ID', 'wp-column' );
	$columns['thumbnail'] = __( 'Thumbnail', 'wp-column' );
	$columns['wordcount'] = __( 'Word Count', 'wp-column' );

	return $columns;
}

add_filter( 'manage_posts_columns', 'wpcol_post_columns' );
add_filter( 'manage_pages_columns', 'wpcol_post_columns' );

function wpcol_post_column_data( $column, $post_id ) {
	if ( 'id' == $column ) {
		echo $post_id;
	} elseif ( 'thumbnail' == $column ) {
		$thumbnail = get_the_post_thumbnail( $post_id, array( 100, 100 ) );
		echo $thumbnail;
	} elseif ( 'wordcount' == $column ) {
		/*$_post = get_post($post_id);
		$content = $_post->post_content;
		$wordn = str_word_count(strip_tags($content));*/
		$wordn = get_post_meta( $post_id, 'wordn', true );
		echo $wordn;
	}
}

add_action( 'manage_posts_custom_column', 'wpcol_post_column_data', 10, 2 );
add_action( 'manage_pages_custom_column', 'wpcol_post_column_data', 10, 2 );

function wpcol_sortable_column( $columns ) {
	$columns['wordcount'] = 'wordn';

	return $columns;
}

add_filter( 'manage_edit-post_sortable_columns', 'wpcol_sortable_column' );

/*function wpcol_set_word_count() {
	$_posts = get_posts( array(
		'posts_per_page' => - 1,
		'post_type'      => 'post',
		'post_status'    => 'any'
	) );

	foreach ( $_posts as $p ) {
		$content = $p->post_content;
		$wordn   = str_word_count( strip_tags( $content ) );
		update_post_meta( $p->ID, 'wordn', $wordn );
	}
}

add_action( 'init', 'wpcol_set_word_count' );*/

function wpcol_sort_column_data( $wpquery ) {
	if ( ! is_admin() ) {
		return;
	}

	$orderby = $wpquery->get( 'orderby' );
	if ( 'wordn' == $orderby ) {
		$wpquery->set( 'meta_key', 'wordn' );
		$wpquery->set( 'orderby', 'meta_value_num' );
	}
}

add_action( 'pre_get_posts', 'wpcol_sort_column_data' );

function wpcol_update_wordcount_on_post_save($post_id){
	$p = get_post($post_id);
	$content = $p->post_content;
	$wordn   = str_word_count( strip_tags( $content ) );
	update_post_meta( $p->ID, 'wordn', $wordn );
}
add_action('save_post','wpcol_update_wordcount_on_post_save');