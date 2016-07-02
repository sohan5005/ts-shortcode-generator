<?php
/**
 * Field view for input
 */

if( isset( $args['default'] ) ) {
	$val = $args['default'];
} else {
	$val = '';
}

?>

<input class="tsg-input-field ts-scg-value-collector" type="text" value="<?php echo $val; ?>">