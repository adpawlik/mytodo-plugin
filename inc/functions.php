<?php

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
          <li data-id="<?php the_ID(); ?>" id="post-<?php the_ID(); ?>" class="mytodoapp-item post-<?php the_ID(); ?>">
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