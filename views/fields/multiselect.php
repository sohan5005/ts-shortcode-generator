<?php
/**
 * Field view for multiselect
 */

if( isset( $args['default'] ) ) {
	$default = ( is_array( $args['default'] ) ? $args['default'] : array( $args['default'] ) );
} else {
	$default = array();
}

?>
<div class="tsg-multiple-select-field">

	<input type="hidden" class="ts-scg-value-collector" value="<?php echo implode( ',', $default ); ?>">

	<select class="tsg-multiselect-select-element" multiple>

		<?php
		if( isset( $args['vals'][0]['opts'] ) ) {
			foreach( $args['vals'] as $optionset ) {
				echo sprintf( '<optgroup label="%s">', $optionset['label'] );
					foreach( $optionset['opts'] as $option ) {
						$selected = ( in_array( $option['val'], $default ) ? ' selected' : '' );
						echo sprintf( '<option value="%s"%s>%s</option>', esc_attr( $option['val'] ), $selected, $option['label'] );
					}
				echo '</optgroup>';
			}
		} else {
			foreach( $args['vals'] as $option ) {
				$selected = ( in_array( $option['val'], $default ) ? ' selected' : '' );
				echo sprintf( '<option value="%s"%s>%s</option>', esc_attr( $option['val'] ), $selected, $option['label'] );
			}
		}
		?>

	</select>

</div>