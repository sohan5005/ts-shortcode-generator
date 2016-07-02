<?php
/**
 * Field view for color choser
 */

if( isset( $args['default'] ) ) {
	$val = $args['default'];
} else {
	$val = '';
}

if( isset( $args['format'] ) ) {
	$col_format = esc_attr( $args['format'] );
} else {
	$col_format = 'hex';
}

?>


<div class="tsg-colorpicker-field">

	<input class="ts-scg-value-collector" type="hidden" value="<?php echo esc_attr( $val ); ?>">

	<input class="tsg-colorpicker-input" data-format="<?php echo $col_format; ?>" type="text" value="<?php echo $val; ?>">
	<span class="tsg-colorpicker-preview" style="background: <?php echo esc_attr( $val ); ?>"></span>

</div>