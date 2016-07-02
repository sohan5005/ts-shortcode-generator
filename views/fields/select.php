<?php
/**
 * Field view for select
 */

if( isset( $args['default'] ) ) {
	$default = $args['default'];
} else {
	$default = isset( $args['vals'][0]['val'] ) ? $args['vals'][0]['val'] : '';
	$default = isset( $args['vals'][0]['opts'] ) ? $args['vals'][0]['opts'][0]['val'] : $default;
}

?>
<div class="tsg-select-field">

	<input type="hidden" class="ts-scg-value-collector" value="<?php echo $default; ?>">

	<select class="tsg-select-element">

		<?php
		if( isset( $args['vals'][0]['opts'] ) ) {
			foreach( $args['vals'] as $optionset ) {
				echo sprintf( '<optgroup label="%s">', $optionset['label'] );
					foreach( $optionset['opts'] as $option ) {
						echo sprintf( '<option value="%s"%s>%s</option>', esc_attr( $option['val'] ), selected( $default, $option['val'], false ), $option['label'] );
					}
				echo '</optgroup>';
			}
		} else {
			foreach( $args['vals'] as $option ) {
				echo sprintf( '<option value="%s"%s>%s</option>', esc_attr( $option['val'] ), selected( $default, $option['val'], false ), $option['label'] );
			}
		}
		?>

	</select>

</div>