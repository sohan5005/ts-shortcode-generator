<?php
/**
 * Field view for richedit - default WP editor
 */

?>
<div class="ts-richedit-field">
	<div class="customEditor wp-core-ui wp-editor-wrap tmce-active">
		<div class="wp-editor-tools hide-if-no-js">
			<div class="wp-media-buttons custom_upload_buttons">
				<?php do_action( 'media_buttons' ); ?>
			</div>
		</div>
		<div class="wp-editor-container">
			<textarea class="wp-editor-area" rows="10" cols="50" rows="3"></textarea>
		</div>
	</div>
</div>