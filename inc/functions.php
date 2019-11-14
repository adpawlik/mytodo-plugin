<?php

function mytodoapp_files(){ 
    wp_enqueue_style('mytodoapp-style', plugin_dir_url( __FILE__ ) . '../assets/temp/css/custom.min.css');
    wp_enqueue_style('googleFont-open-sans', 'https://fonts.googleapis.com/css?family=Open+Sans:400,700&display=swap&subset=latin-ext');
    wp_enqueue_script('mytodoapp-scripts', plugin_dir_url( __FILE__ ) . '../assets/temp/scripts/App.js', null, array(), true);

    wp_localize_script('mytodoapp-scripts', 'mytodoappData', array(
        'root_url' => get_site_url(),
        'nonce' => wp_create_nonce('wp_rest')
    ));
}

add_action('wp_enqueue_scripts', 'mytodoapp_files');


function mytodoap_post_types(){
    register_post_type('mytodoapp', array(
        'show_in_rest' => true,
        'supports' => array('title'),
        'public' => true,
        'show_ui' => true,
        'publicly_queryable' => false,
        'labels' => array(
            'name' => 'My to do list',
        ),
        'menu_icon' => 'dashicons-heart',

    ));

}
add_action('init', 'mytodoap_post_types');

function mytodoapp_shortcode() {
  ob_start(); 
  ?>
  <div id="mytodoapp-alerts" class="mytodoapp-alerts"></div>
  <ul id="mytodoapp-list" class="mytodoapp-list">
      <li class="mytodoapp-item mytodoapp-add">
          <div class="mytodoapp-list-item-checkbox-wrapper">
              <input class="mytodoapp-list-item-checkbox" id="mytodoapp-task-done" type="checkbox" value="yes" />
          </div>
          <div class="mytodoapp-list-item-title-wrapper">
              <input class="mytodoapp-task-title" id="mytodoapp-task-title" type="text" placeholder="Enter new task here..." />
          </div>
      </li>

  <?php
  $query = new WP_Query( array(
      'post_type' => 'mytodoapp',
      'posts_per_page' => -1,
      'order' => 'ASC',
      'orderby' => 'date',
      'author' => get_current_user_id()
  ) );
  if ( $query->have_posts() ) { ?>
      
          <?php while ( $query->have_posts() ) : $query->the_post();      
            $input_text = get_post_meta( get_the_ID(), 'mytodoapp_checkbox_value', true );
            $inputChecked = ( isset( $input_text ) && '' !== $input_text && 'yes' === $input_text ) ? 'yes' : 0;
          ?>
          <li data-id="<?php the_ID(); ?>" id="post-<?php the_ID(); ?>" class="mytodoapp-item-js mytodoapp-item post-<?php the_ID(); ?>">
              <div class="mytodoapp-list-item-checkbox-wrapper">
                  <input class="mytodoapp-list-item-checkbox" type="checkbox" name="mytodoapp_checkbox_value" value="yes" 
                  <?php checked( $inputChecked, 'yes' ); ?> />
              </div>
              <div class="mytodoapp-list-item-title-wrapper">
                  <input class="mytodoapp-list-item-title" type="text" value="<?php the_title(); ?>" /> 
              </div>
              <div class="mytodoapp-delete">&#10006;</div>
          </li>
          <?php endwhile;
          wp_reset_postdata(); ?>
      </ul>
  <?php $myvariable = ob_get_clean();
  return $myvariable;
  }
}
add_shortcode( 'mytodoapp', 'mytodoapp_shortcode' );

function mytodoap_make_list_private($data, $postarr){
    if ($data['post_type'] == 'mytodoapp'){
        $data['post_title'] = sanitize_text_field($data['post_title']);
    }
    if($data['post_type'] == 'mytodoapp' AND $data['post_status'] != 'trash'){
        $data['post_status'] = "private";
    }
    return $data;
}
add_filter('wp_insert_post_data', 'mytodoap_make_list_private', 10, 2);


function mytodoapp_redirect() {
    global $post;
    if (!is_user_logged_in() && $post->post_title == 'My to do app'){
        wp_redirect(esc_url(site_url('/')));
        exit;
    }
}
add_action( 'template_redirect', 'mytodoapp_redirect' );


function mytodoap_disable_title_prefix( $format, $post ) {
    if ( 'mytodoapp' === $post->post_type) {
        $format = '%s';
    }
    return $format;
}
add_filter( 'private_title_format', 'mytodoap_disable_title_prefix', 99, 2 );
add_filter( 'protected_title_format', 'mytodoap_disable_title_prefix', 99,2  );