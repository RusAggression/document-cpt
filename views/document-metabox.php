<?php
defined( 'ABSPATH' ) || exit;

/**
 * @psalm-var array{ id: int, description: string, doc_id: int, doc_url: string, doc_author: string; doc_date: string } $params
 */

wp_nonce_field( 'document_meta_box', 'document_meta_box_nonce' );
?>
<p>
	<?php
	wp_editor(
		$params['description'],
		'document_description',
		[
			'media_buttons' => false,
			'textarea_rows' => 10,
			'teeny'         => true,
			'quicktags'     => true,
		]
	);
	?>
</p>
<div>
	<input type="hidden" id="selected_pdf" name="document_doc_id" value="<?= esc_attr( $params['doc_id'] ); ?>"/>
	<button type="button" class="button" id="select_pdf_button"><?php _e( 'Select document…', 'doc-cpt' ); ?></button>
	<button type="button" class="button" id="remove_pdf_button"><?php _e( 'Remove document', 'doc-cpt' ); ?></button>
	<div id="pdf_preview" style="margin-top:10px;">
		<?php if ( $params['doc_url'] ) : ?>
			<a href="<?= esc_url( $params['doc_url'] ); ?>" target="_blank"><?= esc_html( basename( $params['doc_url'] ) ); ?></a>
		<?php endif; ?>
	</div>
</div>
<p>
	<label for="document_author"><?php _e( 'Document Author:', 'doc-cpt' ); ?></label><br/>
	<input type="text" id="document_author" name="document_author" value="<?= esc_attr( $params['doc_author'] ); ?>" style="width: 100%"/>
</p>
<p>
	<label for="document_date"><?php _e( 'Document Date:', 'doc-cpt' ); ?></label><br/>
	<input type="date" id="document_date" name="document_date" value="<?= esc_attr( $params['doc_date'] ); ?>"/>
</p>

<script>
jQuery(document).ready(function($) {
	let frame;
	$('#select_pdf_button').on('click', function(e) {
		e.preventDefault();
		if (frame) {
			frame.open();
			return;
		}

		frame = wp.media({
			title: '<?= esc_js( __( 'Select PDF Document', 'doc-cpt' ) ); ?>',
			button: { text: '<?= esc_js( __( 'Use this document', 'doc-cpt' ) ); ?>' },
			library: { type: 'application/pdf' },
			multiple: false
		});

		frame.on('select', function() {
			const attachment = frame.state().get('selection').first().toJSON();
			$('#selected_pdf').val(attachment.id);
			$('#pdf_preview').html('<a href="' + encodeURI(attachment.url) + '" target="_blank">' + attachment.filename + '</a>');
			$('#remove_pdf_button').show();
		});

		frame.open();
	});

	$('#remove_pdf_button').on('click', function(e) {
		e.preventDefault();
		$('#selected_pdf').val('');
		$('#pdf_preview').html('');
		$(this).hide();
	});

<?php if ( ! $params['doc_id'] ) : ?>
	$('#remove_pdf_button').hide();
<?php endif; ?>
});
</script>