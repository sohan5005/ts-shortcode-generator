<?php
/**
 * Field view for upload media
 */

if( isset( $args['default'] ) ) {
	$val = $args['default'];
} else {
	$val = '';
}

$frame_title = ( isset( $args['window_title'] ) ? $args['window_title'] : __( 'Select Media', 'ts_core' ) );
$frame_button_title = ( isset( $args['insert_text'] ) ? $args['insert_text'] : __( 'Insert Media', 'ts_core' ) );

?>

<div class="tsg-upload-field">

	<input class="ts-scg-value-collector" readonly type="text" value="<?php echo $val; ?>">

	<input type="button" data-tsframe_title="<?php echo esc_attr( $frame_title ); ?>" data-tsframe_button_text="<?php echo esc_attr( $frame_button_title ); ?>" class="tsg-upload-button button" value="<?php _e( 'Upload', 'ts_core' ); ?>">
	<input type="button" class="tsg-upload-remove-button button" value="x">

	<img class="ts-scg-upload-preview">

</div>