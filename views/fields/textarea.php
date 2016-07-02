<?php
/**
 * Field view for textarea
 */

if( isset( $args['default'] ) ) {
	$val = $args['default'];
} else {
	$val = '';
}

?>

<textarea class="tsg-textarea-field ts-scg-value-collector" rows="10"><?php echo $val; ?></textarea>