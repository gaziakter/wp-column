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
function wpcol_post_column($columns){
   $columns['id'] = __('ID', 'wp-column');
   $columns['thumbnail'] = __('Thumbnail', 'wp-column');
   return $columns;
}
add_filter( "manage_posts_columns", "wpcol_post_column" );


/**
 * get data from database
 */
function wpcol_post_column_data($columns, $post_id){
   if('id'==$columns){
      echo $post_id;
   } elseif('thumbnail'==$columns){
      $thumbnail = get_the_post_thumbnail( $post_id, 'thumbnail' );
      echo $thumbnail;
   }

}
add_action( 'manage_posts_custom_column', 'wpcol_post_column_data', 10, 2 );