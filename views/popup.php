<?php
/**
 * @ts-core
 *
 * TS Shortcode generator popup renderer template
 *
 * Do not modify the markup or any classes. If you do so, you should modify the generator.js file as well
 *
 * Author: Theme Stones (Sohan Zaman)
 */

$shortcodes = array();

foreach( $template as $group ) {
	$shortcodes = array_merge( $group['shortcodes'], $shortcodes );
}

$ext_class = '';

if( count( $shortcodes ) == 1 ) {
	$ext_class .= ' only-shortcode';
}

if( count( $template ) == 1 ) {
	$ext_class .= ' only-tab';
}

?>
<header>
	<span class="icon-ts-generator"><img src="<?php echo esc_url( $this->icon ); ?>" alt="icon" /></span>
	<?php echo $this->title; ?>
	<span class="close-ts-generator"><i class="fa fa-times"></i></span>
</header>
<div class="ts-generator-root-js ts-scg-main-view<?php echo esc_attr( $ext_class ); ?>" data-name="<?php echo esc_attr( $this->name ); ?>">

	<ul class="ts-scg-groups-tabs">
		<?php
		foreach( $template as $i => $group ) {
			$active = '';
			if( $i === 0 ) {
				$active = ' class="active"';
			}
			echo sprintf( '<li data-target="%s"%s><span class="tsg-tooltip-int"><span class="tsg-tooltip-title">%s</span><span class="tsg-tooltip-float">%s</span></span></li>', esc_attr( $group['name'] ), $active, esc_attr( $group['title'] ), esc_attr( $group['desc'] ) );
		}
		?>
	</ul>

	<div class="ts-scg-shortcodes-thumbs">
		<?php
		foreach( $template as $i => $group ) {
			$active = '';
			if( $i === 0 ) {
				$active = ' active';
			}
			$output = '';
			$output .= sprintf( '<ul data-group-id="%s" class="ts-scg-groups-shortcodes%s">', $group['name'], $active );
			foreach( $group['shortcodes'] as $x => $shorcode ) {

				$active = '';

				if( count( $shortcodes ) == 1 ) {
					$active = 'class="active"';
				}

				if( isset( $shorcode['icon'] ) ) {
					$icon_source = $shorcode['icon'];
				} else {
					$icon_source = 'default.png';
				}

				if( filter_var( $icon_source, FILTER_VALIDATE_URL ) ) {
					$icon_file = $icon_source;
				} else {
					$icon_file = TS_SCG_URL . '/../assets/img/icons/default.png';
				}

				$output .= sprintf( '<li data-target-shortcode="%s"%s><div class="tsg-shortcode-module-inner">', esc_attr( $shorcode['tag'] . '_' . $i . $x ), $active );

				if( substr( $icon_source, 0, 3 ) === 'fa-' ) {
					$output .= sprintf( '<div class="module-icon"><i class="fa %s"></i></div>', esc_attr( $icon_source ) );
				} else {
					$output .= sprintf( '<img src="%s">', esc_url( $icon_file ) );
				}

				$output .= sprintf( '<span>%s</span>', $shorcode['title'] );
				$output .= '</div></li>';
			}
			$output .= '</ul>';
			echo $output;
		}
		?>    
	</div>

	<div class="ts-scg-views-canvas-root">

		<div class="tsg-no-shortcodes-text">
			<?php _e( 'Please select a shortcode to continue.', 'ts_core' ); ?>
		</div>

		<div class="tsg-shortcodes-loading">
			<div class="tsg-shortcodes-loading-helper"></div>
		</div>

	</div>

</div>
<footer>
	<input type="button" class="button button-primary button-large ts_add_sc" value="<?php _e( 'Add Shortcode', 'ts_core' ); ?>">
	<input type="button" class="button ts-cancel-sc" value="<?php _e( 'Cancel', 'ts_core' ); ?>">
</footer>