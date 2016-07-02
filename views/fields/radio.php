<?php
/**
 * Field view for radio
 */

if( isset( $args['default'] ) ) {
	$default = $args['default'];
} else {
	$default = '';
}

$tag = $args['parent_tag'];

$name_attr = $tag . '[' . $name . ']';

?>
<div class="tsg-radio-field">
	<input type="hidden" class="ts-scg-value-collector" value="<?php echo $default; ?>">
	<?php

	foreach( $args['vals'] as $option ) {
		echo sprintf( '<span><input type="radio" name="%5$s" id="%1$s" value="%2$s" %3$s><label for="%1$s">%4$s</label></span>', esc_attr( $name_attr . $option['val'] ), esc_attr( $option['val'] ), checked( $default, $option['val'], false ), $option['label'], esc_attr( $name_attr ) );
	}

	?>
</div>