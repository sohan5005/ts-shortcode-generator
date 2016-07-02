<?php
/**
 * Field view for checkbox
 */

if( isset( $args['default'] ) ) {
	if( is_array( $args['default'] ) ) {
		$default = $args['default'];
	} else {
		$default = array( $args['default'] );
	}
} else {
	$default = array();
}

$tag = $args['parent_tag'];

$name_attr = $tag . '[' . $name . ']';

?>
<div class="tsg-checkbox-field">
	<input type="hidden" class="ts-scg-value-collector" value="<?php echo implode( ', ', $default ); ?>">
	<?php

	foreach( $args['vals'] as $option ) {

		$checked = '';
		if( in_array( $option['val'], $default ) ) {
			$checked = ' checked';
		}

		echo sprintf( '<span><input type="checkbox" name="%5$s" id="%1$s" value="%2$s" %3$s><label for="%1$s">%4$s</label></span>', esc_attr( $name_attr . $option['val'] ), esc_attr( $option['val'] ), $checked, $option['label'], esc_attr( $name_attr ) );

	}

	?>
</div>