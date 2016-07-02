<?php
/**
 * Field view for font-awesome icon choser
 */

if( isset( $args['default'] ) ) {
	$val = $args['default'];
} else {
	$val = '';
}

$icons = ts_get_fa_icons_array();

if( $val !== '' ) {
	$pre = $val;
} else {
	$pre = __( 'Please select an icon', 'ts_core' );
}

?>
<div class="tsg-icons-field">

	<input class="ts-scg-value-collector" type="hidden" value="<?php echo $val; ?>">

	<div class="ts-scg-icons-header">
		<span class="ts-scg-icons-default-now"><?php echo $pre; ?></span>
		<span class="ts-scg-icons-toggle"><i class="fa <?php echo esc_attr( $val ); ?>"></i></span>
	</div>

	<ul class="ts-scg-icons-collection">
		<?php foreach( $icons as $icon ) {
			if( $icon !== $val ) {
				echo sprintf( '<li data-value="%1$s"><i class="fa %1$s"></i></li>', $icon );
			} else {
				echo sprintf( '<li data-value="%1$s" class="active"><i class="fa %1$s"></i></li>', $icon );
			}
		} ?>
	</ul>

</div>