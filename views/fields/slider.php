<?php
/**
 * Field view for slider
 */

if( isset( $args['default'] ) ) {
	$val = $args['default'];
} else {
	$val = '';
}

$slider_atts = array();

if( isset( $args['default'] ) ) {
	$slider_atts[] = sprintf( 'data-slider-default="%s"', esc_attr( $args['default'] ) );
}

if( isset( $args['min'] ) ) {
	$slider_atts[] = sprintf( 'data-slider-min="%s"', esc_attr( $args['min'] ) );
}

if( isset( $args['max'] ) ) {
	$slider_atts[] = sprintf( 'data-slider-max="%s"', esc_attr( $args['max'] ) );
}

if( isset( $args['step'] ) ) {
	$slider_atts[] = sprintf( 'data-slider-step="%s"', esc_attr( $args['step'] ) );
}

?>

<div class="tsg-slider-field" <?php echo implode( ' ', $slider_atts ) ?></div>

	<input class="ts-scg-value-collector" type="number" value="<?php echo $val; ?>">

	<div class="tsg-init-slider"></div>

</div>