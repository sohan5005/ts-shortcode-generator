<?php
/**
 * Field view for toggle, foremly single checkbox
 */

$true = ( isset( $args['true'] ) ? $args['true'] : 'yes' );
$false = ( isset( $args['false'] ) ? $args['false'] : 'no' );
$default = ( isset( $args['default'] ) ? $args['default'] : true );
$val = ( $default == 'true' ? $true : $false );
$checked = ( $default == 'true' ? 'checked' : '' );

?>
<div class="tsg-toggle-field">
	<input class="ts-scg-value-collector" type="hidden" value="<?php echo esc_attr( $val ); ?>">
	<input class="tsg-toggle-field-checkbox" data-true="<?php echo esc_attr( $true ); ?>" data-false="<?php echo esc_attr( $false ); ?>" type="checkbox" <?php echo $checked; ?>>
</div>