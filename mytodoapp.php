<?php
/*
Plugin Name: My to do list
Plugin URI: https://pawlikadrian.pl
Description: My to do list
Version: 1.0.0
Author: Adrian Pawlik
Author URI: https://pawlikadrian.pl
Text Domain: mytodoapp
*/
register_activation_hook( __FILE__, 'mytodoapp_activate' );
    function mytodoapp_activate() {
       $post = array(     
                 'post_content'   => '[mytodoapp]',
                 'post_title'     =>'My to do app', 
                 'post_status'    =>  'publish', 
                 'post_type'      =>  'page'  
    );
    if (get_page_by_title('My to do app') === NULL ){
        wp_insert_post( $post ); 
    }
}

include_once('inc/functions.php');
include_once('inc/add_meta_box.php');