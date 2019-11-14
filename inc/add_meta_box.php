<?php 

function mytodoapp_add_meta_box() {
	add_meta_box( 'mytodoapp_metabox', esc_html__( 'Metabox', 'mytodoapp' ), 'mytodoapp_metabox_controls', 'mytodoapp', 'normal', 'low' );
}
add_action( 'add_meta_boxes', 'mytodoapp_add_meta_box' );

function mytodoapp_metabox_controls( $post ) {
	$meta = get_post_meta( $post->ID );
	$mytodoapp_checkbox_value = ( isset( $meta['mytodoapp_checkbox_value'][0] ) &&  'yes' === $meta['mytodoapp_checkbox_value'][0] ) ? 'yes' : 0;
	wp_nonce_field( 'mytodoapp_control_meta_box', 'mytodoapp_control_meta_box_nonce' );
	?>
		<p>
			<label><input type="checkbox" name="mytodoapp_checkbox_value" value="yes" <?php checked( $mytodoapp_checkbox_value, 'yes' ); ?> /><?php esc_attr_e( 'Task done?', 'mytodoapp' ); ?></label>
		</p>
	<?php
}

function mytodoapp_save_metaboxes( $post_id ) {

	if ( isset( $_POST['post_type'] ) && 'mytodoapp' === $_POST['post_type'] ) { 
		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return $post_id;
		}
	} else {
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return $post_id;
	}
	$mytodoapp_checkbox_value = ( isset( $_POST['mytodoapp_checkbox_value'] ) && 'yes' === $_POST['mytodoapp_checkbox_value'] ) ? 'yes' : 0; 
	update_post_meta( $post_id, 'mytodoapp_checkbox_value', esc_attr( $mytodoapp_checkbox_value ) );
}
add_action( 'save_post', 'mytodoapp_save_metaboxes' );