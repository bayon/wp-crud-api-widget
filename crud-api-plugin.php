<?php
/**
 * Plugin Name: CRUD-API-Plugin
 * Plugin URI: http://testing.com
 * Description: This is a plugin that allows us to test Ajax functionality in WordPress
 * Version: 1.0.0
 * Author:  bayon
 * Author URI: http://testing.com
 * License: GPL2
 * Create a Page: 
 * Insert this shortcode: [crud_api_plugin] in the content area.
 */
///////////////////////////////////////////////////////////////////////
 
//Load Class
require_once(plugin_dir_path(__FILE__).'/includes/crud-api-widget.php');
//Register Widget
function  register_crud_api(){
  //Widget Class Name
  register_widget('CRUD_API_WIDGET');
}
//Hook In
add_action('widgets_init','register_crud_api');
/**/
///////////////////////////////////////////////////////////////////////
 
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//~ CREATE CUSTOM POST TYPE
function crud_api_post_types() {

  // Note Post Type
  register_post_type('note', array(
    'show_in_rest' => true,
    'supports' => array('title', 'editor'),
    'public' => false,
    'show_ui' => true,
    'labels' => array(
      'name' => 'Notes',
      'add_new_item' => 'Add New Note',
      'edit_item' => 'Edit Note',
      'all_items' => 'All Notes',
      'singular_name' => 'Note'
    ),
    'menu_icon' => 'dashicons-welcome-write-blog'
  ));


}

add_action('init', 'crud_api_post_types');

 //~REGISTER RESTful FIELD
function custom_rest() {
  register_rest_field('post', 'authorName', array(
    'get_callback' => function() {return get_the_author();}
  ));

  register_rest_field('note', 'userNoteCount', array(
    'get_callback' => function() {return count_user_posts(get_current_user_id(), 'note');}
  ));
}

add_action('rest_api_init', 'custom_rest');


//~ DECLARE THE WINDOW VARS as workaround for WP_LOCALIZE_SCRIPT
function custom_files(){
   //~ CREATE GLOBAL NONCE VARIABLE:(place this code inside the function that includes your scripts)
    //HAD TO APPLY TO 'window.membership Object'!!!
    echo("<script type='text/javascript'> 
    <!-- 
    
    //~ DECLARE THE WINDOW VARS as workaround for WP_LOCALIZE_SCRIPT
    window.mudcake = 'mudcakes around the horn';
    console.log('custom_files function has been called....'); 
    window.crudApiPluginData = {};
    window.crudApiPluginData.root_url = '".get_site_url()."';
    window.crudApiPluginData.nonce = '".wp_create_nonce('wp_rest')."';
    
    // --> </script>");
    
   
   
   wp_enqueue_style( 'crud-api-style', plugin_dir_url( __FILE__ ). '/css/crud-api-style.css', null, '1.0' );
   
   wp_enqueue_script('crud-api-plugin-js', plugin_dir_url( __FILE__ ).'/js/crud-api-plugin.js', NULL, '1.0', true);
          
}
add_action('wp_enqueue_scripts', 'custom_files');

 